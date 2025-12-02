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
            $table->longText('errors_json')->nullable()->after('errors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_export_requests', function (Blueprint $table) {
            $table->dropColumn('errors_json');
        });
    }
};
