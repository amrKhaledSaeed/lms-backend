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
            $table->boolean('can_upload_updated_file')->default(false)->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_students', function (Blueprint $table) {
            $table->dropColumn('can_upload_updated_file');
        });
    }
};
