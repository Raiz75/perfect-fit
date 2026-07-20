<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->integer('question_number');
            $table->text('question_en');
            $table->text('question_tl');
            $table->timestamps();

            $table->unique(['user_id', 'skill_id', 'question_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_questions');
    }
};
