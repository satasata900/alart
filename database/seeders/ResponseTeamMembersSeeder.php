<?php

namespace Database\Seeders;

use App\Models\ResponsePoint;
use App\Models\ResponseTeamMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResponseTeamMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود نقاط استجابة قبل إنشاء أعضاء الفريق
        $responsePoints = ResponsePoint::all();
        
        if ($responsePoints->isEmpty()) {
            $this->command->info('لا توجد نقاط استجابة. يرجى تشغيل بذور نقاط الاستجابة أولاً.');
            return;
        }
        
        // قائمة بالرتب المحتملة
        $ranks = ['ملازم', 'نقيب', 'رائد', 'مقدم', 'عقيد', 'عميد'];
        
        // إنشاء أعضاء فريق لكل نقطة استجابة
        foreach ($responsePoints as $point) {
            // إنشاء قائد فريق واحد لكل نقطة
            ResponseTeamMember::create([
                'name' => 'قائد فريق ' . $point->name,
                'username' => 'leader_' . strtolower(str_replace('-', '_', $point->code)),
                'password' => Hash::make('password123'),
                'response_point_id' => $point->id,
                'rank' => $ranks[array_rand($ranks)],
                'phone' => '09' . rand(10000000, 99999999),
                'whatsapp' => '09' . rand(10000000, 99999999),
                'is_leader' => true,
                'is_active' => true,
            ]);
            
            // إنشاء 2-5 أعضاء عاديين لكل نقطة
            $memberCount = rand(2, 5);
            for ($i = 1; $i <= $memberCount; $i++) {
                ResponseTeamMember::create([
                    'name' => 'عضو ' . $i . ' - ' . $point->name,
                    'username' => 'member_' . $i . '_' . strtolower(str_replace('-', '_', $point->code)),
                    'password' => Hash::make('password123'),
                    'response_point_id' => $point->id,
                    'rank' => $ranks[array_rand($ranks)],
                    'phone' => '09' . rand(10000000, 99999999),
                    'whatsapp' => '09' . rand(10000000, 99999999),
                    'is_leader' => false,
                    'is_active' => rand(0, 10) > 2, // معظم الأعضاء نشطين، مع بعض الأعضاء غير النشطين للاختبار
                ]);
            }
        }
        
        $this->command->info('تم إنشاء ' . ResponseTeamMember::count() . ' عضو فريق استجابة بنجاح.');
    }
}
