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
        Schema::create('response_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_point_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('rank')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->boolean('is_leader')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_team_members');
    }
};
