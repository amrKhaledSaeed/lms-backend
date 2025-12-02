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
        Schema::create('zoom_session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zoom_session_id')->constrained('zoom_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('participant_id'); // Zoom participant ID
            $table->string('participant_uuid')->nullable(); // Zoom participant UUID
            $table->string('participant_name'); // Name from Zoom
            $table->string('participant_email')->nullable(); // Email from Zoom
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->integer('duration')->nullable(); // Duration in seconds
            $table->json('participant_data')->nullable(); // Store additional participant data from zoom
            $table->timestamps();

            $table->index(['zoom_session_id', 'user_id']);
            $table->index(['participant_id', 'participant_uuid']);
            $table->index('joined_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_session_participants');
    }
};
