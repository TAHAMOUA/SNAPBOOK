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
       Schema::create('reviews', function (Blueprint $table) {
            $table->string('id_review', 20)->primary();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamp('review_date');

            $table->string('id_user', 20);

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');

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
        Schema::dropIfExists('reviews');
    }
};
