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
        Schema::create('tbl_chat_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('tbl_users')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->boolean('is_private')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_chat_models');
    }
};
