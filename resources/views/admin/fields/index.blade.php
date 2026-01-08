@extends('layouts.app_bootstrap')
@section('title', 'Admin - Kelola Lapangan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 naisya-title">Kelola Lapangan</h4>
            <div class="text-white-50 small">Tambah, edit, dan hapus data lapangan.</div>
        </div>
        <a href="{{ route('admin.fields.create') }}" class="btn naisya-btn naisya-btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah
        </a>
    </div>

    <div class="naisya-glass p-3">
        <div class="table-responsive">
            <table class="table naisya-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Harga/Jam</th>
                        <th>Lokasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fields as $f)
                        <tr class="naisya-fade">
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $f->name }}</td>
                            <td><span class="naisya-badge">{{ $f->sport_type }}</span></td>
                            <td>Rp {{ number_format($f->price_per_hour) }}</td>
                            <td>{{ $f->location }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-success rounded-pill px-3"
                                    href="{{ route('admin.fields.edit', $f) }}">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>

                                <form class="d-inline" method="POST" action="{{ route('admin.fields.destroy', $f) }}"
                                    onsubmit="return confirm('Hapus lapangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" type="submit">
                                        <i class="bi bi-trash3 me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-white-50 py-4">Belum ada data lapangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $fields->links() }}</div>
@endsection
