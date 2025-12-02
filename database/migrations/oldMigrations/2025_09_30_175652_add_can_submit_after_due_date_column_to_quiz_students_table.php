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
        Schema::table('quiz_students', function (Blueprint $table) {
            $table->boolean('can_submit_after_due_date')->default(false)->after('is_finished');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_students', function (Blueprint $table) {
            $table->dropColumn('can_submit_after_due_date');
        });
    }
};
