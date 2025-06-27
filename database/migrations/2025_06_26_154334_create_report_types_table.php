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
        Schema::create('report_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم نوع البلاغ
            $table->string('code')->unique(); // رمز فريد للنوع
            $table->text('description')->nullable(); // وصف اختياري
            $table->string('color')->default('#3b82f6'); // لون للتمييز في واجهة المستخدم
            $table->boolean('is_active')->default(true); // حالة النوع (فعال/غير فعال)
            $table->timestamps();
            $table->softDeletes(); // للحذف الناعم
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_types');
    }
};
