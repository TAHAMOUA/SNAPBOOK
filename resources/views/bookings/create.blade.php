<x-app-layout>
    <div class="book-wrap">

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm text-[#5dbf7e]">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="book-title">Book a Session</h1>
        <p class="book-sub">
            Secure your session with
            {{ $service->photographerProfile->user->first_name }} {{ $service->photographerProfile->user->last_name }}.
        </p>

        <div class="steps">
            <div class="sd done"><i class="ti ti-check" aria-hidden="true" style="font-size:12px;"></i></div>
            <div class="sl2 done">Service</div>
            <div class="sline done"></div>
            <div class="sd act">2</div>
            <div class="sl2 act">Details</div>
            <div class="sline"></div>
            <div class="sd todo">3</div>
            <div class="sl2 todo">Confirm</div>
        </div>

        @if ($availabilities->count())
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf

                <input type="hidden" name="id_service" value="{{ $service->id_service }}">

                <div class="fld">
                    <label class="lbl">Selected service</label>
                    <select class="sel" disabled>
                        <option>{{ $service->title }} — {{ number_format($service->price, 2) }} MAD</option>
                    </select>
                </div>

                <div class="fld">
                    <label class="lbl" for="id_availability">Available slot</label>
                    <select
                        id="id_availability"
                        name="id_availability"
                        class="sel"
                        required
                        autofocus
                    >
                        <option value="">Select a time slot</option>
                        @foreach ($availabilities->groupBy(fn ($slot) => $slot->available_date->format('Y-m-d')) as $date => $slots)
                            <optgroup label="{{ \Illuminate\Support\Carbon::parse($date)->format('j F Y') }}">
                                @foreach ($slots as $slot)
                                    <option
                                        value="{{ $slot->id_availability }}"
                                        {{ old('id_availability') === $slot->id_availability ? 'selected' : '' }}
                                    >
                                        {{ $slot->start_time }} - {{ $slot->end_time }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('id_availability')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="fld">
                    <label class="lbl" for="event_address">Event address</label>
                    <input
                        id="event_address"
                        class="inp"
                        type="text"
                        name="event_address"
                        value="{{ old('event_address') }}"
                        maxlength="255"
                        placeholder="Venue name or address"
                        required
                    >
                    @error('event_address')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sum-box">
                    <div class="sum-row"><span class="sum-lbl">Service</span><span>{{ $service->title }}</span></div>
                    <div class="sum-row"><span class="sum-lbl">Photographer</span><span>{{ $service->photographerProfile->user->first_name }} {{ $service->photographerProfile->user->last_name }}</span></div>
                    <div class="sum-row"><span class="sum-lbl">Date</span><span id="sum-date">—</span></div>
                    @if ($service->duration)
                        <div class="sum-row"><span class="sum-lbl">Duration</span><span>{{ $service->duration }} minutes</span></div>
                    @endif
                    <div class="sum-row"><span class="sum-lbl">Total</span><span class="sum-total">{{ number_format($service->price, 2) }} MAD</span></div>
                </div>

                <button type="submit" class="btn-sub">Confirm and send request</button>

                <p style="font-size:13px;color:var(--mist);margin-top:.9rem;text-align:center;">
                    {{ $service->photographerProfile->user->first_name }} will confirm within 24 hours.
                    Check your bookings in your dashboard.
                </p>

                <div class="mt-6 text-center">
                    <a href="{{ route('services.show', $service->id_service) }}" class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]">
                        Back to service
                    </a>
                </div>
            </form>
        @else
            <div class="border border-[var(--bd)] bg-[rgba(63,58,66,.2)] rounded-md p-10 text-center">
                <p class="font-medium text-[var(--white)] mb-4">
                    This photographer currently has no available time slots.
                </p>
                <a href="{{ route('services.show', $service->id_service) }}" class="btn-mini btn-mg">
                    Back to Service
                </a>
            </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var select = document.getElementById('id_availability');
            var dateRow = document.getElementById('sum-date');

            if (select && dateRow) {
                var sync = function () {
                    var opt = select.options[select.selectedIndex];
                    dateRow.textContent = opt && opt.value && opt.parentElement.label
                        ? opt.parentElement.label + ' · ' + opt.textContent
                        : '—';
                };

                select.addEventListener('change', sync);
                sync();
            }
        });
    </script>
</x-app-layout>