<?php

use App\Enums\ImportExportRequestStatusEnum;
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
        Schema::create('import_export_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('file_name')->nullable();
            $table->string('status')->default(ImportExportRequestStatusEnum::Pending->value);
            $table->string('entity_type')->nullable();
            $table->string('url')->nullable();
            $table->string('error_message')->nullable();
            $table->longText('errors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_export_requests');
    }
};
