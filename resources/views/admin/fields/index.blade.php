@extends('layouts.app_bootstrap')
@section('title', 'Admin - Kelola Lapangan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Kelola Lapangan</h4>
        <a href="{{ route('admin.fields.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Harga/Jam</th>
                        <th>Lokasi</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fields as $f)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $f->name }}</td>
                            <td>{{ $f->sport_type }}</td>
                            <td>Rp {{ number_format($f->price_per_hour) }}</td>
                            <td>{{ $f->location }}</td>
                            <td class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('admin.fields.edit', $f) }}">Edit</a>

                                <form method="POST" action="{{ route('admin.fields.destroy', $f) }}"
                                    onsubmit="return confirm('Hapus lapangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data lapangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $fields->links() }}</div>
@endsection
