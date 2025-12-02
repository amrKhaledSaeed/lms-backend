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
        Schema::table('homework_students', function (Blueprint $table) {
            $table->string('corrected_file')->nullable()->after('updated_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework_students', function (Blueprint $table) {
            $table->dropColumn('corrected_file');
        });
    }
};
