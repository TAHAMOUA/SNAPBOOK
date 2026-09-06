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
        $table->string('id_booking', 20)->primary();

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

        $table->string('id_user', 20);

        $table->foreign('id_user')
            ->references('id_user')
            ->on('users');

        $table->string('id_service', 20);

        $table->foreign('id_service')
            ->references('id_service')
            ->on('services');

        $table->timestamps();
        $table->softDeletes();
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
