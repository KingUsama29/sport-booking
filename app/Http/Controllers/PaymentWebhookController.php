<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();

        // Validasi signature (wajib untuk security)
        $serverKey = config('midtrans.server_key');

        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $mySignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        if ($signatureKey !== $mySignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        // Map status midtrans → status payment
        // settlement/capture = sukses
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $payment->status = 'settlement';
            $payment->paid_at = now();

            // update booking jadi PAID
            $booking = $payment->booking;
            if ($booking && $booking->status !== 'paid') {
                $booking->status = 'paid';
                $booking->save();
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
            $payment->status = $transactionStatus;
        } elseif ($transactionStatus === 'expire') {
            $payment->status = 'expire';
        } else {
            $payment->status = 'pending';
        }

        $payment->payment_type = $paymentType;
        $payment->transaction_id = $transactionId;
        $payment->raw_response = $payload;
        $payment->save();

        return response()->json(['message' => 'OK']);
    }
}
