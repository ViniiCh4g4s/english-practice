<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentences', function (Blueprint $table) {
            $table->id();
            $table->text('text_pt');
            $table->text('text_en_reference');
            $table->string('level', 10); // A1, A2, B1, B2, C1, C2
            $table->string('topic', 50)->nullable();
            $table->integer('difficulty_score')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['level', 'active']);
            $table->index('topic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentences');
    }
};
