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
        Schema::create('report_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable()->comment('سيتم إنشاء جدول التقارير لاحقاً');
            $table->foreignId('response_point_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users');
            $table->enum('status', ['new', 'in_progress', 'completed', 'rejected'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_assignments');
    }
};
