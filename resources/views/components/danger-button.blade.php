<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[rgba(163,48,48,.12)] border border-[rgba(163,48,48,.3)] rounded-[3px] font-semibold text-[11px] tracking-[.04em] text-[#c97070] hover:bg-[rgba(163,48,48,.24)] focus:outline-none focus:ring-2 focus:ring-[#c97070] focus:ring-offset-2 focus:ring-offset-[#0A0C12] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
