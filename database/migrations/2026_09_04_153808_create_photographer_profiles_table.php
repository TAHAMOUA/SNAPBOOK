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
        Schema::create('photographer_profiles', function (Blueprint $table) {
            $table->string('id_profile', 20)->primary();

            $table->text('bio')->nullable();
            $table->string('city', 100)->nullable();
            $table->unsignedSmallInteger('experience')->nullable();

            $table->enum('validation_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->string('id_user', 20)->unique();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');

            $table->timestamps();
            $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photographer_profiles');
    }
};
