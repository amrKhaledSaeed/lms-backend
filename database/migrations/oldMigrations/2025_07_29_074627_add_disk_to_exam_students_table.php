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
        Schema::table('exam_students', function (Blueprint $table) {
            $table->string('file_disk')->nullable()->after('file');
            $table->string('updated_file_disk')->nullable()->after('updated_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_students', function (Blueprint $table) {
            $table->dropColumn(['file_disk', 'updated_file_disk']);
        });
    }
};
