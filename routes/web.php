<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\WalkInController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffOrdersController;
use App\Http\Controllers\Staff\LaundryProcessingController;
use App\Http\Controllers\Staff\PickupDeliveryController;
use App\Http\Controllers\Staff\InventoryController;
use App\Http\Controllers\Staff\DeliveryAssignmentController;
use App\Http\Controllers\Staff\DeliveryDispatchController;
use App\Http\Controllers\Delivery\DeliveryDashboardController;
use App\Http\Controllers\Delivery\DeliveryDeliveriesController;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::middleware(['auth', 'verified'])
    ->get('/dashboard', function () {

        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        if ($user->role === 'delivery') {
            return redirect()->route('delivery.dashboard');
        }

        return redirect()->route('customer.dashboard');

    })->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', [CustomerController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/bookings', [CustomerController::class, 'index'])
            ->name('bookings.index');

        Route::get('/bookings/create', [CustomerController::class, 'create'])
            ->name('bookings.create');

        Route::post('/bookings', [CustomerController::class, 'store'])
            ->name('bookings.store');

        Route::get('/bookings/{id}', [CustomerController::class, 'show'])
            ->whereNumber('id')
            ->name('bookings.show');

        Route::post('/bookings/{id}/cancel', [CustomerController::class, 'cancel'])
            ->whereNumber('id')
            ->name('bookings.cancel');

        Route::get('/bookings/{id}/paypal/success', [
            CustomerController::class,
            'paypalSuccess'
        ])->whereNumber('id')->name('paypal.success');

        Route::get('/bookings/{id}/paypal/cancel', [
            CustomerController::class,
            'paypalCancel'
        ])->whereNumber('id')->name('paypal.cancel');

        Route::get('/bookings/{id}/payment-success', [
            CustomerController::class,
            'paymentSuccess'
        ])->whereNumber('id')->name('paypal.success-page');

        Route::get('/bookings/{id}/demo-payment', [
            CustomerController::class,
            'demoPayment'
        ])->whereNumber('id')->name('demo-payment.show');

        Route::post('/bookings/{id}/demo-payment', [
            CustomerController::class,
            'simulateDemoPayment'
        ])->whereNumber('id')->name('demo-payment.simulate');

    });

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [AdminDashboardController::class, 'orders'])
            ->name('orders.index');

        Route::get('/orders/create', function () {
            return view('admin.orders.create');
        })->name('orders.create');

        Route::get('/orders/{booking}/view', [
            AdminDashboardController::class,
            'showOrder'
        ])->name('orders.show');

        Route::get('/orders/{booking}/edit', [
            AdminDashboardController::class,
            'editOrder'
        ])->name('orders.edit');

        Route::put('/orders/{booking}', [
            AdminDashboardController::class,
            'updateOrder'
        ])->name('orders.update');

        Route::get('/bookings', [AdminDashboardController::class, 'bookings'])
            ->name('bookings.index');

        Route::get('/walk-ins', [WalkInController::class, 'index'])
            ->name('walk-ins.index');

        Route::post('/walk-ins', [WalkInController::class, 'store'])
            ->name('walk-ins.store');

        Route::get('/customers', [AdminDashboardController::class, 'customers'])
            ->name('customers.index');

        Route::get('/inventory', [AdminInventoryController::class, 'index'])
            ->name('inventory.index');

        Route::get('/inventory/create', [AdminInventoryController::class, 'create'])
            ->name('inventory.create');

        Route::post('/inventory', [AdminInventoryController::class, 'store'])
            ->name('inventory.store');

        Route::get('/inventory/{item}/edit', [AdminInventoryController::class, 'edit'])
            ->whereNumber('item')
            ->name('inventory.edit');

        Route::put('/inventory/{item}', [AdminInventoryController::class, 'update'])
            ->whereNumber('item')
            ->name('inventory.update');

        Route::post('/inventory/{item}/stock', [
            AdminInventoryController::class,
            'adjustStock'
        ])->whereNumber('item')->name('inventory.stock');

        Route::get('/payments', [AdminDashboardController::class, 'payments'])
            ->name('payments.index');

        Route::get('/reports', [AdminDashboardController::class, 'reports'])
            ->name('reports.index');

        Route::get('/staff', [AdminDashboardController::class, 'staff'])
            ->name('staff.index');

        Route::get('/staff/create', [AdminDashboardController::class, 'createStaff'])
            ->name('staff.create');

        Route::post('/staff', [AdminDashboardController::class, 'storeStaff'])
            ->name('staff.store');

        Route::get('/staff/{id}', [AdminDashboardController::class, 'showStaff'])
            ->whereNumber('id')
            ->name('staff.show');

    });

