<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('homework_students', function (Blueprint $table) {
            DB::statement('UPDATE homework_students SET can_upload_updated_file = NOT can_upload_updated_file');
            $table->renameColumn('can_upload_updated_file', 'cannot_upload_updated_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework_students', function (Blueprint $table) {
            DB::statement('UPDATE homework_students SET cannot_upload_updated_file = NOT cannot_upload_updated_file');
            $table->renameColumn('cannot_upload_updated_file', 'can_upload_updated_file');
        });
    }
};
