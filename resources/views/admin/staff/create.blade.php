@extends('layouts.admin')

@section('title', 'Add Staff')
@section('page-heading', '')

@section('content')
<div class="mx-auto max-w-[850px] font-['DM_Sans',Arial,sans-serif] text-[#17365d]">

    <div class="mb-6">
        <h1 class="mb-[6px] m-0 text-[26px] font-bold">Add Staff</h1>
        <p class="m-0 text-[13px] text-[#718096]">Create a laundry staff or delivery personnel account.</p>
    </div>

    <div class="rounded-[9px] border border-[#e5ebf3] bg-white p-7 max-[600px]:p-[18px]">

        @if ($errors->any())
            <div class="mb-5 rounded-md bg-[#fff0f0] p-[14px] text-[12px] text-[#b91c1c]">
                Please check the form for errors.
            </div>
        @endif

        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf

            <div class="grid grid-cols-2 gap-5 max-[600px]:grid-cols-1">

                <div class="flex flex-col gap-2">
                    <label for="name" class="text-[12px] font-semibold text-[#475569]">Full Name *</label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter full name"
                        required
                        class="w-full rounded-md border border-[#dfe6ef] bg-white p-3 text-[13px] text-[#17365d] outline-none focus:border-[#3288e8] focus:ring-[3px] focus:ring-[#3288e8]/10"
                    >

                    @error('name')
                        <span class="text-[11px] text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="email" class="text-[12px] font-semibold text-[#475569]">Email Address *</label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="staff@example.com"
                        required
                        class="w-full rounded-md border border-[#dfe6ef] bg-white p-3 text-[13px] text-[#17365d] outline-none focus:border-[#3288e8] focus:ring-[3px] focus:ring-[#3288e8]/10"
                    >

                    @error('email')
                        <span class="text-[11px] text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password" class="text-[12px] font-semibold text-[#475569]">Password *</label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="At least 8 characters"
                        minlength="8"
                        required
                        class="w-full rounded-md border border-[#dfe6ef] bg-white p-3 text-[13px] text-[#17365d] outline-none focus:border-[#3288e8] focus:ring-[3px] focus:ring-[#3288e8]/10"
                    >

                    @error('password')
                        <span class="text-[11px] text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password_confirmation" class="text-[12px] font-semibold text-[#475569]">
                        Confirm Password *
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Re-enter password"
                        minlength="8"
                        required
                        class="w-full rounded-md border border-[#dfe6ef] bg-white p-3 text-[13px] text-[#17365d] outline-none focus:border-[#3288e8] focus:ring-[3px] focus:ring-[#3288e8]/10"
                    >
                </div>

                <div class="col-span-full flex flex-col gap-2 max-[600px]:col-span-1">
                    <label for="role" class="text-[12px] font-semibold text-[#475569]">Account Role *</label>

                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full rounded-md border border-[#dfe6ef] bg-white p-3 text-[13px] text-[#17365d] outline-none focus:border-[#3288e8] focus:ring-[3px] focus:ring-[#3288e8]/10"
                    >
                        <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>
                            Laundry Staff
                        </option>

                        <option value="delivery" {{ old('role') === 'delivery' ? 'selected' : '' }}>
                            Delivery Personnel
                        </option>
                    </select>

                    <small class="text-[11px] leading-[1.5] text-[#718096]">
                        Laundry Staff handles laundry processing.
                        Delivery Personnel handles assigned pickup and delivery orders.
                    </small>

                    @error('role')
                        <span class="text-[11px] text-red-600">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-7 flex justify-end gap-[10px] border-t border-[#edf1f6] pt-5 max-[600px]:flex-col-reverse">

                <a
                    href="{{ route('admin.staff.index') }}"
                    class="cursor-pointer rounded-md border border-[#dfe6ef] bg-white px-[17px] py-[11px] text-center text-[12px] font-semibold text-[#64748b] no-underline hover:bg-[#f8fafc]"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="cursor-pointer rounded-md border border-[#1673d1] bg-[#1673d1] px-[17px] py-[11px] text-[12px] font-semibold text-white hover:border-[#125fae] hover:bg-[#125fae]"
                >
                    <i class="fa-solid fa-user-plus"></i>
                    Create Account
                </button>

            </div>
        </form>

    </div>
</div>
@endsection
