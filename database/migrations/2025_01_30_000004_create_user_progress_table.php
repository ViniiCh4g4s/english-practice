<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable();
            $table->integer('total_exercises')->default(0);
            $table->integer('correct_exercises')->default(0);
            $table->integer('current_streak')->default(0);
            $table->integer('best_streak')->default(0);
            $table->integer('total_xp')->default(0);
            $table->integer('level')->default(1);
            $table->date('last_practice_date')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
