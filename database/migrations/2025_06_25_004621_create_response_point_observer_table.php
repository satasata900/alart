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
        Schema::create('response_point_observer', function (Blueprint $table) {
            $table->foreignId('response_point_id')->constrained()->onDelete('cascade');
            $table->foreignId('observer_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->primary(['response_point_id', 'observer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_point_observer');
    }
};
