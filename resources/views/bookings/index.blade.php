@extends('layouts.app_bootstrap')

@section('title', 'Booking Saya')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Booking Saya</h4>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ Buat Booking</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->field->name }}</td>
                            <td>{{ $b->booking_date }}</td>
                            <td>{{ $b->start_time }} - {{ $b->end_time }}</td>
                            <td>Rp {{ number_format($b->total_price) }}</td>
                            <td>
                                @php
                                    $badge = match ($b->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badge }}">{{ $b->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
@endsection
