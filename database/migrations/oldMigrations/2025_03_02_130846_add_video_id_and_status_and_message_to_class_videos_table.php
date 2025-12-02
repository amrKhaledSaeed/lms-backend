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
            $table->uuid('video_id')->after('video')->nullable();
            $table->enum('status', ['pending', 'transferring', 'uploaded', 'failed', 'ready'])->after('video_id')
                ->default('pending');
            $table->text('message')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_videos', function (Blueprint $table) {
            $table->dropColumn('video_id');
            $table->dropColumn('status');
        });
    }
};
