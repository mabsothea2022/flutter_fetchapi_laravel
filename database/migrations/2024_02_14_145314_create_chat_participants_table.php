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
        Schema::create('tbl_chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('tbl_chat_models')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('tbl_users')->cascadeOnDelete();
            $table->unique([
                'chat_id',
                'user_id'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_chat_participants');
    }
};
