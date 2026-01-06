<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Field;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::updateOrCreate(
            ['name' => 'Lapangan A'],
            [
                'sport_type' => 'Futsal',
                'price_per_hour' => 120000,
                'location' => 'Jl. Merdeka No. 10',
                'description' => 'Lapangan futsal indoor, rumput sintetis, pencahayaan terang.',
            ]
        );

        Field::updateOrCreate(
            ['name' => 'Lapangan B'],
            [
                'sport_type' => 'Badminton',
                'price_per_hour' => 80000,
                'location' => 'Jl. Sudirman No. 5',
                'description' => 'Lapangan badminton indoor, 2 court, tersedia shuttlecock.',
            ]
        );

        Field::updateOrCreate(
            ['name' => 'Lapangan C'],
            [
                'sport_type' => 'Basket',
                'price_per_hour' => 100000,
                'location' => 'Komplek Olahraga Kota',
                'description' => 'Lapangan basket outdoor, cocok untuk latihan dan sparing.',
            ]
        );
    }
}
