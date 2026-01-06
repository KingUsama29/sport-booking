@extends('layouts.app_bootstrap')
@section('title', 'Admin - Tambah Lapangan')

@section('content')
    <h4 class="mb-3">Tambah Lapangan</h4>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.fields.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Lapangan</label>
                    <input class="form-control" name="name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Olahraga</label>
                    <input class="form-control" name="sport_type" placeholder="Futsal / Badminton" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga per Jam</label>
                    <input type="number" class="form-control" name="price_per_hour" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input class="form-control" name="location" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.fields.index') }}">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
