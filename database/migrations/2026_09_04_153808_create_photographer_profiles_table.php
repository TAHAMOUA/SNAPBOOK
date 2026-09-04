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
            $table->id('id_profile');
            $table->text('bio')->nullable();
            $table->string('city', 100)->nullable();
            $table->unsignedSmallInteger('experience')->nullable();
            $table->enum('validation_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('id_user')
                ->unique()
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->timestamps();
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
