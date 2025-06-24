<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'unique' => 'قيمة :attribute مستخدمة بالفعل.',
    'max' => 'يجب ألا يتجاوز :attribute :max حرفًا.',
    'min' => 'يجب ألا يقل :attribute عن :min حرفًا.',
    'string' => 'يجب أن يكون :attribute نصًا.',
    'exists' => 'القيمة المحددة في :attribute غير صالحة.',
    'boolean' => 'يجب أن يكون :attribute نعم أو لا.',
    // رسائل مخصصة للحقول
    'attributes' => [
        // حقول منطقة العمليات
        'operation_areas.code' => 'رمز منطقة العمليات',
        'operation_areas.name' => 'اسم منطقة العمليات',
        
        // حقول الراصدين
        'username' => 'اسم المستخدم',
        'password' => 'كلمة المرور',
        'code' => 'رمز الراصد',
        'name' => 'اسم الراصد',
        
        // حقول مشتركة
        'description' => 'الوصف',
        'province_id' => 'المحافظة',
        'district_id' => 'المنطقة',
        'subdistrict_id' => 'الناحية',
        'village_id' => 'القرية',
        'operation_areas' => 'مناطق العمليات',
    ],
];
