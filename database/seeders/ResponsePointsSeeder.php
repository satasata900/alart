<?php

namespace Database\Seeders;

use App\Models\OperationArea;
use App\Models\Province;
use App\Models\ResponsePoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponsePointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود مناطق عمليات قبل إنشاء نقاط الاستجابة
        $operationAreas = OperationArea::all();
        
        if ($operationAreas->isEmpty()) {
            $this->command->info('لا توجد مناطق عمليات. يرجى تشغيل بذور مناطق العمليات أولاً.');
            return;
        }
        
        // إنشاء نقاط استجابة لكل منطقة عمليات موجودة
        foreach ($operationAreas as $index => $area) {
            // استخراج رمز المحافظة لاستخدامه في رمز نقطة الاستجابة
            $provinceCode = 'XX';
            if ($area->province) {
                $provinceCode = substr($area->province->code, 3, 2); // استخراج الجزء الأخير من رمز المحافظة
            }
            
            // إنشاء نقطة استجابة واحدة لكل منطقة عمليات
            ResponsePoint::create([
                'code' => 'RP-' . $provinceCode . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'name' => 'نقطة استجابة ' . $area->name,
                'description' => 'نقطة استجابة في منطقة ' . $area->name,
                'operation_area_id' => $area->id,
                'province_id' => $area->province_id,
                'district_id' => $area->district_id,
                'subdistrict_id' => $area->subdistrict_id,
                'village_id' => $area->village_id,
                'address' => 'عنوان تفصيلي لنقطة استجابة في ' . $area->name,
                'is_active' => true,
            ]);
            
            // إنشاء نقطة استجابة ثانية لكل منطقة عمليات (بعضها غير نشط للاختبار)
            ResponsePoint::create([
                'code' => 'RP-' . $provinceCode . '-B' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'name' => 'نقطة استجابة ثانوية ' . $area->name,
                'description' => 'نقطة استجابة ثانوية في منطقة ' . $area->name,
                'operation_area_id' => $area->id,
                'province_id' => $area->province_id,
                'district_id' => $area->district_id,
                'subdistrict_id' => $area->subdistrict_id,
                'village_id' => $area->village_id,
                'address' => 'عنوان تفصيلي لنقطة استجابة ثانوية في ' . $area->name,
                'is_active' => $index % 2 == 0, // بعض النقاط غير نشطة للاختبار
            ]);
        }
        
        $this->command->info('تم إنشاء ' . ResponsePoint::count() . ' نقطة استجابة بنجاح.');
    }
}
