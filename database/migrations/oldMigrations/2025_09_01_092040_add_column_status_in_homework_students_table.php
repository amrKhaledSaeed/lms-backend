<?php

use App\Enums\AssignmentStudentStatusEnum;
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
        Schema::table('homework_students', function (Blueprint $table) {
            $table->string('status', 50)->default(AssignmentStudentStatusEnum::NOT_SUBMITTED->value)->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework_students', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
