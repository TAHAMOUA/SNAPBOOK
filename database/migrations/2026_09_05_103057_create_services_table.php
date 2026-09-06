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
        Schema::create('services', function (Blueprint $table) {
            $table->string('id_service', 20)->primary();

            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedSmallInteger('duration');

            $table->string('id_profile', 20);

            $table->foreign('id_profile')
                ->references('id_profile')
                ->on('photographer_profiles');

            $table->string('id_category', 20);

            $table->foreign('id_category')
                ->references('id_category')
                ->on('categories');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
