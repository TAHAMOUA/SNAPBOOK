@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#5dbf7e]']) }}>
        {{ $status }}
    </div>
@endif
