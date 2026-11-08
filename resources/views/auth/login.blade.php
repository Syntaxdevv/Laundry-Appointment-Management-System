
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#171719] px-5 py-10">

        <!-- Laundry Logo -->
        <div class="mb-7 flex flex-col items-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/5">
                <svg class="h-12 w-12 text-cyan-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.4"
                        d="M5 3h14v18H5zM5 7h14M9 5h.01M12 5h.01M8 11h8v6H8zM10 13h4m-4 2h4" />
                </svg>
            </div>

            <h1 class="mt-3 text-xl font-bold tracking-wide text-white">
             Lavanderie
            </h1>
            <p class="mt-1 text-xs text-gray-500">
                LAUNDRY MANAGEMENT SYSTEM
            </p>
        </div>

        <!-- Login Card -->
        <div class="w-full max-w-[380px] rounded-lg border border-white/5 bg-[#2d2d30] p-6 shadow-xl sm:p-7">

            <h2 class="text-base font-semibold text-white">
                Welcome back!
            </h2>
            <p class="mt-1 text-xs text-gray-400">
                Sign in to manage your laundry business.
            </p>

            <x-auth-session-status
                class="mt-4"
                :status="session('status')"
            />

            <form method="POST" action="{{ route('login') }}" class="mt-5">
                @csrf

                <!-- Email -->
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
                        placeholder="Enter your email address"
                        class="w-full rounded-md border border-white/5 bg-[#454548] px-3 py-2.5 text-sm text-white placeholder-gray-400 outline-none transition focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                    >

                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="mb-2 block text-xs font-medium text-gray-200">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="w-full rounded-md border border-white/5 bg-[#454548] px-3 py-2.5 text-sm text-white placeholder-gray-400 outline-none transition focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                    >

                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Remember Me -->
                <div class="mt-4 flex items-center justify-between gap-2">
                    <label for="remember_me" class="flex items-center gap-2 text-xs text-gray-300">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-500 bg-[#454548] text-cyan-400 focus:ring-cyan-400"
                        >
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-cyan-400 transition hover:text-cyan-300">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Sign In -->
                <button
                    type="submit"
                    class="mt-6 w-full rounded-md bg-cyan-500 px-4 py-2.5 text-sm font-semibold text-[#102024] transition hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-[#2d2d30]"
                >
                    Sign In
                </button>
            </form>

            <!-- Register -->
            @if (Route::has('register'))
                <p class="mt-5 text-center text-xs text-gray-400">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="font-semibold text-cyan-400 hover:text-cyan-300">
                        Register now
                    </a>
                </p>
            @endif
        </div>
        </p>
    </div>
</x-guest-layout>
