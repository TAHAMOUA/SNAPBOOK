<x-guest-layout>
    <div class="auth-split max-sm:grid-cols-1">

        <div class="auth-left ocean hidden sm:flex">
            <div class="al-ap"></div>
            <div class="al-logo">Snap<span>Book</span></div>
            <div>
                <div class="al-title">Welcome<br><em>back.</em></div>
                <p class="al-sub">Book world-class photographers for every moment that matters.</p>
            </div>
            <div class="al-bullets">
                <div class="al-bl"><div class="al-dot"></div>840+ professional photographers</div>
                <div class="al-bl"><div class="al-dot"></div>Instant availability calendar</div>
                <div class="al-bl"><div class="al-dot"></div>Secure online booking</div>
                <div class="al-bl"><div class="al-dot"></div>Verified reviews</div>
            </div>
        </div>

        <div class="auth-right">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="form-title">Log in</div>
            <p class="form-sub">Access your account to manage bookings</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="fld">
                    <label class="lbl" for="email">Email address</label>
                    <input
                        class="inp"
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required
                        autofocus
                        autocomplete="username"
                    >
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="fld">
                    <label class="lbl" for="password">Password</label>
                    <input
                        class="inp"
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Your password"
                        required
                        autocomplete="current-password"
                    >
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="block mt-4 mb-1">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-[var(--bd)] bg-[#0d1018] text-[var(--ember)] focus:ring-[var(--ember)]"
                            name="remember"
                        >
                        <span class="ms-2 text-sm text-[var(--mist)]">{{ __('Remember me') }}</span>
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="forgot" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button type="submit" class="btn-submit">
                    {{ __('Log in') }}
                </button>

                <div class="switch-lnk">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}">{{ __('Sign up') }}</a>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>