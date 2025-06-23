<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyriaAdminDivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar restricciones de clave externa temporalmente
        Schema::disableForeignKeyConstraints();
        
        // Truncar tablas en orden inverso para evitar problemas de clave externa
        DB::table('villages')->truncate();
        DB::table('subdistricts')->truncate();
        DB::table('districts')->truncate();
        DB::table('provinces')->truncate();
        
        // Reactivar restricciones de clave externa
        Schema::enableForeignKeyConstraints();

        // المحافظات السورية
        $provinces = [
            ['name_ar' => 'دمشق', 'name_en' => 'Damascus', 'code' => 'SY-DI'],
            ['name_ar' => 'حلب', 'name_en' => 'Aleppo', 'code' => 'SY-HL'],
            ['name_ar' => 'ريف دمشق', 'name_en' => 'Rif Dimashq', 'code' => 'SY-RD'],
            ['name_ar' => 'حمص', 'name_en' => 'Homs', 'code' => 'SY-HO'],
            ['name_ar' => 'حماة', 'name_en' => 'Hama', 'code' => 'SY-HA'],
            ['name_ar' => 'اللاذقية', 'name_en' => 'Latakia', 'code' => 'SY-LA'],
            ['name_ar' => 'إدلب', 'name_en' => 'Idlib', 'code' => 'SY-ID'],
            ['name_ar' => 'الحسكة', 'name_en' => 'Al-Hasakah', 'code' => 'SY-HK'],
            ['name_ar' => 'دير الزور', 'name_en' => 'Deir ez-Zor', 'code' => 'SY-DZ'],
            ['name_ar' => 'طرطوس', 'name_en' => 'Tartus', 'code' => 'SY-TA'],
            ['name_ar' => 'الرقة', 'name_en' => 'Raqqa', 'code' => 'SY-RA'],
            ['name_ar' => 'درعا', 'name_en' => 'Daraa', 'code' => 'SY-DR'],
            ['name_ar' => 'السويداء', 'name_en' => 'As-Suwayda', 'code' => 'SY-SU'],
            ['name_ar' => 'القنيطرة', 'name_en' => 'Quneitra', 'code' => 'SY-QU'],
        ];

        foreach ($provinces as $provinceData) {
            Province::create($provinceData);
        }

        // المناطق (لمحافظة إدلب كمثال)
        $idlibProvince = Province::where('name_en', 'Idlib')->first();
        
        $districts = [
            [
                'name_ar' => 'إدلب',
                'name_en' => 'Idlib',
                'code' => 'ID-ID',
                'province_id' => $idlibProvince->id
            ],
            [
                'name_ar' => 'معرة النعمان',
                'name_en' => 'Maarat al-Numan',
                'code' => 'ID-MN',
                'province_id' => $idlibProvince->id
            ],
            [
                'name_ar' => 'أريحا',
                'name_en' => 'Ariha',
                'code' => 'ID-AR',
                'province_id' => $idlibProvince->id
            ],
            [
                'name_ar' => 'حارم',
                'name_en' => 'Harem',
                'code' => 'ID-HA',
                'province_id' => $idlibProvince->id
            ],
            [
                'name_ar' => 'جسر الشغور',
                'name_en' => 'Jisr al-Shughur',
                'code' => 'ID-JS',
                'province_id' => $idlibProvince->id
            ],
        ];

        foreach ($districts as $districtData) {
            District::create($districtData);
        }

        // النواحي (لمنطقة إدلب كمثال)
        $idlibDistrict = District::where('name_en', 'Idlib')->first();
        
        $subdistricts = [
            [
                'name_ar' => 'إدلب',
                'name_en' => 'Idlib',
                'code' => 'ID-ID-ID',
                'district_id' => $idlibDistrict->id
            ],
            [
                'name_ar' => 'سراقب',
                'name_en' => 'Saraqib',
                'code' => 'ID-ID-SR',
                'district_id' => $idlibDistrict->id
            ],
            [
                'name_ar' => 'معرة مصرين',
                'name_en' => 'Maarat Misrin',
                'code' => 'ID-ID-MM',
                'district_id' => $idlibDistrict->id
            ],
            [
                'name_ar' => 'بنش',
                'name_en' => 'Bennish',
                'code' => 'ID-ID-BN',
                'district_id' => $idlibDistrict->id
            ],
        ];

        foreach ($subdistricts as $subdistrictData) {
            Subdistrict::create($subdistrictData);
        }

        // القرى (لناحية إدلب كمثال)
        $idlibSubdistrict = Subdistrict::where('name_en', 'Idlib')->first();
        
        $villages = [
            [
                'name_ar' => 'كفر يحمول',
                'name_en' => 'Kafr Yahmoul',
                'code' => 'ID-ID-ID-KY',
                'subdistrict_id' => $idlibSubdistrict->id
            ],
            [
                'name_ar' => 'النيرب',
                'name_en' => 'Al-Nayrab',
                'code' => 'ID-ID-ID-NY',
                'subdistrict_id' => $idlibSubdistrict->id
            ],
            [
                'name_ar' => 'معرشمشة',
                'name_en' => 'Maarshamsheh',
                'code' => 'ID-ID-ID-MS',
                'subdistrict_id' => $idlibSubdistrict->id
            ],
            [
                'name_ar' => 'معرتمصرين',
                'name_en' => 'Maartmisrin',
                'code' => 'ID-ID-ID-MT',
                'subdistrict_id' => $idlibSubdistrict->id
            ],
            [
                'name_ar' => 'تل منس',
                'name_en' => 'Tell Mannas',
                'code' => 'ID-ID-ID-TM',
                'subdistrict_id' => $idlibSubdistrict->id
            ],
        ];

        foreach ($villages as $villageData) {
            Village::create($villageData);
        }
    }
}
