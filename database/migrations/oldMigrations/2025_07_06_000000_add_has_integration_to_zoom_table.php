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
            $table->boolean('has_integration')->default(false)->after('transition_to_live')
                ->comment('Flag to indicate if meeting has Zoom API integration or is manually entered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zoom', function (Blueprint $table) {
            $table->dropColumn('has_integration');
        });
    }
};
