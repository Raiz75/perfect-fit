<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interest_and_passion_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ministry_category_id')->constrained()->cascadeOnDelete();
            $table->integer('question_number');
            $table->text('question_en');
            $table->text('question_tl');
            $table->timestamps();

            $table->unique(['user_id', 'ministry_category_id', 'question_number'], 'interest_questions_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interest_and_passion_questions');
    }
};
