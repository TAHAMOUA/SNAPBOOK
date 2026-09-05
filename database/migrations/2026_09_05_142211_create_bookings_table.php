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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id('id_booking');

        $table->timestamp('booking_date');
        $table->date('event_date');
        $table->string('event_address', 255);

        $table->decimal('total_price', 10, 2);

        $table->enum('status', [
            'pending',
            'accepted',
            'rejected',
            'cancelled',
            'completed'
        ])->default('pending');

        $table->foreignId('id_user')
            ->constrained('users', 'id_user')
            ->cascadeOnDelete();

        $table->foreignId('id_service')
            ->constrained('services', 'id_service')
            ->restrictOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
