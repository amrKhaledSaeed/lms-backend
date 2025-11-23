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
            $table->tinyInteger('database_type')->default(1)->comment('1=Single Database, 2=Multi Database');
            
            // Fields for multi-database mode
            $table->string('database_name')->nullable();
            $table->string('database_username')->nullable();
            $table->string('database_password')->nullable();
            
            // Keep for backwards compatibility
            $table->string('database')->nullable();
            
            $table->timestamps();
        });
    }
};
