<x-guest-layout>
    <div class="auth-right w-full max-w-md" style="border:1px solid var(--bd);border-radius:6px;">
        <div class="form-title">Verify your email</div>
        <p class="form-sub">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-[#5dbf7e]">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <button type="submit" class="btn-submit" style="margin-bottom:0;width:auto;">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-[var(--mist)] hover:text-[var(--ember)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--ember)]">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>