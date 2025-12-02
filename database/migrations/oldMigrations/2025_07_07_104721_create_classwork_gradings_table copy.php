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
        Schema::create('classwork_gradings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classwork_id')->constrained()->cascadeOnDelete();
            $table->string('grade');
            $table->decimal('from', 5, 2);
            $table->decimal('to', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_gradings');
    }
};
