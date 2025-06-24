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
     * Get operation areas by province (AJAX for observers form).
     */
    public function byProvince($provinceId)
    {
        $areas = \App\Models\OperationArea::where('province_id', $provinceId)
            ->where('is_active', true)
            ->select('id', 'name as name')
            ->orderBy('name')
            ->get();
        return response()->json($areas);
    }

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

    $operationArea = OperationArea::create($validated);

    // إضافة الراصدين إذا تم تحديدهم (سيتم تنفيذه لاحقًا)
    if ($request->has('observers') && !empty($request->observers)) {
        // $operationArea->observers()->sync($request->observers);
    }

    // إضافة نقاط الاستجابة إذا تم تحديدها (سيتم تنفيذه لاحقًا)
    if ($request->has('response_points') && !empty($request->response_points)) {
        // $operationArea->responsePoints()->sync($request->response_points);
    }

    $request->session()->flash('success', 'تم إضافة منطقة العمليات بنجاح');

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'تم إضافة منطقة العمليات بنجاح',
            'redirect' => route('operation-areas.index')
        ]);
    }

    return redirect()->route('operation-areas.index');
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
        try {
            $operationArea = OperationArea::findOrFail($id);

            // التحقق من البيانات
            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('operation_areas')->ignore($operationArea->id),
                ],
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

            DB::beginTransaction();
            
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

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث منطقة العمليات بنجاح',
                    'redirect' => route('operation-areas.index')
                ]);
            }

            return redirect()->route('operation-areas.index')
                ->with('success', 'تم تحديث منطقة العمليات بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث منطقة العمليات: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث منطقة العمليات: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        $operationArea = OperationArea::findOrFail($id);
        $operationArea->delete();
        return redirect()->route('operation-areas.index')->with('success', 'تم حذف منطقة العمليات بنجاح (حذف ناعم).');
    } catch (\Exception $e) {
        return redirect()->route('operation-areas.index')->with('error', 'حدث خطأ أثناء حذف منطقة العمليات: ' . $e->getMessage());
    }
}
}
