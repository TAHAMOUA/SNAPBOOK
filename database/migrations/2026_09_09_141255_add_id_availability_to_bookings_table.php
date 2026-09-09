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
            $table->string('id_availability', 20)->after('event_address');

            $table->foreign('id_availability')
                ->references('id_availability')
                ->on('availabilities');

            $table->index('id_availability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['id_availability']);
            $table->dropIndex(['id_availability']);
            $table->dropColumn('id_availability');
        });
    }
};