Route::middleware(['auth', 'verified'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [StaffOrdersController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{booking}', [
            StaffOrdersController::class,
            'show'
        ])->whereNumber('booking')->name('orders.show');

        Route::get('/walk-ins', [
            \App\Http\Controllers\Staff\WalkInController::class,
            'index'
        ])->name('walk-ins.index');

        Route::post('/walk-ins', [
            \App\Http\Controllers\Staff\WalkInController::class,
            'store'
        ])->name('walk-ins.store');

        Route::get('/processing', [LaundryProcessingController::class, 'index'])
            ->name('processing.index');

        Route::patch('/processing/{booking}/status', [
            LaundryProcessingController::class,
            'updateStatus'
        ])->name('processing.status');

        Route::get('/inventory', [InventoryController::class, 'index'])
            ->name('inventory.index');

        Route::get('/inventory/create', [InventoryController::class, 'create'])
            ->name('inventory.create');

        Route::post('/inventory', [InventoryController::class, 'store'])
            ->name('inventory.store');

        Route::get('/inventory/history', [InventoryController::class, 'history'])
            ->name('inventory.history');

        Route::get('/inventory/{item}/edit', [InventoryController::class, 'edit'])
            ->name('inventory.edit');

        Route::put('/inventory/{item}', [InventoryController::class, 'update'])
            ->name('inventory.update');

        Route::delete('/inventory/{item}', [InventoryController::class, 'destroy'])
            ->name('inventory.destroy');

        Route::post('/inventory/{item}/stock', [
            InventoryController::class,
            'stock'
        ])->name('inventory.stock');

        Route::get('/pickup-pricing', function () {

            $statuses = [
                'pending',
                'confirmed',
                'out_for_pickup',
                'picked_up',
                'in_transit',
                'delivered',
                'cancelled',
            ];

            $requests = \App\Models\PickupDelivery::query()
                ->orderByDesc('scheduled_at')
                ->paginate(10);

            return view('staff.pickup-pricing.index', compact(
                'statuses',
                'requests'
            ));

        })->name('pickup-pricing.index');

        Route::get('/tasks', [
            \App\Http\Controllers\Staff\TaskController::class,
            'index'
        ])->name('tasks.index');

        Route::patch('/tasks/{task}/status', [
            \App\Http\Controllers\Staff\TaskController::class,
            'updateStatus'
        ])->name('tasks.updateStatus');

        Route::get('/pickup-delivery', [PickupDeliveryController::class, 'index'])
            ->name('pickup-delivery.index');

        Route::get('/pickup-delivery/{pickupDelivery}', [
            PickupDeliveryController::class,
            'show'
        ])->whereNumber('pickupDelivery')
            ->name('pickup-delivery.show');

        Route::patch('/pickup-delivery/{pickupDelivery}/status', [
            PickupDeliveryController::class,
            'updateStatus'
        ])->whereNumber('pickupDelivery')
            ->name('pickup-delivery.status');

        Route::get('/delivery-assignments', [
            DeliveryAssignmentController::class,
            'index'
        ])->name('delivery-assignments.index');

        Route::patch('/delivery-assignments/{bookingId}/assign', [
            DeliveryAssignmentController::class,
            'assign'
        ])->whereNumber('bookingId')->name('delivery-assignments.assign');

        Route::get('/delivery-dispatch', [
            DeliveryDispatchController::class,
            'index'
        ])->name('delivery-dispatch.index');

        Route::patch('/delivery-dispatch/{booking}/assign', [
            DeliveryDispatchController::class,
            'assign'
        ])->whereNumber('booking')->name('delivery-dispatch.assign');

    });

Route::middleware(['auth', 'verified'])
    ->prefix('delivery')
    ->name('delivery.')
    ->group(function () {

        Route::get('/dashboard', [
            DeliveryDashboardController::class,
            'index'
        ])->name('dashboard');

        Route::get('/deliveries', [
            DeliveryDeliveriesController::class,
            'index'
        ])->name('deliveries.index');

        Route::get('/deliveries/{id}', [
            DeliveryDeliveriesController::class,
            'show'
        ])->whereNumber('id')->name('deliveries.show');

        Route::patch('/deliveries/{id}/status', [
            DeliveryDeliveriesController::class,
            'updateStatus'
        ])->whereNumber('id')->name('deliveries.status');

        Route::get('/tracking', [
            DeliveryDashboardController::class,
            'tracking'
        ])->name('tracking.index');

        Route::get('/history', [
            DeliveryDashboardController::class,
            'history'
        ])->name('history.index');

        Route::get('/profile', [
            DeliveryDashboardController::class,
            'profile'
        ])->name('profile');

        Route::patch('/profile', [
            DeliveryDashboardController::class,
            'updateProfile'
        ])->name('profile.update');

    });

Route::get('/test-email', function () {

    Mail::raw(
        'This is a test email from LinisLaba Laundry Management System.',
        function ($message) {
            $message->to('YOUR-EMAIL@gmail.com')
                ->subject('LinisLaba Email Test');
        }
    );

    return 'Test email sent successfully!';

});

require __DIR__ . '/auth.php';