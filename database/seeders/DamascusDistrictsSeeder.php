<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DamascusDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // يفترض أن معرف محافظة دمشق هو 1، عدله إذا كان مختلفاً
        $provinceId = DB::table('provinces')->where('code', 'DMC')->value('id') ?? 1;

        $districts = [
            ['code' => 'DMC-01', 'name_ar' => 'دمشق القديمة', 'name_en' => 'Old Damascus'],
            ['code' => 'DMC-02', 'name_ar' => 'ساروجة', 'name_en' => 'Sarouja'],
            ['code' => 'DMC-03', 'name_ar' => 'القنوات', 'name_en' => 'Al-Qanawat'],
            ['code' => 'DMC-04', 'name_ar' => 'جوبر', 'name_en' => 'Jobar'],
            ['code' => 'DMC-05', 'name_ar' => 'الميدان', 'name_en' => 'Al-Midan'],
            ['code' => 'DMC-06', 'name_ar' => 'الشاغور', 'name_en' => 'Al-Shaghour'],
            ['code' => 'DMC-07', 'name_ar' => 'القدم', 'name_en' => 'Al-Qadam'],
            ['code' => 'DMC-08', 'name_ar' => 'كفر سوسة', 'name_en' => 'Kafr Sousa'],
            ['code' => 'DMC-09', 'name_ar' => 'المزة', 'name_en' => 'Al-Mazzeh'],
            ['code' => 'DMC-10', 'name_ar' => 'دمر', 'name_en' => 'Dummar'],
            ['code' => 'DMC-11', 'name_ar' => 'برزة', 'name_en' => 'Barzeh'],
            ['code' => 'DMC-12', 'name_ar' => 'القابون', 'name_en' => 'Qaboun'],
            ['code' => 'DMC-13', 'name_ar' => 'ركن الدين', 'name_en' => 'Rukn al-Din'],
            ['code' => 'DMC-14', 'name_ar' => 'الصالحية', 'name_en' => 'Al-Salihiyah'],
            ['code' => 'DMC-15', 'name_ar' => 'المهاجرين', 'name_en' => 'Al-Muhajirin'],
            ['code' => 'DMC-16', 'name_ar' => 'اليرموك', 'name_en' => 'Yarmouk'],
        ];

        foreach ($districts as $district) {
            DB::table('districts')->updateOrInsert([
                'code' => $district['code'],
            ], [
                'province_id' => $provinceId,
                'name_ar' => $district['name_ar'],
                'name_en' => $district['name_en'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
