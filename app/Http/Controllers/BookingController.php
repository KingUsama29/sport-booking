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

    public function create(Request $request)
    {
        $fields = Field::orderBy('name')->get();
        if ($fields->isEmpty()) {
            return redirect()->route('fields.index')
                ->with('error', 'Belum ada data lapangan. Tambahkan lapangan dulu.');
        }

        $fieldId = $request->get('field_id') ?? $fields->first()->id;
        $date    = $request->get('booking_date') ?? now()->toDateString();

        // Jam operasional
        $openHour  = 8;
        $closeHour = 22; // terakhir 21–22

        $hours = range($openHour, $closeHour - 1);
        $activeStatuses = ['pending', 'approved_unpaid', 'approved_paid', 'paid'];

        $bookings = Booking::where('field_id', $fieldId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', $activeStatuses)
            ->get(['start_hour', 'end_hour']);

        // booked[8] = true artinya slot 08–09 terpakai
        $booked = array_fill($openHour, $closeHour - $openHour, false);

        foreach ($bookings as $b) {
            for ($h = (int)$b->start_hour; $h < (int)$b->end_hour; $h++) {
                if (array_key_exists($h, $booked)) {
                    $booked[$h] = true;
                }
            }
        }

        return view('bookings.create', compact(
            'fields',
            'fieldId',
            'date',
            'openHour',
            'closeHour',
            'hours',
            'booked'
        ));
    }

    public function availability(Request $request)
    {
        $data = $request->validate([
            'field_id'     => ['required', 'exists:fields,id'],
            'booking_date' => ['required', 'date'],
        ]);

        $activeStatuses = ['pending', 'approved_unpaid', 'approved_paid', 'paid'];

        $bookings = Booking::where('field_id', $data['field_id'])
            ->whereDate('booking_date', $data['booking_date'])
            ->whereIn('status', $activeStatuses)
            ->get(['start_hour', 'end_hour']);

        $bookedHours = [];

        foreach ($bookings as $b) {
            for ($h = (int)$b->start_hour; $h < (int)$b->end_hour; $h++) {
                $bookedHours[$h] = true; // pakai key biar auto-unique
            }
        }

        $bookedHours = array_keys($bookedHours);
        sort($bookedHours);

        return response()->json([
            'booked_hours' => $bookedHours,
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            return back()->with('error', 'Admin tidak bisa membuat booking.');
        }

        $data = $request->validate([
            'field_id'     => ['required', 'exists:fields,id'],
            'booking_date' => ['required', 'date'],
            'start_hour'   => ['required', 'integer', 'min:0', 'max:23'],
            'duration'     => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        $start = (int) $data['start_hour'];
        $end   = $start + (int) $data['duration'];

        $openHour  = 8;
        $closeHour = 22;

        if ($start < $openHour || $end > $closeHour) {
            return back()->withInput()->with('error', 'Jam booking di luar jam operasional.');
        }

        $activeStatuses = ['pending', 'approved_unpaid', 'approved_paid', 'paid'];

        $conflict = Booking::where('field_id', $data['field_id'])
            ->whereDate('booking_date', $data['booking_date'])
            ->whereIn('status', $activeStatuses)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_hour', '<', $end)
                  ->where('end_hour', '>', $start);
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Slot jam yang dipilih bentrok dengan booking lain.');
        }

        $field = Field::findOrFail($data['field_id']);
        $duration = $end - $start;

        $totalPrice = (int) $field->price_per_hour * $duration;

        Booking::create([
            'user_id'      => auth()->id(),
            'field_id'     => $data['field_id'],
            'booking_date' => $data['booking_date'],
            'start_hour'   => $start,
            'end_hour'     => $end,

            // kalau kamu masih mau simpan legacy kolom time:
            'start_time'   => sprintf('%02d:00:00', $start),
            'end_time'     => sprintf('%02d:00:00', $end),

            'total_price'  => $totalPrice,
            'status'       => 'pending',
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil dibuat.');
    }
}
