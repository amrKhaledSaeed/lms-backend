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
        Schema::create('zoom_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zoom_id')->constrained('zoom')->cascadeOnDelete();
            $table->string('meeting_id'); // Zoom meeting ID
            $table->string('meeting_uuid'); // Zoom meeting UUID
            $table->enum('session_type', ['meeting', 'webinar']);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('session_data')->nullable(); // Store additional session data from zoom
            $table->timestamps();

            $table->index(['meeting_id', 'meeting_uuid']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_sessions');
    }
};
