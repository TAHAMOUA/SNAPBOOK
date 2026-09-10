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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('id_booking', 20)->after('id_profile');

            $table->foreign('id_booking')
                ->references('id_booking')
                ->on('bookings');

            $table->unique('id_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['id_booking']);
            $table->dropUnique(['id_booking']);
            $table->dropColumn('id_booking');
        });
    }
};