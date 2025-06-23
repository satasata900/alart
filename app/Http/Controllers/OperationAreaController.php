<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\OperationArea;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OperationAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // الإحصائيات
        $totalAreas = OperationArea::count();
        $activeAreas = OperationArea::where('is_active', true)->count();
        $provincesCount = Province::count();
        $districtsCount = District::count();

        // قائمة مناطق العمليات مع التصفح
        $operationAreas = OperationArea::with(['province', 'district', 'subdistrict', 'village'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('operation-areas.index', compact(
            'operationAreas',
            'totalAreas',
            'activeAreas',
            'provincesCount',
            'districtsCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operation-areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // التحقق من البيانات
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:operation_areas,code',
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'province_id' => 'required|exists:provinces,id',
                'district_id' => 'nullable|exists:districts,id',
                'subdistrict_id' => 'nullable|exists:subdistricts,id',
                'village_id' => 'nullable|exists:villages,id',
                'is_active' => 'boolean',
                'observers' => 'nullable|array',
                'response_points' => 'nullable|array',
            ]);

            // إنشاء منطقة العمليات الجديدة
            $operationArea = OperationArea::create($validated);

            // إضافة الراصدين إذا تم تحديدهم (سيتم تنفيذه لاحقًا)
            if ($request->has('observers') && !empty($request->observers)) {
                // $operationArea->observers()->sync($request->observers);
            }

            // إضافة نقاط الاستجابة إذا تم تحديدها (سيتم تنفيذه لاحقًا)
            if ($request->has('response_points') && !empty($request->response_points)) {
                // $operationArea->responsePoints()->sync($request->response_points);
            }

            // تنظيف جلسة الأخطاء وإضافة رسالة النجاح
            $request->session()->forget('errors');
            $request->session()->flash('success', 'تم إضافة منطقة العمليات بنجاح');
            
            // إذا كان الطلب بواسطة AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة منطقة العمليات بنجاح',
                    'redirect' => route('operation-areas.index')
                ]);
            }
            
            // التوجيه العادي للطلبات غير AJAX
            return redirect()->route('operation-areas.index');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ مع طلب AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'error' => 'حدث خطأ أثناء إنشاء منطقة العمليات: ' . $e->getMessage()
                    ]
                ], 422);
            }
            
            // في حالة حدوث خطأ مع الطلبات العادية
            return back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء إنشاء منطقة العمليات: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $operationArea = OperationArea::with(['province', 'district', 'subdistrict', 'village'])
            ->findOrFail($id);

        return view('operation-areas.show', compact('operationArea'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $operationArea = OperationArea::findOrFail($id);
        
        return view('operation-areas.edit', compact('operationArea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $operationArea = OperationArea::findOrFail($id);

        // التحقق من البيانات
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('operation_areas')->ignore($operationArea->id),
            ],
            'description' => 'required|string|max:500',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'village_id' => 'nullable|exists:villages,id',
            'is_active' => 'boolean',
            'observers' => 'nullable|array',
            'response_points' => 'nullable|array',
        ]);

        // تحديث منطقة العمليات
        $operationArea->update($validated);

        // تحديث الراصدين إذا تم تحديدهم (سيتم تنفيذه لاحقًا)
        if ($request->has('observers')) {
            // $operationArea->observers()->sync($request->observers);
        }

        // تحديث نقاط الاستجابة إذا تم تحديدها (سيتم تنفيذه لاحقًا)
        if ($request->has('response_points')) {
            // $operationArea->responsePoints()->sync($request->response_points);
        }

        return redirect()->route('operation-areas.index')
            ->with('success', 'تم تحديث منطقة العمليات بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $operationArea = OperationArea::findOrFail($id);
        
        // يمكن إضافة التحقق من وجود سجلات مرتبطة هنا قبل الحذف
        // مثال: if ($operationArea->reports()->count() > 0) { return back()->with('error', 'لا يمكن حذف منطقة العمليات لوجود تقارير مرتبطة بها'); }
        
        $operationArea->delete();

        return redirect()->route('operation-areas.index')
            ->with('success', 'تم حذف منطقة العمليات بنجاح');
    }
}
