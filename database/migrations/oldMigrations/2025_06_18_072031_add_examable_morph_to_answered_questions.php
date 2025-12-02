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
        Schema::table('answered_questions', function (Blueprint $table) {
            $table->string('examable_type');
            $table->unsignedBigInteger('examable_id');
            $table->index(['examable_type', 'examable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answered_questions', function (Blueprint $table) {
            $table->dropIndex(['examable_type', 'examable_id']);
            $table->dropColumn(['examable_type', 'examable_id']);
        });
    }
};
