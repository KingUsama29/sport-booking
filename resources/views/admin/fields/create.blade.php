@extends('layouts.app_bootstrap')
@section('title', 'Admin - Tambah Lapangan')

@section('content')
    <h4 class="mb-3 naisya-title">Tambah Lapangan</h4>

    <div class="naisya-glass p-4">
        <form method="POST" action="{{ route('admin.fields.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lapangan</label>
                    <input class="form-control" name="name" required value="{{ old('name') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Olahraga</label>
                    <input class="form-control" name="sport_type" required value="{{ old('sport_type') }}"
                        placeholder="Futsal / Badminton">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Harga per Jam</label>
                    <input type="number" class="form-control" name="price_per_hour" min="0" required
                        value="{{ old('price_per_hour') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lokasi</label>
                    <input class="form-control" name="location" required value="{{ old('location') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Gambar Lapangan (opsional)</label>
                    <input type="file" name="image" class="form-control">
                    <div class="form-text text-muted">JPG/PNG/WEBP, max 2MB</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn naisya-btn naisya-btn-primary" type="submit">
                    <i class="bi bi-save2 me-2"></i>Simpan
                </button>
                <a class="btn naisya-btn naisya-btn-soft" href="{{ route('admin.fields.index') }}">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
