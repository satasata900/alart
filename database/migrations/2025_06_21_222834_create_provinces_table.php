<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name_ar', 50);
            $table->string('name_en', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // إضافة المحافظات السورية
        $provinces = [
            ['code' => 'DMC', 'name_ar' => 'دمشق', 'name_en' => 'Damascus'],
            ['code' => 'RDM', 'name_ar' => 'ريف دمشق', 'name_en' => 'Rural Damascus'],
            ['code' => 'HLB', 'name_ar' => 'حلب', 'name_en' => 'Aleppo'],
            ['code' => 'HOM', 'name_ar' => 'حمص', 'name_en' => 'Homs'],
            ['code' => 'HMA', 'name_ar' => 'حماة', 'name_en' => 'Hama'],
            ['code' => 'LAT', 'name_ar' => 'اللاذقية', 'name_en' => 'Lattakia'],
            ['code' => 'IDL', 'name_ar' => 'إدلب', 'name_en' => 'Idlib'],
            ['code' => 'HSK', 'name_ar' => 'الحسكة', 'name_en' => 'Al-Hasakah'],
            ['code' => 'DRZ', 'name_ar' => 'دير الزور', 'name_en' => 'Deir ez-Zor'],
            ['code' => 'TRT', 'name_ar' => 'طرطوس', 'name_en' => 'Tartus'],
            ['code' => 'RQQ', 'name_ar' => 'الرقة', 'name_en' => 'Raqqa'],
            ['code' => 'DRA', 'name_ar' => 'درعا', 'name_en' => 'Daraa'],
            ['code' => 'SWD', 'name_ar' => 'السويداء', 'name_en' => 'As-Suwayda'],
            ['code' => 'QUN', 'name_ar' => 'القنيطرة', 'name_en' => 'Quneitra'],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'code' => $province['code'],
                'name_ar' => $province['name_ar'],
                'name_en' => $province['name_en'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
