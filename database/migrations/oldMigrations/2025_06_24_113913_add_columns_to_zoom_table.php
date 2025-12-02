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
        Schema::table('zoom', function (Blueprint $table) {
            $table->string('meeting_uuid')->nullable()->after('id');
            $table->string('host_id')->nullable()->after('meeting_uuid');
            $table->string('host_email')->nullable()->after('host_id');
            $table->string('start_url')->nullable()->after('join_url');
            $table->text('settings')->nullable()->after('start_url');
            $table->string('timezone')->default('UTC')->after('settings');
            $table->boolean('is_simulive')->default(false)->after('timezone');
            $table->boolean('support_go_live')->default(false)->after('is_simulive');
            $table->boolean('transition_to_live')->default(false)->after('support_go_live');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zoom', function (Blueprint $table) {
            $table->dropColumn([
                'meeting_uuid',
                'host_id',
                'host_email',
                'start_url',
                'settings',
                'timezone',
                'is_simulive',
                'support_go_live',
                'transition_to_live',
            ]);
        });
    }
};
