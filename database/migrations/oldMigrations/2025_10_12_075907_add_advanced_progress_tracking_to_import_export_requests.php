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
        Schema::table('import_export_requests', function (Blueprint $table) {
            $table->string('current_stage')->nullable()->after('progress_percentage');
            $table->timestamp('started_at')->nullable()->after('current_stage');
            $table->timestamp('estimated_completion_at')->nullable()->after('started_at');
            $table->decimal('speed_rows_per_second', 10, 2)->nullable()->after('estimated_completion_at');
            $table->unsignedInteger('failed_rows')->default(0)->after('speed_rows_per_second');
            $table->unsignedBigInteger('memory_peak_usage')->nullable()->after('failed_rows');
            $table->text('stage_details')->nullable()->after('memory_peak_usage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_export_requests', function (Blueprint $table) {
            $table->dropColumn([
                'current_stage',
                'started_at',
                'estimated_completion_at',
                'speed_rows_per_second',
                'failed_rows',
                'memory_peak_usage',
                'stage_details',
            ]);
        });
    }
};
