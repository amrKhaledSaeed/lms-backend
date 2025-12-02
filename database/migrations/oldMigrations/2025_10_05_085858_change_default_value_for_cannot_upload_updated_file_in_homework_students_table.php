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
            $table->boolean('cannot_upload_updated_file')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework_students', function (Blueprint $table) {
            $table->boolean('cannot_upload_updated_file')->default(true)->change();
        });
    }
};
