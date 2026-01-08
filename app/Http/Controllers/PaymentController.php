<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout(Booking $booking)
    {
        // hanya user pemilik booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // hanya bisa bayar kalau sudah approved_unpaid
        if ($booking->status !== 'approved_unpaid') {
            return redirect()->route('bookings.index')
                ->with('error', 'Booking belum disetujui admin atau sudah dibayar.');
        }

        // kalau sudah ada payment pending, pakai itu
        $payment = $booking->payment;

        if (!$payment) {
            $orderId = 'BOOK-' . $booking->id . '-' . now()->format('YmdHis');

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'amount' => (int) $booking->total_price,
                'status' => 'pending',
            ]);
        }

        // generate snap token kalau belum ada
        if (!$payment->snap_token) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $payment->order_id,
                    'gross_amount' => $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => $booking->user->name,
                    'email' => $booking->user->email,
                ],
                'item_details' => [
                    [
                        'id' => 'FIELD-' . $booking->field_id,
                        'price' => (int) $booking->total_price,
                        'quantity' => 1,
                        'name' => 'Booking ' . $booking->field->name . ' (' . $booking->booking_date . ' ' . $booking->start_time . ')',
                    ],
                ],

                // ✅ HANYA VA / BANK TRANSFER (QRIS, GOPAY, dll hilang)
                'enabled_payments' => [
                    'bank_transfer',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'mandiri_va',
                    'permata_va',
                ],

                'callbacks' => [
                    'finish' => route('payments.finish'),
                ],
            ];


            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $payment->update([
                'snap_token' => $snapToken,
            ]);
        }

        return view('payments.checkout', [
            'booking' => $booking,
            'payment' => $payment,
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => (bool) config('midtrans.is_production'),
        ]);
    }

    public function finish()
    {
        // Midtrans redirect ke sini setelah pembayaran
        // Status final tetap akan dipastikan lewat webhook notification (lebih valid)
        return redirect()->route('bookings.index')
            ->with('success', 'Jika pembayaran berhasil, status akan otomatis terupdate.');
    }
}
