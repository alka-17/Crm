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
        Schema::create('merge_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_contact_id')->index();
            $table->unsignedBigInteger('secondary_contact_id')->index();
            $table->json('merged_data')->nullable(); // snapshot of what was merged
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merge_logs');
    }
};
