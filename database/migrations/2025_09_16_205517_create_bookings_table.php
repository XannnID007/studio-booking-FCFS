<?php
// database/migrations/2024_01_01_000003_create_bookings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration'); // in hours
            $table->decimal('total_price', 10, 2);
            $table->decimal('dp_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('booking_code')->unique();
            $table->timestamp('booked_at');
            $table->timestamps();

            // Index untuk optimasi FCFS
            $table->index(['studio_id', 'booking_date', 'start_time']);
            $table->index(['booked_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
