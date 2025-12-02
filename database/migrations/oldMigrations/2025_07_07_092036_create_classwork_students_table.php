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
        Schema::create('classwork_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classwork_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('score')->nullable();
            $table->string('file')->nullable();
            $table->string('updated_file')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->string('status')->default('not_attended');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classwork_students');
    }
};
