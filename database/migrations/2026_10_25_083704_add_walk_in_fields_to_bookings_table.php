<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->decimal('weight_kg', 8, 2)->nullable();
        $table->decimal('total_price', 10, 2)->nullable();
        $table->string('service_option')->nullable();
        $table->text('notes')->nullable();
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn([
            'weight_kg',
            'total_price',
            'service_option',
            'notes',
        ]);
    });
}
};
