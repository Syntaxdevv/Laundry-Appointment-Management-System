@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page-heading', '')

@section('content')

@php
    $allStaff = \App\Models\User::whereIn('role', ['staff', 'delivery']);

    $totalStaff = (clone $allStaff)->where('role', 'staff')->count();
    $totalDelivery = (clone $allStaff)->where('role', 'delivery')->count();
    $totalAccounts = (clone $allStaff)->count();
@endphp

<div class="text-[#29312D] [font-family:'DM_Sans',Arial,sans-serif]">

    {{-- Staff Header --}}
    <div class="mb-[22px] flex flex-wrap items-center justify-between gap-[15px]">
        <div>
            <h2 class="mb-[5px] text-[23px] font-bold text-[#252A34]">
                Staff Management
            </h2>
            <p class="text-[13px] text-[#78827B]">
                Manage laundry staff and delivery personnel accounts.
            </p>
        </div>

        <a
            href="{{ route('admin.staff.create') }}"
            class="inline-flex items-center gap-2 rounded-md bg-[#00A878] px-4 py-[11px] text-[12px] font-semibold text-white no-underline transition-colors hover:bg-[#008C64] hover:text-white"
        >
            <i class="fa-solid fa-plus"></i>
            Add Account
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="mb-[18px] rounded-md bg-[#E8F5EF] px-[15px] py-3 text-[12px] text-[#008C64]">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Staff Summary --}}
    <div class="mb-5 grid grid-cols-3 gap-[15px] max-[650px]:grid-cols-1">

        <div class="rounded-lg border border-[#E1E6E0] bg-white px-[19px] py-[17px]">
            <span class="mb-2 block text-[11px] text-[#78827B]">
                Total Accounts
            </span>
            <strong class="block text-2xl font-bold text-[#252A34]">
                {{ $totalAccounts }}
            </strong>
        </div>

        <div class="rounded-lg border border-[#E1E6E0] bg-white px-[19px] py-[17px]">
            <span class="mb-2 block text-[11px] text-[#78827B]">
                Laundry Staff
            </span>
            <strong class="block text-2xl font-bold text-[#252A34]">
                {{ $totalStaff }}
            </strong>
        </div>

        <div class="rounded-lg border border-[#E1E6E0] bg-white px-[19px] py-[17px]">
            <span class="mb-2 block text-[11px] text-[#78827B]">
                Delivery Personnel
            </span>
            <strong class="block text-2xl font-bold text-[#252A34]">
                {{ $totalDelivery }}
            </strong>
        </div>

    </div>

    {{-- Staff Table Card --}}
    <div class="overflow-hidden rounded-lg border border-[#E1E6E0] bg-white">

        {{-- Search --}}
        <div class="border-b border-[#edf1ec] px-5 py-[17px] max-[650px]:p-[14px]">
            <form method="GET" action="{{ route('admin.staff.index') }}">
                <input
                    type="search"
                    name="search"
                    class="w-full max-w-[380px] rounded-md border border-[#dce3dc] bg-white px-[13px] py-[11px] text-[12px] text-[#29312D] outline-none placeholder:text-[#78827B] focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/10"
                    placeholder="Search by name or email..."
                    value="{{ request('search') }}"
                >
            </form>
        </div>

        {{-- Staff Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full border-collapse whitespace-nowrap">

                <thead class="bg-[#F7F9F6]">
                    <tr>
                        <th class="border-b border-[#E1E6E0] px-[19px] py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B] max-[650px]:px-[14px]">
                            Account ID
                        </th>
                        <th class="border-b border-[#E1E6E0] px-[19px] py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B] max-[650px]:px-[14px]">
                            Name
                        </th>
                        <th class="border-b border-[#E1E6E0] px-[19px] py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B] max-[650px]:px-[14px]">
                            Account Role
                        </th>
                        <th class="border-b border-[#E1E6E0] px-[19px] py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B] max-[650px]:px-[14px]">
                            Email Address
                        </th>
                        <th class="border-b border-[#E1E6E0] px-[19px] py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B] max-[650px]:px-[14px]">
                            Status
                        </th>
                        <th class="border-b border-[#E1E6E0] px-[19px] py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B] max-[650px]:px-[14px]">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse(($staff ?? collect()) as $member)
                        @php
                            $role = strtolower($member->role ?? 'staff');

                            $roleClass = match ($role) {
                                'staff' => 'laundry',
                                'delivery' => 'delivery',
                                default => 'other',
                            };

                            $roleLabel = match ($role) {
                                'staff' => 'Laundry Staff',
                                'delivery' => 'Delivery Personnel',
                                default => ucfirst($role),
                            };

                            $isActive = strtolower(
                                (string) ($member->status ?? 'active')
                            ) === 'active';
                        @endphp

                        <tr class="hover:bg-[#FAFCFA]">

                            <td class="border-b border-[#edf1ec] px-[19px] py-[15px] text-[12px] font-bold text-[#29312D] max-[650px]:px-[14px]">
                                #LL-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="border-b border-[#edf1ec] px-[19px] py-[15px] text-[12px] font-semibold text-[#29312D] max-[650px]:px-[14px]">
                                {{ $member->name }}
                            </td>

                            <td class="border-b border-[#edf1ec] px-[19px] py-[15px] text-[12px] text-[#64716A] max-[650px]:px-[14px]">
                                <span @class([
                                    'inline-block rounded-[5px] px-[9px] py-[5px] text-[10px] font-bold',
                                    'bg-[#E8F5EF] text-[#008C64]' => $roleClass === 'laundry',
                                    'bg-[#EAF1FF] text-[#315EAD]' => $roleClass === 'delivery',
                                    'bg-[#F1F3F1] text-[#66716A]' => $roleClass === 'other',
                                ])>
                                    {{ $roleLabel }}
                                </span>
                            </td>

                            <td class="border-b border-[#edf1ec] px-[19px] py-[15px] text-[12px] text-[#64716A] max-[650px]:px-[14px]">
                                {{ $member->email }}
                            </td>

                            <td class="border-b border-[#edf1ec] px-[19px] py-[15px] text-[12px] text-[#64716A] max-[650px]:px-[14px]">
                                <span @class([
                                    'inline-flex items-center gap-[6px] rounded-full px-[9px] py-[5px] text-[10px] font-bold',
                                    'bg-[#E8F5EF] text-[#138568]' => $isActive,
                                    'bg-[#FFF0F0] text-[#D94848]' => !$isActive,
                                ])>
                                    <span class="h-[6px] w-[6px] rounded-full bg-current"></span>
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="border-b border-[#edf1ec] px-[19px] py-[15px] text-[12px] text-[#64716A] max-[650px]:px-[14px]">
                                <a
                                    href="{{ route('admin.staff.show', $member->id) }}"
                                    class="inline-block rounded-[5px] border border-[#DCE3DC] bg-white px-[11px] py-[6px] text-[11px] font-semibold text-[#008C64] no-underline transition-colors hover:bg-[#E8F5EF]"
                                >
                                    View
                                </a>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-[12px] text-[#78827B]">
                                <strong>No staff accounts found.</strong>
                                <br>
                                <span class="mt-[7px] inline-block">
                                    Create a Laundry Staff or Delivery Personnel account to get started.
                                </span>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- Footer and Pagination --}}
        <div class="flex flex-wrap items-center justify-between gap-[15px] border-t border-[#E1E6E0] px-5 py-4 text-[11px] text-[#78827B] max-[650px]:p-[14px]">

            <span>
                Showing {{ ($staff ?? collect())->count() }}
                of {{ ($staff ?? collect())->total() }}
                accounts
            </span>

            @if(isset($staff) && method_exists($staff, 'links'))
                {{ $staff->links() }}
            @endif

        </div>

    </div>

</div>
@endsection
