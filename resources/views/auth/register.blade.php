
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#171719] px-5 py-8">

        <!-- Laundry Branding -->
        <div class="mb-6 flex flex-col items-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/5">
                <svg class="h-10 w-10 text-cyan-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.4"
                        d="M5 3h14v18H5zM5 7h14M9 5h.01M12 5h.01M8 11h8v6H8zM10 13h4m-4 2h4" />
                </svg>
            </div>

            <h1 class="mt-3 text-xl font-bold tracking-wide text-white">
                Lavanderie
            </h1>

            <p class="mt-1 text-[10px] tracking-[2px] text-gray-500">
                LAUNDRY MANAGEMENT SYSTEM
            </p>
        </div>

        <!-- Register Card -->
        <div class="w-full max-w-[380px] rounded-lg border border-white/5 bg-[#2d2d30] p-6 shadow-xl sm:p-7">

            <h2 class="text-base font-semibold text-white">
                Create an account
            </h2>

            <p class="mt-1 text-xs text-gray-400">
                Join FreshWash and manage your laundry business.
            </p>

            <form method="POST" action="{{ route('register') }}" class="mt-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="mb-2 block text-xs font-medium text-gray-200">
                        Full name
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your full name"
                        class="w-full rounded-md border border-white/5 bg-[#454548] px-3 py-2.5 text-sm text-white placeholder-gray-400 outline-none transition focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                    >

                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="mb-2 block text-xs font-medium text-gray-200">
                        Email address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
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
                        autocomplete="new-password"
                        placeholder="Create a password"
                        class="w-full rounded-md border border-white/5 bg-[#454548] px-3 py-2.5 text-sm text-white placeholder-gray-400 outline-none transition focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                    >

                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="mb-2 block text-xs font-medium text-gray-200">
                        Confirm password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                        class="w-full rounded-md border border-white/5 bg-[#454548] px-3 py-2.5 text-sm text-white placeholder-gray-400 outline-none transition focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                    >

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="mt-6 w-full rounded-md bg-cyan-500 px-4 py-2.5 text-sm font-semibold text-[#102024] transition hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-[#2d2d30]"
                >
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-5 text-center text-xs text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="font-semibold text-cyan-400 transition hover:text-cyan-300">
                    Sign in
                </a>
            </p>
        </div>

        </p>
    </div>
</x-guest-layout>
