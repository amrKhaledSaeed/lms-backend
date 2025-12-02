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
            $table->unsignedInteger('total_rows')->nullable()->after('status');
            $table->unsignedInteger('processed_rows')->default(0)->after('total_rows');
            $table->decimal('progress_percentage', 5, 2)->default(0)->after('processed_rows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_export_requests', function (Blueprint $table) {
            $table->dropColumn(['total_rows', 'processed_rows', 'progress_percentage']);
        });
    }
};
