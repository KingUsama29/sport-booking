<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('field', 'payment')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $fields = Field::orderBy('name')->get();
        $selectedFieldId = $request->query('field_id');

        return view('bookings.create', compact('fields', 'selectedFieldId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'notes' => 'nullable|string|max:255',
        ]);

        $field = Field::findOrFail($validated['field_id']);

        // Hitung total harga (jam) sederhana
        $start = strtotime($validated['start_time']);
        $end = strtotime($validated['end_time']);
        $hours = max(1, (int) ceil(($end - $start) / 3600));

        $total = $hours * (int) $field->price_per_hour;

        Booking::create([
            'user_id' => auth()->id(),
            'field_id' => $field->id,
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'notes' => $validated['notes'] ?? null,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat (status: pending).');
    }
}
