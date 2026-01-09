<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // kalau start_hour sudah ada, biarin. kalau belum, tambahin.
            if (!Schema::hasColumn('bookings', 'start_hour')) {
                $table->unsignedTinyInteger('start_hour')->nullable()->after('booking_date');
            }

            if (!Schema::hasColumn('bookings', 'end_hour')) {
                $table->unsignedTinyInteger('end_hour')->nullable()->after('start_hour');
            }
        });

        // Backfill dari data lama (start_time/end_time -> start_hour/end_hour)
        DB::table('bookings')
            ->whereNull('start_hour')
            ->whereNotNull('start_time')
            ->update([
                'start_hour' => DB::raw('HOUR(start_time)'),
            ]);

        DB::table('bookings')
            ->whereNull('end_hour')
            ->whereNotNull('end_time')
            ->update([
                'end_hour' => DB::raw('HOUR(end_time)'),
            ]);

        // Kalau ada yang end_hour masih null (jaga2), set start+1
        DB::table('bookings')
            ->whereNull('end_hour')
            ->whereNotNull('start_hour')
            ->update([
                'end_hour' => DB::raw('start_hour + 1'),
            ]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'end_hour')) {
                $table->dropColumn('end_hour');
            }
            // start_hour jangan di-drop kalau kamu memang sudah punya dari awal
        });
    }
};
