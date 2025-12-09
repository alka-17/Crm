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
        Schema::create('custom_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id')->index();
            $table->unsignedBigInteger('custom_field_id')->index();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->onDelete('cascade');
            $table->unique(['contact_id', 'custom_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_values');
    }
};
