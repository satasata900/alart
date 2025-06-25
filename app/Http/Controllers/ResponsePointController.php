<?php

namespace App\Http\Controllers;

use App\Models\OperationArea;
use App\Models\Province;
use App\Models\ResponsePoint;
use App\Models\ResponseTeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponsePointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responsePoints = ResponsePoint::with('operationArea.province', 'operationArea.district', 'operationArea.subdistrict', 'operationArea.village')
            
            ->paginate(10);
            
        $activeCount = ResponsePoint::where('is_active', true)->count();
        $inactiveCount = ResponsePoint::where('is_active', false)->count();
        
        
        return view('response-points.index', compact('responsePoints', 'activeCount', 'inactiveCount', 'totalTeamMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name_ar')->get();
        $operationAreas = OperationArea::orderBy('name')->get();
        $observers = \App\Models\Observer::orderBy('name')->get();
        
        return view('response-points.create', compact('provinces', 'operationAreas', 'observers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:response_points',
            'province_id' => 'required|exists:provinces,id',
            'operation_areas' => 'required|array',
            'operation_areas.*' => 'exists:operation_areas,id',
            'observers' => 'nullable|array',
            'observers.*' => 'exists:observers,id',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // التحقق من أن مناطق العمليات المختارة تنتمي للمحافظة المحددة
        $provinceId = $request->province_id;
        $selectedOperationAreas = OperationArea::whereIn('id', $request->operation_areas)
            ->where('province_id', $provinceId)
            ->get();
        
        if ($selectedOperationAreas->count() !== count($request->operation_areas)) {
            return redirect()->back()->withInput()
                ->withErrors(['operation_areas' => 'بعض مناطق العمليات المختارة لا تنتمي للمحافظة المحددة']);
        }
        
        // التحقق من أن الراصدين المختارين ينتمون للمحافظة المحددة (إذا تم اختيارهم)
        if ($request->has('observers') && is_array($request->observers) && count($request->observers) > 0) {
            $selectedObservers = \App\Models\Observer::whereIn('id', $request->observers)
                ->where('province_id', $provinceId)
                ->get();
                
            if ($selectedObservers->count() !== count($request->observers)) {
                return redirect()->back()->withInput()
                    ->withErrors(['observers' => 'بعض الراصدين المختارين لا ينتمون للمحافظة المحددة']);
            }
        }
        
        // استخدام أول منطقة عمليات كمنطقة رئيسية للنقطة
        $primaryOperationAreaId = $request->operation_areas[0];
        $primaryOperationArea = $selectedOperationAreas->firstWhere('id', $primaryOperationAreaId);
        
        // إنشاء نقطة الاستجابة مع المنطقة الرئيسية
        $responsePoint = new ResponsePoint([
            'name' => $request->name,
            'code' => $request->code,
            'operation_area_id' => $primaryOperationAreaId,
            'province_id' => $provinceId,
            'district_id' => $primaryOperationArea->district_id,
            'subdistrict_id' => $primaryOperationArea->subdistrict_id,
            'village_id' => $primaryOperationArea->village_id,
            'address' => $request->address,
            'description' => $request->description,
            'is_active' => true
        ]);
        
        $responsePoint->save();
        
        // إضافة علاقات مناطق العمليات الإضافية (إذا وجدت)
        if (count($request->operation_areas) > 1) {
            // هنا يمكن إضافة علاقة مناطق العمليات الإضافية عندما يتم إنشاء جدول العلاقات المتعددة
            // $responsePoint->additionalOperationAreas()->attach($request->operation_areas);
        }
        
        // إضافة علاقات الراصدين (إذا وجدت)
        if ($request->has('observers') && is_array($request->observers) && count($request->observers) > 0) {
            $responsePoint->observers()->attach($request->observers);
        }
        
        return redirect()->route('response-points.index')
            ->with('success', 'تم إنشاء نقطة الاستجابة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponsePoint $responsePoint)
    {
        $responsePoint->load(['operationArea.province', 'operationArea.district', 'operationArea.subdistrict', 'operationArea.village']);
        
        return view('response-points.show', compact('responsePoint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResponsePoint $responsePoint)
    {
        // تحميل الراصدين المرتبطين بنقطة الاستجابة
        $responsePoint->load('observers');
        
        $provinces = Province::orderBy('name_ar')->get();
        $operationAreas = OperationArea::where('province_id', $responsePoint->province_id)->orderBy('name')->get();
        $observers = \App\Models\Observer::where('province_id', $responsePoint->province_id)->orderBy('name')->get();
        
        // الحصول على مصفوفة بمعرفات الراصدين المرتبطين
        $selectedObserverIds = $responsePoint->observers->pluck('id')->toArray();
        
        return view('response-points.edit', compact('responsePoint', 'provinces', 'operationAreas', 'observers', 'selectedObserverIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponsePoint $responsePoint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:response_points,code,' . $responsePoint->id,
            'province_id' => 'required|exists:provinces,id',
            'operation_areas' => 'required|array',
            'operation_areas.*' => 'exists:operation_areas,id',
            'observers' => 'nullable|array',
            'observers.*' => 'exists:observers,id',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // التحقق من أن مناطق العمليات المختارة تنتمي للمحافظة المحددة
        $provinceId = $request->province_id;
        $selectedOperationAreas = OperationArea::whereIn('id', $request->operation_areas)
            ->where('province_id', $provinceId)
            ->get();
        
        if ($selectedOperationAreas->count() !== count($request->operation_areas)) {
            return redirect()->back()->withInput()
                ->withErrors(['operation_areas' => 'بعض مناطق العمليات المختارة لا تنتمي للمحافظة المحددة']);
        }
        
        // التحقق من أن الراصدين المختارين ينتمون للمحافظة المحددة (إذا تم اختيارهم)
        if ($request->has('observers') && is_array($request->observers) && count($request->observers) > 0) {
            $selectedObservers = \App\Models\Observer::whereIn('id', $request->observers)
                ->where('province_id', $provinceId)
                ->get();
                
            if ($selectedObservers->count() !== count($request->observers)) {
                return redirect()->back()->withInput()
                    ->withErrors(['observers' => 'بعض الراصدين المختارين لا ينتمون للمحافظة المحددة']);
            }
        }
        
        // استخدام أول منطقة عمليات كمنطقة رئيسية للنقطة
        $primaryOperationAreaId = $request->operation_areas[0];
        $primaryOperationArea = $selectedOperationAreas->firstWhere('id', $primaryOperationAreaId);
        
        // تحديث نقطة الاستجابة مع المنطقة الرئيسية
        $responsePoint->update([
            'name' => $request->name,
            'code' => $request->code,
            'operation_area_id' => $primaryOperationAreaId,
            'province_id' => $provinceId,
            'district_id' => $primaryOperationArea->district_id,
            'subdistrict_id' => $primaryOperationArea->subdistrict_id,
            'village_id' => $primaryOperationArea->village_id,
            'address' => $request->address,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false
        ]);
        
        // إضافة علاقات مناطق العمليات الإضافية (إذا وجدت)
        if (count($request->operation_areas) > 1) {
            // هنا يمكن إضافة علاقة مناطق العمليات الإضافية عندما يتم إنشاء جدول العلاقات المتعددة
            // $responsePoint->additionalOperationAreas()->sync($request->operation_areas);
        }
        
        // إضافة علاقات الراصدين (إذا وجدت)
        if ($request->has('observers') && is_array($request->observers) && count($request->observers) > 0) {
            $responsePoint->observers()->sync($request->observers);
        } else {
            // إزالة جميع العلاقات إذا لم يتم تحديد أي راصد
            $responsePoint->observers()->detach();
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث نقطة الاستجابة بنجاح',
                'redirect' => route('response-points.index')
            ]);
        }
        
        return redirect()->route('response-points.index')
            ->with('success', 'تم تحديث نقطة الاستجابة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponsePoint $responsePoint)
    {
        $responsePoint->delete();
        
        return redirect()->route('response-points.index')
            ->with('success', 'تم حذف نقطة الاستجابة بنجاح');
    }
    
    /**
     * Toggle the active status of the response point.
     */
    public function toggle(ResponsePoint $responsePoint)
    {
        $responsePoint->is_active = !$responsePoint->is_active;
        $responsePoint->save();
        
        $status = $responsePoint->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('response-points.index')
            ->with('success', "تم {$status} نقطة الاستجابة بنجاح");
    }
    
    /**
     * Display the dashboard with tabs for response points and team members.
     */
    public function dashboard()
    {
        // إحصائيات نقاط الاستجابة
        $responsePointsCount = ResponsePoint::count();
        $activePointsCount = ResponsePoint::where('is_active', true)->count();
        
        // إحصائيات أعضاء فرق الاستجابة
        $teamMembersCount = ResponseTeamMember::count();
        $teamLeadersCount = ResponseTeamMember::where('is_leader', true)->count();
        
        // بيانات نقاط الاستجابة للجدول
        $responsePoints = ResponsePoint::with('operationArea')
            ->withCount('responseTeamMembers as team_members_count')
            ->paginate(10);
            
        // بيانات أعضاء فرق الاستجابة للجدول
        $teamMembers = ResponseTeamMember::with('responsePoint')
            ->paginate(10);
        
        return view('response-points.dashboard', compact(
            'responsePointsCount',
            'activePointsCount',
            'teamMembersCount',
            'teamLeadersCount',
            'responsePoints',
            'teamMembers'
        ));
    }
}
