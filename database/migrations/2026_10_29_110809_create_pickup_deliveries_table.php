
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();

            $table->string('customer_name');
            $table->string('phone', 30);
            $table->string('email')->nullable();

            $table->string('service_type')->default('Standard');
            $table->string('pickup_address');
            $table->string('delivery_address')->nullable();

            $table->dateTime('scheduled_at');
            $table->text('instructions')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_deliveries');
    }
};
