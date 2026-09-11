<x-guest-layout>
    <div class="auth-right w-full max-w-md" style="border:1px solid var(--bd);border-radius:6px;">
        <div class="form-title">Confirm password</div>
        <p class="form-sub">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

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

            <button type="submit" class="btn-submit">
                {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-guest-layout>