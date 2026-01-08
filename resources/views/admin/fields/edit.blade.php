@extends('layouts.app_bootstrap')
@section('title', 'Admin - Edit Lapangan')

@section('content')
    <h4 class="mb-3 naisya-title">Edit Lapangan</h4>

    <div class="naisya-glass p-4">
        <form method="POST" action="{{ route('admin.fields.update', $field) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lapangan</label>
                    <input class="form-control" name="name" required value="{{ old('name', $field->name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Olahraga</label>
                    <input class="form-control" name="sport_type" required
                        value="{{ old('sport_type', $field->sport_type) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Harga per Jam</label>
                    <input type="number" class="form-control" name="price_per_hour" min="0" required
                        value="{{ old('price_per_hour', $field->price_per_hour) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lokasi</label>
                    <input class="form-control" name="location" required value="{{ old('location', $field->location) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $field->description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Gambar Lapangan (opsional)</label>

                    @if ($field->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $field->image) }}" class="rounded" style="max-width: 260px;">
                        </div>
                    @endif

                    <input type="file" name="image" class="form-control">
                    <div class="form-text text-muted">Upload baru untuk mengganti gambar lama.</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn naisya-btn naisya-btn-primary" type="submit">
                    <i class="bi bi-arrow-repeat me-2"></i>Update
                </button>
                <a class="btn naisya-btn naisya-btn-soft" href="{{ route('admin.fields.index') }}">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
