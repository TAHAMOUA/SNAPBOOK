<x-guest-layout>
    <div class="auth-split max-sm:grid-cols-1">

        <div class="auth-left slate hidden sm:flex">
            <div class="al-ap"></div>
            <div class="al-logo">Snap<span>Book</span></div>
            <div>
                <div class="al-title">Join the<br><em>platform.</em></div>
                <p class="al-sub">Create your account as a client or photographer and start in minutes.</p>
            </div>
            <div class="al-bullets">
                <div class="al-bl"><div class="al-dot"></div>Free to join as a client</div>
                <div class="al-bl"><div class="al-dot"></div>Photographers reviewed within 24h</div>
                <div class="al-bl"><div class="al-dot"></div>Secure bookings</div>
                <div class="al-bl"><div class="al-dot"></div>Verified reviews</div>
            </div>
        </div>

        <div class="auth-right">
            <div class="form-title">Create account</div>
            <p class="form-sub">Create your SnapBook account to get started</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="fld-row">
                    <div>
                        <label class="lbl" for="first_name">First name</label>
                        <input
                            class="inp"
                            id="first_name"
                            type="text"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="Amina"
                            required
                            autofocus
                            autocomplete="given-name"
                        >
                        <x-input-error :messages="$errors->get('first_name')" />
                    </div>
                    <div>
                        <label class="lbl" for="last_name">Last name</label>
                        <input
                            class="inp"
                            id="last_name"
                            type="text"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="Khaldi"
                            required
                            autocomplete="family-name"
                        >
                        <x-input-error :messages="$errors->get('last_name')" />
                    </div>
                </div>

                <div class="fld">
                    <label class="lbl" for="phone">Phone</label>
                    <input
                        class="inp"
                        id="phone"
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="+212 600 000 000"
                        autocomplete="tel"
                    >
                    <x-input-error :messages="$errors->get('phone')" />
                </div>

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
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
                    >
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="fld">
                    <label class="lbl" for="password_confirmation">Confirm password</label>
                    <input
                        class="inp"
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Repeat password"
                        required
                        autocomplete="new-password"
                    >
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <button type="submit" class="btn-submit">
                    {{ __('Create account') }}
                </button>

                <div class="switch-lnk">
                    {{ __("Already have an account?") }}
                    <a href="{{ route('login') }}">{{ __('Log in') }}</a>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>