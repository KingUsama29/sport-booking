@extends('layouts.app_bootstrap')
@section('title', 'Checkout - Pembayaran')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="naisya-glass p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h4 class="mb-1 naisya-title">Pembayaran Booking</h4>
                        <div class="text-muted small">
                            Lapangan: <span class="fw-semibold">{{ $booking->field->name }}</span> •
                            Tanggal: <span class="fw-semibold">{{ $booking->booking_date }}</span> •
                            Jam: <span class="fw-semibold">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                        </div>
                    </div>
                    <span class="naisya-badge">
                        <i class="bi bi-receipt me-1"></i>{{ $payment->order_id }}
                    </span>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">Total Bayar</div>
                    <div class="fs-4 fw-bold">Rp {{ number_format($payment->amount) }}</div>
                </div>

                <div class="alert alert-info mt-3 small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Setelah pembayaran sukses, status booking akan otomatis menjadi <b>paid</b>.
                </div>

                <div class="d-grid mt-4">
                    <button id="pay-button" class="btn naisya-btn naisya-btn-primary">
                        <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                    </button>
                </div>

                <div class="text-center mt-3 small text-muted">
                    Dengan klik bayar, kamu akan diarahkan ke halaman pembayaran Midtrans (Sandbox/Production).
                </div>
            </div>
        </div>
    </div>

    @if ($isProduction)
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @endif

    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            window.snap.pay(@json($payment->snap_token), {
                onSuccess: function(result) {
                    window.location.href = @json(route('payments.finish'));
                },
                onPending: function(result) {
                    window.location.href = @json(route('payments.finish'));
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Coba lagi.');
                },
                onClose: function() {
                    /* user tutup popup */
                }
            });
        });
    </script>
@endsection
