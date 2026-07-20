<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demographic_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ministry_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('gender')->default(0);
            $table->integer('age_min')->default(1);
            $table->integer('age_max')->default(99);
            $table->tinyInteger('marital_status')->default(0);
            $table->tinyInteger('baptized')->default(2);
            $table->tinyInteger('time_in_faith')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'ministry_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demographic_restrictions');
    }
};
