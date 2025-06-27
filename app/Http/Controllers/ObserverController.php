<?php

namespace App\Http\Controllers;

use App\Models\Observer;
use App\Models\OperationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ObserverController extends Controller
{
    /**
     * Get observers by province (AJAX for response points form).
     */
    public function byProvince($provinceId)
    {
        $observers = Observer::where('province_id', $provinceId)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        return response()->json($observers);
    }
    
    public function index(Request $request)
    {
        $query = Observer::with('operationAreas');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                     ->orWhere('username', 'like', "%$q%")
                     ->orWhere('code', 'like', "%$q%");
            });
        }
        $observers = $query->paginate(20);
        $total = Observer::count();
        $active = Observer::where('is_active', true)->count();
        $inactive = $total - $active;
        return view('observers.index', compact('observers', 'total', 'active', 'inactive'));
    }

    public function toggle(Observer $observer)
    {
        $observer->is_active = !$observer->is_active;
        $observer->save();
        // سجل العملية
        \App\Models\ActivityLog::create([
            'observer_id' => $observer->id,
            'action' => $observer->is_active ? 'activate' : 'deactivate',
            'description' => ($observer->is_active ? 'تفعيل' : 'تعطيل') . ' الحساب',
            'ip_address' => request()->ip(),
        ]);
        return redirect()->back()->with('success', 'تم تحديث حالة الراصد بنجاح');
    }

    public function activity(Observer $observer)
    {
        $logs = $observer->activityLogs()->latest('created_at')->paginate(30);
        return view('observers.activity', compact('observer', 'logs'));
    }

    public function show(Observer $observer)
    {
        return view('observers.show', compact('observer'));
    }

    public function edit(Observer $observer)
    {
        $provinces = \App\Models\Province::orderBy('name_ar')->get();
        $areas = \App\Models\OperationArea::where('province_id', $observer->province_id)->get();
        return view('observers.edit', compact('observer', 'provinces', 'areas'));
    }

    public function update(Request $request, Observer $observer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
    'required',
    'string',
    'max:50',
    Rule::unique('observers', 'username')->ignore($observer->id)->whereNull('deleted_at'),
],
            'password' => 'nullable|string|min:6',
            'code' => [
    'required',
    'string',
    'max:50',
    Rule::unique('observers', 'code')->ignore($observer->id)->whereNull('deleted_at'),
],
            'whatsapp' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'rank_stars' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'province_id' => 'required|exists:provinces,id',
            'operation_areas' => 'nullable|array',
            'operation_areas.*' => 'exists:operation_areas,id',
        ]);
        $areaIds = $data['operation_areas'] ?? [];
        $invalidAreas = \App\Models\OperationArea::whereIn('id', $areaIds)
            ->where('province_id', '!=', $data['province_id'])
            ->pluck('id');
        if ($invalidAreas->count() > 0) {
            return back()->withInput()->withErrors(['operation_areas' => 'بعض مناطق العمليات المختارة لا تتبع المحافظة المحددة.']);
        }
        if (!empty($data['password'])) {
            $observer->password = $data['password'];
        }
        $observer->fill($data);
        $observer->save();
        $observer->operationAreas()->sync($areaIds);
        \App\Models\ActivityLog::create([
            'observer_id' => $observer->id,
            'action' => 'update',
            'description' => 'تعديل بيانات الراصد',
            'ip_address' => $request->ip(),
        ]);
        return redirect()->route('observers.index')->with('success', 'تم تعديل بيانات الراصد بنجاح');
    }

    public function destroy(Observer $observer)
    {
        $observer->delete();
        \App\Models\ActivityLog::create([
            'observer_id' => $observer->id,
            'action' => 'delete',
            'description' => 'حذف (تعطيل) الراصد',
            'ip_address' => request()->ip(),
        ]);
        return redirect()->route('observers.index')->with('success', 'تم حذف الراصد بنجاح');
    }

    public function create()
    {
        $provinces = \App\Models\Province::orderBy('name_ar')->get();
        // Initially, no areas are loaded until a province is selected
        $areas = collect();
        return view('observers.create', compact('provinces', 'areas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:50',
                // تعديل التحقق للسماح باستعمال الأسماء المستخدمة في السجلات المحذوفة
                function ($attribute, $value, $fail) {
                    $exists = Observer::where('username', $value)
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($exists) {
                        $fail('قيمة اسم المستخدم مستخدمة بالفعل.');
                    }
                },
            ],
            'password' => 'required|string|min:6',
            'code' => [
                'required', 'string', 'max:50',
                // تعديل التحقق للسماح باستعمال الرموز المستخدمة في السجلات المحذوفة
                function ($attribute, $value, $fail) {
                    $exists = Observer::where('code', $value)
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($exists) {
                        $fail('قيمة رمز الراصد مستخدمة بالفعل.');
                    }
                },
            ],
            'whatsapp' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'rank_stars' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'province_id' => 'required|exists:provinces,id',
            'operation_areas' => 'nullable|array',
            'operation_areas.*' => 'exists:operation_areas,id',
        ]);

        $areaIds = $data['operation_areas'] ?? [];
        if (!empty($areaIds)) {
            $invalidAreasCount = \App\Models\OperationArea::whereIn('id', $areaIds)
                ->where('province_id', '!=', $data['province_id'])
                ->count();

            if ($invalidAreasCount > 0) {
                return back()->withInput()->withErrors(['operation_areas' => 'بعض مناطق العمليات المختارة لا تتبع المحافظة المحددة.']);
            }
        }

        $data['password'] = Hash::make($data['password']);
        $observer = Observer::create($data);

        $observer->operationAreas()->sync($areaIds);

        \App\Models\ActivityLog::create([
            'observer_id' => $observer->id,
            'action' => 'create',
            'description' => 'إنشاء حساب راصد جديد',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('observers.index')->with('success', 'تم إضافة الراصد بنجاح.');
    }
}
