<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'field'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, \App\Models\Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:approved_unpaid,rejected'
        ]);

        // kalau sudah paid, jangan boleh diubah
        if ($booking->status === 'paid') {
            return back()->with('error', 'Booking sudah dibayar, status tidak bisa diubah.');
        }

        $booking->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

}
