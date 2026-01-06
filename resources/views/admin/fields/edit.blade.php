@extends('layouts.app_bootstrap')
@section('title', 'Admin - Edit Lapangan')

@section('content')
    <h4 class="mb-3">Edit Lapangan</h4>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.fields.update', $field) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Lapangan</label>
                    <input class="form-control" name="name" value="{{ $field->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Olahraga</label>
                    <input class="form-control" name="sport_type" value="{{ $field->sport_type }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga per Jam</label>
                    <input type="number" class="form-control" name="price_per_hour" min="0"
                        value="{{ $field->price_per_hour }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input class="form-control" name="location" value="{{ $field->location }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea class="form-control" name="description" rows="3">{{ $field->description }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.fields.index') }}">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
