<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@naisya.test'],
            [
                'name' => 'Admin Naisya',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user1@test.com'],
            [
                'name' => 'User Satu',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@test.com'],
            [
                'name' => 'User Dua',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
