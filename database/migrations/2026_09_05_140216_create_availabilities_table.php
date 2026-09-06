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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->string('id_availability', 20)->primary();

            $table->date('available_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('id_profile', 20);

            $table->foreign('id_profile')
                ->references('id_profile')
                ->on('photographer_profiles');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};