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
        Schema::create('tenant_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->unique();
            
            // Student statistics
            $table->integer('total_students')->default(0);
            $table->integer('active_students')->default(0);
            $table->integer('archived_students')->default(0);
            
            // Class statistics
            $table->integer('total_classes')->default(0);
            $table->integer('classes_active_today')->default(0);
            
            // Staff statistics
            $table->integer('total_teachers')->default(0);
            $table->integer('total_assistants')->default(0);
            
            // Performance statistics
            $table->decimal('average_attendance_percentage', 5, 2)->default(0);
            $table->decimal('average_grade', 5, 2)->default(0);
            $table->decimal('average_students', 5, 2)->default(0);
            
            // Student performance indicators
            $table->integer('at_risk_students_count')->default(0);
            $table->integer('top_performers_count')->default(0);
            
            // Task statistics
            $table->integer('urgent_tasks_count')->default(0);
            $table->integer('tasks_completed_today')->default(0);
            
            // Revenue statistics
            $table->decimal('total_revenue', 10, 2)->default(0);
            
            $table->timestamps();
            
            // Index
            $table->index('tenant_id', 'idx_tenant_statistics_tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_statistics');
    }
};

