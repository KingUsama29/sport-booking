<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Field;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // optional: biar gak dobel, hapus booking by notes/status tertentu
        Booking::whereIn('notes', ['Latihan tim.', 'Bentrok jadwal.'])->delete();

        $user1 = User::where('email', 'user1@test.com')->first();
        $user2 = User::where('email', 'user2@test.com')->first();
        $fieldA = Field::where('name', 'Lapangan A')->first();
        $fieldB = Field::where('name', 'Lapangan B')->first();

        if ($user1 && $fieldA) {
            Booking::updateOrCreate(
                [
                    'user_id' => $user1->id,
                    'field_id' => $fieldA->id,
                    'booking_date' => now()->addDays(1)->toDateString(),
                    'start_time' => '18:00',
                    'end_time' => '20:00',
                ],
                [
                    'total_price' => 240000,
                    'status' => 'pending',
                    'notes' => 'Latihan tim.',
                ]
            );
        }

        if ($user2 && $fieldB) {
            Booking::updateOrCreate(
                [
                    'user_id' => $user2->id,
                    'field_id' => $fieldB->id,
                    'booking_date' => now()->addDays(2)->toDateString(),
                    'start_time' => '09:00',
                    'end_time' => '10:00',
                ],
                [
                    'total_price' => 80000,
                    'status' => 'approved',
                    'notes' => null,
                ]
            );
        }

        if ($user1 && $fieldB) {
            Booking::updateOrCreate(
                [
                    'user_id' => $user1->id,
                    'field_id' => $fieldB->id,
                    'booking_date' => now()->addDays(3)->toDateString(),
                    'start_time' => '15:00',
                    'end_time' => '16:00',
                ],
                [
                    'total_price' => 80000,
                    'status' => 'rejected',
                    'notes' => 'Bentrok jadwal.',
                ]
            );
        }
    }
}
