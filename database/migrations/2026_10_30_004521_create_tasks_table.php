
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Task information
            $table->string('title');
            $table->text('description')->nullable();

            // Staff assigned to the task
            $table->foreignId('assigned_to')
                ->constrained('users')
                ->cascadeOnDelete();

            // Admin who created the task
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Task priority
            $table->enum('priority', [
                'low',
                'medium',
                'high'
            ])->default('medium');

            // Task status
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');

            // Deadline
            $table->date('due_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
