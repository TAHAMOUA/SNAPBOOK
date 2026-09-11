<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-transparent border border-[var(--bd)] rounded-[3px] font-semibold text-[11px] tracking-[.04em] text-[var(--mist)] hover:text-[var(--white)] hover:border-[var(--mist)] focus:outline-none focus:ring-2 focus:ring-[var(--ember)] focus:ring-offset-2 focus:ring-offset-[#0A0C12] disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
