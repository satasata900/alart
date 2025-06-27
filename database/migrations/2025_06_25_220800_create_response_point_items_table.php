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
        Schema::create('response_point_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_point_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('whatsapp', 20)->nullable();
            $table->string('telegram_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_leader')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_point_items');
    }
};
