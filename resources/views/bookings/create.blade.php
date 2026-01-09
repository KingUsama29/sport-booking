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
            width: 100%;
        }

        .slot-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, .06);
        }

        .slot-btn.active {
            background: rgba(99, 102, 241, .12);
            border-color: rgba(99, 102, 241, .55);
        }

        /* Range highlight (durasi > 1) */
        .slot-btn.slot-range {
            background: rgba(99, 102, 241, .08);
            border-color: rgba(99, 102, 241, .35);
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
            white-space: nowrap;
        }

        .slot-pill.penuh {
            background: rgba(239, 68, 68, .12);
            color: #b91c1c;
        }

        .slot-pill.tersedia {
            background: rgba(16, 185, 129, .12);
            color: #047857;
        }

        .slot-pill.dipilih {
            background: rgba(99, 102, 241, .14);
            color: #3730a3;
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
                            <div class="text-muted">Pilih lapangan, tanggal, lalu pilih jam mulai & durasi.</div>
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

                        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-2">
                            <div>
                                <label class="form-label fw-semibold mb-1">Pilih Jam Mulai</label>
                                <div class="text-muted small">
                                    Slot yang sudah dibooking akan otomatis nonaktif.
                                </div>
                            </div>

                            <div style="min-width: 200px">
                                <label class="form-label fw-semibold mb-1">Durasi</label>
                                <select name="duration" id="duration" class="form-select rounded-3">
                                    @for ($d = 1; $d <= 6; $d++)
                                        <option value="{{ $d }}" @selected(old('duration', 1) == $d)>{{ $d }}
                                            jam</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- input hidden untuk controller --}}
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
                                    <button type="button" class="slot-btn" data-hour="{{ $h }}">
                                        <span>{{ $label }}</span>
                                        <span class="slot-pill tersedia">Tersedia</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div id="slotError" class="alert alert-danger mt-3 d-none"></div>

                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div class="small text-muted">
                                <i class="bi bi-shield-check"></i> Sistem otomatis menolak slot yang bentrok.
                            </div>
                            <button class="btn btn-primary rounded-pill px-4" type="submit" id="submitBtn">
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
        const durationEl = document.getElementById('duration');

        const startHourInput = document.getElementById('start_hour');
        const slotButtons = document.querySelectorAll('.slot-btn');

        const errorBox = document.getElementById('slotError');
        const submitBtn = document.getElementById('submitBtn');

        let bookedHours = []; // array jam yg sudah terbooking (misal [9,10,11])
        let selectedStart = startHourInput.value ? parseInt(startHourInput.value, 10) : null;

        function setError(msg) {
            if (!msg) {
                errorBox.classList.add('d-none');
                errorBox.textContent = '';
                submitBtn.disabled = false;
                return;
            }
            errorBox.classList.remove('d-none');
            errorBox.textContent = msg;
            submitBtn.disabled = true;
        }

        function resetSlotsUI() {
            slotButtons.forEach(btn => {
                btn.classList.remove('active', 'disabled', 'slot-range');
                const pill = btn.querySelector('.slot-pill');
                pill.classList.remove('penuh', 'dipilih', 'tersedia');
                pill.classList.add('tersedia');
                pill.textContent = 'Tersedia';
            });
        }

        function applyBookedUI() {
            slotButtons.forEach(btn => {
                const hour = parseInt(btn.dataset.hour, 10);
                if (bookedHours.includes(hour)) {
                    btn.classList.add('disabled');
                    const pill = btn.querySelector('.slot-pill');
                    pill.classList.remove('tersedia');
                    pill.classList.add('penuh');
                    pill.textContent = 'Penuh';
                }
            });
        }

        function isRangeAvailable(start, duration) {
            const end = start + duration;
            // semua jam dalam range harus tidak ada di bookedHours
            for (let h = start; h < end; h++) {
                if (bookedHours.includes(h)) return false;
            }
            return true;
        }

        function renderSelection() {
            setError('');

            if (selectedStart === null || Number.isNaN(selectedStart)) {
                startHourInput.value = '';
                setError('Pilih jam mulai dulu ya.');
                return;
            }

            const duration = parseInt(durationEl.value || '1', 10);
            const end = selectedStart + duration;

            // highlight range
            slotButtons.forEach(btn => {
                const h = parseInt(btn.dataset.hour, 10);
                if (h === selectedStart) btn.classList.add('active');
                if (h >= selectedStart && h < end) btn.classList.add('slot-range');
            });

            // label pill untuk slot yg masuk range (biar jelas dipilih)
            slotButtons.forEach(btn => {
                const h = parseInt(btn.dataset.hour, 10);
                const pill = btn.querySelector('.slot-pill');

                if (btn.classList.contains('disabled')) return;

                if (h >= selectedStart && h < end) {
                    pill.classList.remove('tersedia');
                    pill.classList.add('dipilih');
                    pill.textContent = (h === selectedStart) ? 'Dipilih' : 'Range';
                }
            });

            // validasi range
            if (!isRangeAvailable(selectedStart, duration)) {
                setError(
                    'Rentang jam yang kamu pilih bentrok dengan slot yang sudah penuh. Kurangi durasi atau pilih jam lain.'
                );
                return;
            }

            // ok
            startHourInput.value = selectedStart;
        }

        function markSelected(hour) {
            selectedStart = hour;
            resetSlotsUI();
            applyBookedUI();
            renderSelection();
        }

        async function loadAvailability() {
            const fieldId = fieldEl.value;
            const date = dateEl.value;

            resetSlotsUI();
            selectedStart = null;
            startHourInput.value = '';
            setError('Pilih lapangan & tanggal terlebih dahulu.');

            if (!fieldId || !date) return;

            const url =
                `{{ route('bookings.availability') }}?field_id=${encodeURIComponent(fieldId)}&booking_date=${encodeURIComponent(date)}`;

            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();
            bookedHours = data.booked_hours || [];

            resetSlotsUI();
            applyBookedUI();
            setError('');

            // reselect old value kalau ada
            const oldHour = parseInt(`{{ old('start_hour') ?? 'NaN' }}`, 10);
            if (!Number.isNaN(oldHour)) {
                // kalau jam old kebetulan sudah penuh, kasih pesan
                if (bookedHours.includes(oldHour)) {
                    setError('Jam yang kamu pilih sebelumnya sudah penuh. Pilih jam lain.');
                } else {
                    selectedStart = oldHour;
                    renderSelection();
                }
            } else {
                setError('Pilih jam mulai dulu ya.');
            }
        }

        slotButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('disabled')) return;
                const hour = parseInt(btn.dataset.hour, 10);
                markSelected(hour);
            });
        });

        durationEl.addEventListener('change', () => {
            resetSlotsUI();
            applyBookedUI();
            if (selectedStart !== null) renderSelection();
            else setError('Pilih jam mulai dulu ya.');
        });

        fieldEl.addEventListener('change', loadAvailability);
        dateEl.addEventListener('change', loadAvailability);

        // init
        loadAvailability();
    </script>
@endpush
