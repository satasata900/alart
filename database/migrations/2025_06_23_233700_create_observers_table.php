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
        Schema::create('observers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('whatsapp')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('rank_stars')->default(1); // 1-5
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('observer_operation_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observer_id')->constrained()->onDelete('cascade');
            $table->foreignId('operation_area_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['observer_id', 'operation_area_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observer_operation_area');
        Schema::dropIfExists('observers');
    }
};
