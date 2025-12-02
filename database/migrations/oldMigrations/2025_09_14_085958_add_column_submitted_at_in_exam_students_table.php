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
        Schema::table('exam_students', function (Blueprint $table) {
            $table->dateTime('submitted_at')->nullable()->after('updated_file_disk')->comment('Student submission timestamp');
        });

        DB::table('exam_students')->update([
            'submitted_at' => DB::raw('created_at'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_students', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });
    }
};
