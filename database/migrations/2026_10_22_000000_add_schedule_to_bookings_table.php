<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'pickup_date')) {
                $table->date('pickup_date')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'pickup_time')) {
                $table->time('pickup_time')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'pickup_date')) {
                $table->dropColumn('pickup_date');
            }
            if (Schema::hasColumn('bookings', 'pickup_time')) {
                $table->dropColumn('pickup_time');
            }
        });
    }
};