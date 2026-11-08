<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    /**
     * Admin dashboard.
     */
    public function index()
    {
        $totalOrders = Booking::count();

        $pendingOrders = Booking::whereRaw(
            'LOWER(status) = ?',
            ['pending']
        )->count();

        $inProgressOrders = Booking::whereRaw(
            'LOWER(status) IN (?, ?, ?, ?)',
            ['washing', 'drying', 'processing', 'ready']
        )->count();

        $completedOrders = Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        )->count();

        $cancelledOrders = Booking::whereRaw(
            'LOWER(status) = ?',
            ['cancelled']
        )->count();

        $recentOrders = Booking::with(['service', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::tomorrow();

        $dailyCounts = Booking::query()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->selectRaw(
                'DATE(created_at) as booking_date, COUNT(*) as total'
            )
            ->groupBy('booking_date')
            ->orderBy('booking_date')
            ->pluck('total', 'booking_date');

        $chartLabels = [];
        $chartValues = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->toDateString();

            $chartLabels[] = $date->format('D');
            $chartValues[] = (int) ($dailyCounts[$key] ?? 0);
        }

        $statusCounts = Booking::query()
            ->selectRaw(
                'LOWER(status) as booking_status, COUNT(*) as total'
            )
            ->groupBy('booking_status')
            ->pluck('total', 'booking_status');

        $statusLabels = [
            'Pending',
            'Washing',
            'Drying',
            'Processing',
            'Ready',
            'Completed',
            'Cancelled',
        ];

        $statusKeys = [
            'pending',
            'washing',
            'drying',
            'processing',
            'ready',
            'completed',
            'cancelled',
        ];

        $statusValues = [];

        foreach ($statusKeys as $key) {
            $statusValues[] = (int) ($statusCounts[$key] ?? 0);
        }

        // Revenue calculation remains unchanged.
        $monthlyRevenue = (float) Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        )
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total_price');

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders',
            'cancelledOrders',
            'monthlyRevenue',
            'recentOrders',
            'chartLabels',
            'chartValues',
            'statusLabels',
            'statusValues'
        ));
    }

    /**
     * Display admin orders.
     */
    public function orders()
    {
        $query = Booking::with([
            'service',
            'user',
            'assignedStaff'
        ])->latest();

        $search = trim((string) request('search', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $status = strtolower((string) request('status', 'all'));

        if ($status !== '' && $status !== 'all') {
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display one order.
     */
    public function showOrder(Booking $booking)
    {
        $booking->load([
            'service',
            'user',
            'assignedStaff'
        ]);

        return view('admin.orders.show', compact('booking'));
    }

    /**
     * Show order edit form.
     */
    public function editOrder(Booking $booking)
    {
        $booking->load([
            'service',
            'user',
            'assignedStaff'
        ]);

        // Only users with staff role can be assigned.
        $staffUsers = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        return view('admin.orders.edit', compact(
            'booking',
            'staffUsers'
        ));
    }

    /**
     * Update an order and assign laundry staff.
     */
    public function updateOrder(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'weight_kg' => [
                'required',
                'numeric',
                'min:0',
            ],

            'total_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'service_option' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_date' => [
                'nullable',
                'date',
            ],

            'pickup_time' => [
                'nullable',
                'string',
                'max:50',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'status' => [
                'required',
                'string',
                Rule::in([
                    'pending',
                    'washing',
                    'drying',
                    'processing',
                    'ready',
                    'completed',
                    'cancelled',
                ]),
            ],

            // Allow only existing users with staff role.
            'assigned_staff_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'staff');
                }),
            ],
        ]);

        $booking->update($validated);

        return redirect()
            ->route('admin.orders.show', $booking)
            ->with('success', 'Order updated and staff assignment saved successfully.');
    }

    /**
     * Display bookings.
     */
    public function bookings()
    {
        $query = Booking::with(['service', 'user'])->latest();

        $search = trim((string) request('search', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $status = strtolower((string) request('status', 'all'));

        if ($status !== '' && $status !== 'all') {
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Display customers.
     */
    public function customers()
    {
        $query = Booking::query()
            ->with('user')
            ->whereNotNull('user_id')
            ->select('user_id')
            ->selectRaw('MAX(created_at) as latest_order_at')
            ->selectRaw('COUNT(*) as total_orders')
            ->groupBy('user_id')
            ->orderByDesc('latest_order_at');

        $search = trim((string) request('search', ''));

        if ($search !== '') {
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(10)->withQueryString();

        $totalCustomers = Booking::query()
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $customersWithOrders = $totalCustomers;

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'customersWithOrders'
        ));
    }

    /**
     * Display services.
     */
    public function services()
    {
        return view('admin.services');
    }

    /**
     * Display payments.
     */
    public function payments()
    {
        $query = Booking::with(['service', 'user'])->latest();

        $search = trim((string) request('search', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $status = strtolower((string) request('status', 'all'));

        if ($status === 'pending') {
            $query->whereRaw('LOWER(status) = ?', ['pending']);
        } elseif ($status === 'paid') {
            $query->whereRaw('LOWER(status) = ?', ['completed']);
        } elseif ($status === 'failed') {
            $query->whereRaw('1 = 0');
        }

        // Payment records matching the current search and filter.
        $payments = $query->paginate(10)->withQueryString();

        // Total amount paid for completed bookings.
        $paidAmount = Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        )->sum('total_price');

        // Number of bookings awaiting payment.
        $pendingPayments = Booking::whereRaw(
            'LOWER(status) = ?',
            ['pending']
        )->count();

        return view('admin.payments', compact(
            'payments',
            'paidAmount',
            'pendingPayments'
        ));
    }

    /**
     * Display reports.
     */
    public function reports()
    {
        $totalOrders = Booking::count();

        $completedOrders = Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        )->count();

        $cancelledOrders = Booking::whereRaw(
            'LOWER(status) = ?',
            ['cancelled']
        )->count();

        $pendingOrders = Booking::whereRaw(
            'LOWER(status) = ?',
            ['pending']
        )->count();

        $completedBookings = Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        );

        $totalSales = (float) (clone $completedBookings)->sum('total_price');

        $averageOrderValue = $completedOrders > 0
            ? $totalSales / $completedOrders
            : 0;

        // Revenue from completed bookings this month.
        $monthlyRevenue = (float) Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        )
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total_price');

        // Total expenses from recorded expense entries.
        $totalExpenses = (float) Expense::sum('amount');

        // Total profit = Total Sales - Total Expenses.
        $totalProfit = $totalSales - $totalExpenses;

        // Sales for the last 7 days.
        $startDate = now()->startOfDay()->subDays(6);
        $endDate = now()->startOfDay()->addDay();

        $dailySales = Booking::whereRaw(
            'LOWER(status) = ?',
            ['completed']
        )
            ->where('updated_at', '>=', $startDate)
            ->where('updated_at', '<', $endDate)
            ->selectRaw('DATE(updated_at) as sales_date, SUM(total_price) as total')
            ->groupBy('sales_date')
            ->pluck('total', 'sales_date');

        $salesByDay = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->toDateString();

            $salesByDay[] = [
                'label' => $date->format('D'),
                'total' => (float) ($dailySales[$key] ?? 0),
            ];
        }

        // Top services based on completed bookings.
        $serviceTotals = Booking::with('service')
            ->whereRaw('LOWER(status) = ?', ['completed'])
            ->get()
            ->groupBy(function ($booking) {
                return $booking->service?->name ?? 'Unknown Service';
            })
            ->map(function ($bookings, $serviceName) {
                return [
                    'service_name' => $serviceName,
                    'total' => $bookings->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(4)
            ->values();

        $serviceBookingTotal = $serviceTotals->sum('total');

        $topServices = $serviceTotals->map(function ($service) use ($serviceBookingTotal) {
            $service['percentage'] = $serviceBookingTotal > 0
                ? round(($service['total'] / $serviceBookingTotal) * 100)
                : 0;

            return (object) $service;
        });

        $totalCustomers = Booking::whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        return view('admin.reports', compact(
            'totalOrders',
            'completedOrders',
            'cancelledOrders',
            'pendingOrders',
            'totalSales',
            'totalExpenses',
            'averageOrderValue',
            'totalProfit',
            'monthlyRevenue',
            'salesByDay',
            'topServices',
            'totalCustomers'
        ));
    }

    /**
     * Display staff accounts only.
     */
    public function staff()
    {
        $query = User::where('role', 'staff')
            ->latest();

        $search = trim((string) request('search', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $query->paginate(10)->withQueryString();

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the Add Staff form.
     */
    public function createStaff()
    {
        return view('admin.staff.create');
    }

    /**
     * Create a staff account.
     */
    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'staff',
        ]);

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Staff account created successfully.');
    }

    /**
     * Display staff account details.
     */
    public function showStaff($id)
    {
        $user = User::where('role', 'staff')
            ->findOrFail($id);

        return view('admin.staff.show', compact('user'));
    }

    /**
     * Display settings.
     */
    public function settings()
    {
        return view('admin.settings');
    }
}
