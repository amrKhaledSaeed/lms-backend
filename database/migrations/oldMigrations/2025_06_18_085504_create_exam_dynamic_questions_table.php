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
        Schema::create('exam_dynamic_questions', function (Blueprint $table) {
            $table->id();
            $table->string('examable_type');
            $table->unsignedBigInteger('examable_id');
            $table->index(['examable_type', 'examable_id']);

            $table->foreignId('educational_part_id')->constrained('educational_parts')->onDelete('cascade');
            $table->integer('complexity')->default(1);
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_dynamic_questions');
    }
};
