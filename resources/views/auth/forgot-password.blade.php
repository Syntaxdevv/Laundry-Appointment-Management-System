
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#171719] px-5 py-10">

        <!-- FreshWash Branding -->
        <div class="mb-7 flex flex-col items-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/5">
                <svg class="h-10 w-10 text-cyan-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.4"
                        d="M5 3h14v18H5zM5 7h14M9 5h.01M12 5h.01M8 11h8v6H8zM10 13h4m-4 2h4" />
                </svg>
            </div>

            <h1 class="mt-3 text-xl font-bold tracking-wide text-white">
                Linis<span class="text-cyan-400">Laba</span>
            </h1>

            <p class="mt-1 text-[10px] tracking-[2px] text-gray-500">
                LAUNDRY MANAGEMENT SYSTEM
            </p>
        </div>

        <!-- Forgot Password Card -->
        <div class="w-full max-w-[380px] rounded-lg border border-white/5 bg-[#2d2d30] p-6 shadow-xl sm:p-7">

            <!-- Icon -->
            <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-400/10">
                <svg class="h-6 w-6 text-cyan-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm2-9V7a4 4 0 118 0v3" />
                </svg>
            </div>

            <h2 class="text-base font-semibold text-white">
                Forgot your password?
            </h2>

            <p class="mt-2 text-xs leading-5 text-gray-400">
                No worries! Enter the email address associated with your account, and we'll send you a password reset link.
            </p>

            <!-- Session Status -->
            <x-auth-session-status
                class="mt-4"
                :status="session('status')"
            />

            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.email') }}" class="mt-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-xs font-medium text-gray-200">
                        Email address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your registered email"
                        class="w-full rounded-md border border-white/5 bg-[#454548] px-3 py-2.5 text-sm text-white placeholder-gray-400 outline-none transition focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                    >

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-1"
                    />
                </div>

                <button
                    type="submit"
                    class="mt-6 w-full rounded-md bg-cyan-500 px-4 py-2.5 text-sm font-semibold text-[#102024] transition hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-[#2d2d30]"
                >
                    Send Password Reset Link
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-5 text-center">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-xs font-medium text-cyan-400 transition hover:text-cyan-300">
                    <svg class="h-4 w-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M10 19l-7-7 7-7M3 12h18" />
                    </svg>
                    Back to Sign In
                </a>
            </div>
        </div>

        <p class="mt-5 text-center text-[11px] text-gray-600">
            © {{ date('Y') }} FreshWash. All rights reserved.
        </p>
    </div>
</x-guest-layout>
