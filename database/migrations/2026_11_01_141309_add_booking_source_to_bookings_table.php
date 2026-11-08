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
            $table->string('booking_source')
                ->default('online')
                ->after('service_option');
        });

        // Convert old Walk-in records to the new structure.
        DB::table('bookings')
            ->where('service_option', 'Walk-in')
            ->update([
                'booking_source' => 'walk_in',
                'service_option' => 'drop_off',
            ]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_source');
        });
    }
};