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
        Schema::create('report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade'); // معرف البلاغ المرتبط
            $table->string('file_path'); // مسار الملف
            $table->string('file_name'); // اسم الملف الأصلي
            $table->string('mime_type'); // نوع الملف
            $table->enum('file_type', ['image', 'video', 'document', 'other'])->default('image'); // تصنيف الملف
            $table->unsignedBigInteger('size')->nullable(); // حجم الملف بالبايت
            $table->text('description')->nullable(); // وصف اختياري
            $table->unsignedBigInteger('uploaded_by'); // معرف المستخدم الذي رفع الملف
            $table->timestamps();
            $table->softDeletes(); // للحذف الناعم
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_attachments');
    }
};
