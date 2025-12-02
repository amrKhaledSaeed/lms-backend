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
        Schema::create('class_category_papers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration');
            $table->decimal('max_grade');
            $table->foreignId('category_id')->constrained('class_categories')->cascadeOnDelete();
            $table->json('grades');
            $table->date('average_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_category_papers');
    }
};
