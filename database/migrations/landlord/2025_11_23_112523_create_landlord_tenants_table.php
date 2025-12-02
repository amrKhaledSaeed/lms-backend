<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            
            // Database mode: 1=single, 2=multi (using DatabaseType enum)
            $table->tinyInteger('database_type')->default(1)->comment('1=Single, 2=Multi');
            
            // Fields for multi-database mode
            $table->string('database_name')->nullable();
            $table->string('database_username')->nullable();
            $table->string('database_password')->nullable();
            
            // Keep for backwards compatibility
            $table->string('database')->nullable()->comment('Legacy field');
            
            // Tenant status: 1=active, 2=suspended, 3=archived
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=suspended, 3=archived');
            
            // Resource limits
            $table->integer('max_users')->nullable()->comment('User limit for this tenant');
            $table->integer('max_storage_gb')->nullable()->comment('Storage limit in GB');
            
            $table->timestamps();
            
            // Indexes
            $table->index('domain', 'idx_domain');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
