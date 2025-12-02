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
        Schema::create('zoom', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('meeting_id');
            $table->enum('main_type', ['meeting', 'webinar'])->default('meeting');
            $table->string('type');
            $table->text('agenda');
            $table->string('join_url');
            $table->string('password')->nullable();
            $table->integer('duration')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom');
    }
};
