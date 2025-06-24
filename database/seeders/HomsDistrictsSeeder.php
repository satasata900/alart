<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomsDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // يفترض أن معرف محافظة حمص موجود مسبقاً
        $provinceId = DB::table('provinces')->where('code', 'HOMS')->value('id') ?? 1;

        $districts = [
            ['code' => 'HOMS-01', 'name_ar' => 'حمص', 'name_en' => 'Homs'],
            ['code' => 'HOMS-02', 'name_ar' => 'تلكلخ', 'name_en' => 'Talkalakh'],
            ['code' => 'HOMS-03', 'name_ar' => 'الرستن', 'name_en' => 'Al-Rastan'],
            ['code' => 'HOMS-04', 'name_ar' => 'القصير', 'name_en' => 'Al-Qusayr'],
            ['code' => 'HOMS-05', 'name_ar' => 'تدمر', 'name_en' => 'Palmyra'],
            ['code' => 'HOMS-06', 'name_ar' => 'المخرم', 'name_en' => 'Al-Mukharram'],
            ['code' => 'HOMS-07', 'name_ar' => 'تلدو', 'name_en' => 'Taldu'],
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
