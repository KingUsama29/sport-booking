@extends('layouts.app_bootstrap')

@section('title', 'Buat Booking')

@push('styles')
    <style>
        .slot-btn {
            border: 1px solid rgba(99, 102, 241, .25);
            background: #fff;
            border-radius: 14px;
            padding: 10px 12px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            transition: .15s ease;
        }

        .slot-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, .06);
        }

        .slot-btn.active {
            background: rgba(99, 102, 241, .10);
            border-color: rgba(99, 102, 241, .5);
        }

        .slot-btn.disabled {
            opacity: .45;
            pointer-events: none;
            text-decoration: line-through;
        }

        .slot-pill {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(15, 23, 42, .06);
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">Buat Booking</h3>
                            <div class="text-muted">Pilih lapangan, tanggal, lalu pilih slot jam (per 1 jam).</div>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lapangan</label>
                                <select name="field_id" id="field_id" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Lapangan --</option>
                                    @foreach ($fields as $f)
                                        <option value="{{ $f->id }}" @selected(old('field_id') == $f->id)>
                                            {{ $f->name }} (Rp
                                            {{ number_format($f->price_per_hour ?? ($f->price ?? 0), 0, ',', '.') }}/jam)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal</label>
                                <input type="date" name="booking_date" id="booking_date" class="form-control rounded-3"
                                    required min="{{ now()->toDateString() }}"
                                    value="{{ old('booking_date', now()->toDateString()) }}">
                            </div>
                        </div>

                        <hr class="my-4">

                        <label class="form-label fw-semibold mb-2">Pilih Slot Jam</label>
                        <div class="text-muted small mb-3">
                            Slot yang sudah dibooking akan otomatis nonaktif.
                        </div>

                        <input type="hidden" name="start_hour" id="start_hour" value="{{ old('start_hour') }}">

                        <div class="row g-2" id="slotContainer">
                            @foreach ($hours as $h)
                                @php
                                    $label =
                                        str_pad($h, 2, '0', STR_PAD_LEFT) .
                                        ':00 - ' .
                                        str_pad($h + 1, 2, '0', STR_PAD_LEFT) .
                                        ':00';
                                @endphp
                                <div class="col-6 col-md-4">
                                    <button type="button" class="slot-btn w-100" data-hour="{{ $h }}">
                                        <span>{{ $label }}</span>
                                        <span class="slot-pill">Tersedia</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div class="small text-muted">
                                <i class="bi bi-shield-check"></i> Sistem otomatis menolak slot yang bentrok.
                            </div>
                            <button class="btn btn-primary rounded-pill px-4" type="submit">
                                <i class="bi bi-check2-circle"></i> Simpan Booking
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const fieldEl = document.getElementById('field_id');
        const dateEl = document.getElementById('booking_date');
        const startHourInput = document.getElementById('start_hour');
        const slotButtons = document.querySelectorAll('.slot-btn');

        function resetSlots() {
            slotButtons.forEach(btn => {
                btn.classList.remove('active', 'disabled');
                btn.querySelector('.slot-pill').textContent = 'Tersedia';
            });
        }

        function applyBooked(bookedHours) {
            slotButtons.forEach(btn => {
                const hour = parseInt(btn.dataset.hour, 10);
                if (bookedHours.includes(hour)) {
                    btn.classList.add('disabled');
                    btn.querySelector('.slot-pill').textContent = 'Penuh';
                }
            });
        }

        function markSelected(hour) {
            slotButtons.forEach(btn => btn.classList.remove('active'));
            const btn = [...slotButtons].find(b => parseInt(b.dataset.hour, 10) === hour);
            if (btn && !btn.classList.contains('disabled')) {
                btn.classList.add('active');
                startHourInput.value = hour;
            }
        }

        async function loadAvailability() {
            const fieldId = fieldEl.value;
            const date = dateEl.value;

            resetSlots();
            startHourInput.value = '';

            if (!fieldId || !date) return;

            const url =
                `{{ route('bookings.availability') }}?field_id=${encodeURIComponent(fieldId)}&booking_date=${encodeURIComponent(date)}`;
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();

            applyBooked(data.booked_hours || []);

            // kalau sebelumnya user udah pilih (old value), coba reselect
            const oldHour = parseInt(`{{ old('start_hour') ?? 'NaN' }}`, 10);
            if (!Number.isNaN(oldHour)) markSelected(oldHour);
        }

        slotButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const hour = parseInt(btn.dataset.hour, 10);
                if (btn.classList.contains('disabled')) return;
                markSelected(hour);
            });
        });

        fieldEl.addEventListener('change', loadAvailability);
        dateEl.addEventListener('change', loadAvailability);

        // init
        loadAvailability();
    </script>
@endpush
