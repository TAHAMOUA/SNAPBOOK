<x-guest-layout>
    <div class="auth-right w-full max-w-md" style="border:1px solid var(--bd);border-radius:6px;">
        <div class="form-title">Reset password</div>
        <p class="form-sub">
            Choose a new password for your account.
        </p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="fld">
                <label class="lbl" for="email">Email address</label>
                <input
                    class="inp"
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
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
                {{ __('Reset Password') }}
            </button>

            <div class="switch-lnk">
                <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
            </div>
        </form>
    </div>
</x-guest-layout>