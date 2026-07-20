<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ministry_id')->constrained()->cascadeOnDelete();
            $table->boolean('music')->default(false);
            $table->boolean('technology')->default(false);
            $table->boolean('writing')->default(false);
            $table->boolean('technical')->default(false);
            $table->boolean('speaking')->default(false);
            $table->boolean('accounting')->default(false);
            $table->boolean('mentoring')->default(false);
            $table->boolean('bible_knowledge')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'ministry_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_restrictions');
    }
};
