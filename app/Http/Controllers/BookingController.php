<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('field')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        // ✅ Admin tidak boleh booking
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Admin tidak bisa melakukan booking. Gunakan akun user biasa.');
        }

        $fields = Field::orderBy('name')->get();

        $open = config('booking.open_hour', 8);
        $close = config('booking.close_hour', 22);
        $hours = range($open, $close - 1);

        return view('bookings.create', compact('fields', 'hours'));
    }

    /**
     * ✅ Endpoint untuk AJAX: ambil jam yang sudah dibooking
     * return: { booked_hours: [8,9,10] }
     */
    public function availability(Request $request)
    {
        $data = $request->validate([
            'field_id' => ['required', 'exists:fields,id'],
            'booking_date' => ['required', 'date'],
        ]);

        $bookedHours = Booking::where('field_id', $data['field_id'])
            ->where('booking_date', $data['booking_date'])
            // status yang dianggap "mengunci slot"
            ->whereIn('status', ['pending', 'approved_unpaid', 'approved_paid', 'approved'])
            ->pluck('start_hour')
            ->map(fn ($h) => (int) $h)
            ->values();

        return response()->json([
            'booked_hours' => $bookedHours,
        ]);
    }

    public function store(Request $request)
    {
        // ✅ Admin tidak boleh booking
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Admin tidak bisa melakukan booking.');
        }

        $open = config('booking.open_hour', 8);
        $close = config('booking.close_hour', 22);

        $data = $request->validate([
            'field_id' => ['required', 'exists:fields,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_hour' => ['required', 'integer', "min:$open", "max:" . ($close - 1)],
        ]);

        // ✅ Cek tabrakan (double-safety: selain unique index)
        $exists = Booking::where('field_id', $data['field_id'])
            ->where('booking_date', $data['booking_date'])
            ->where('start_hour', $data['start_hour'])
            ->whereIn('status', ['pending', 'approved_unpaid', 'approved_paid', 'approved'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Slot jam tersebut sudah dibooking. Silakan pilih jam lain.');
        }

        $field = Field::findOrFail($data['field_id']);
        $total = (int) ($field->price_per_hour ?? $field->price ?? 0);

        // ✅ status awal: pending (nunggu admin approve)
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'field_id' => $data['field_id'],
            'booking_date' => $data['booking_date'],
            'start_hour' => $data['start_hour'],
            'total' => $total,
            'status' => 'pending',
            'payment_status' => null,
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil dibuat (status: pending). Menunggu persetujuan admin.');
    }
}
