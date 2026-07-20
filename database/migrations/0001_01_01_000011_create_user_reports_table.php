<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->id();
            $table->string('church_code', 9)->index();
            $table->string('email');
            $table->string('name');
            $table->string('contact_no', 20);
            $table->boolean('music')->default(false);
            $table->boolean('technology')->default(false);
            $table->boolean('writing')->default(false);
            $table->boolean('technical')->default(false);
            $table->boolean('speaking')->default(false);
            $table->boolean('accounting')->default(false);
            $table->boolean('mentoring')->default(false);
            $table->boolean('bible_knowledge')->default(false);
            $table->text('eligible_ministry')->nullable();
            $table->tinyInteger('gender');
            $table->integer('age');
            $table->tinyInteger('marital_status');
            $table->tinyInteger('baptized');
            $table->tinyInteger('time_in_faith');
            $table->dateTime('time_of_submission')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reports');
    }
};
