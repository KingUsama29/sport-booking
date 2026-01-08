<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // booking_date sudah ada? kalau belum baru add
            if (!Schema::hasColumn('bookings', 'booking_date')) {
                $table->date('booking_date')->nullable()->after('field_id');
            }

            // start_hour belum ada? add
            if (!Schema::hasColumn('bookings', 'start_hour')) {
                $table->unsignedTinyInteger('start_hour')->nullable()->after('booking_date');
            }

            // unique index (cek dulu biar aman)
            // ini ga bisa cek exists dengan schema builder biasa,
            // tapi aman: coba buat aja, kalau error baru kita rename.
            $table->unique(['field_id', 'booking_date', 'start_hour'], 'uniq_field_date_hour');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // drop unique kalau ada
            try { $table->dropUnique('uniq_field_date_hour'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('bookings', 'start_hour')) {
                $table->dropColumn('start_hour');
            }

            // booking_date jangan kita drop kalau kamu sudah pakai sebelumnya
        });
    }
};
