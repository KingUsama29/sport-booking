@extends('layouts.app_bootstrap')

@section('title', 'Buat Booking')

@section('content')
    <h4 class="mb-3">Buat Booking</h4>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih Lapangan</label>
                    <select name="field_id" class="form-select" required>
                        <option value="">-- pilih --</option>
                        @foreach ($fields as $f)
                            <option value="{{ $f->id }}" @selected((string) $selectedFieldId === (string) $f->id)>
                                {{ $f->name }} ({{ $f->sport_type }}) - Rp {{ number_format($f->price_per_hour) }}/jam
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="booking_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mulai</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Selesai</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Catatan (opsional)</label>
                    <input type="text" name="notes" class="form-control" placeholder="contoh: pakai bola dari tempat">
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('bookings.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
