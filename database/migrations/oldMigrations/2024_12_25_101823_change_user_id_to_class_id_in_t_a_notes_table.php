<?php

use App\Models\User;
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
        // Drop the foreign key constraint for user_id
        Schema::table('t_a_notes', function (Blueprint $table) {
            $table->dropForeignIdFor(User::class);
        });

        // Add class_id column if it doesn't exist
        if (! Schema::hasColumn('t_a_notes', 'class_id')) {
            Schema::table('t_a_notes', function (Blueprint $table) {
                $table->unsignedBigInteger('class_id')->after('id')->nullable();
            });
        }

        // Delete records where class_id does not exist in classes table
        DB::table('t_a_notes')->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('classes')
                ->whereColumn('classes.id', 't_a_notes.class_id');
        })->delete();

        // Add foreign key constraint for class_id
        Schema::table('t_a_notes', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->foreign('class_id')
                ->references('id')->on('classes')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint and column class_id
        Schema::table('t_a_notes', function (Blueprint $table) {
            if (Schema::hasColumn('t_a_notes', 'class_id')) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            }

            // Re-add the user_id foreign key constraint
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
        });
    }
};
