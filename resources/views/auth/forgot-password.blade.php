<x-guest-layout>
    <div class="auth-right w-full max-w-md" style="border:1px solid var(--bd);border-radius:6px;">
        <div class="form-title">Forgot your password?</div>
        <p class="form-sub">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="fld">
                <label class="lbl" for="email">Email address</label>
                <input
                    class="inp"
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="you@example.com"
                    required
                    autofocus
                >
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <button type="submit" class="btn-submit">
                {{ __('Email Password Reset Link') }}
            </button>

            <div class="switch-lnk">
                <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
            </div>
        </form>
    </div>
</x-guest-layout>