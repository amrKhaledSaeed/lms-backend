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
        Schema::table('class_videos', function (Blueprint $table) {
            $table->string('video')->nullable()->change(); // Make the video column nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_videos', function (Blueprint $table) {
            $table->string('video')->nullable(false)->change(); // Revert the video column to not nullable
        });
    }
};
