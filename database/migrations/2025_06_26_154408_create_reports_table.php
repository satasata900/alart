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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان البلاغ
            $table->text('description'); // وصف البلاغ
            $table->foreignId('report_type_id')->constrained('report_types'); // نوع البلاغ
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new'); // حالة البلاغ
            $table->enum('urgency_level', ['normal', 'urgent', 'emergency'])->default('normal'); // مستوى الأهمية
            $table->enum('reporter_type', ['admin', 'observer']); // نوع المبلغ
            $table->unsignedBigInteger('created_by'); // معرف المستخدم المنشئ
            $table->foreignId('operation_area_id')->nullable()->constrained('operation_areas'); // منطقة العمليات
            $table->foreignId('assigned_to')->nullable()->constrained('response_points'); // نقطة الاستجابة المكلفة
            $table->foreignId('observer_id')->nullable()->constrained('observers'); // معرف الراصد
            $table->json('location_data')->nullable(); // بيانات الموقع (JSON)
            $table->timestamp('report_date')->useCurrent(); // تاريخ البلاغ
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();
            $table->softDeletes(); // للحذف الناعم
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
