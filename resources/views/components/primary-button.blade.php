<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[var(--ember)] border border-transparent rounded-[3px] font-semibold text-[11px] tracking-[.04em] text-white hover:bg-[rgba(219,82,39,.85)] focus:outline-none focus:ring-2 focus:ring-[var(--ember)] focus:ring-offset-2 focus:ring-offset-[#0A0C12] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
