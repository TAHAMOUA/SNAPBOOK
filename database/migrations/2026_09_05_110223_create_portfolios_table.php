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
       Schema::create('portfolios', function (Blueprint $table) {
            $table->string('id_photo', 20)->primary();

            $table->string('image', 255);
            $table->text('description')->nullable();

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
        Schema::dropIfExists('portfolios');
    }
};
