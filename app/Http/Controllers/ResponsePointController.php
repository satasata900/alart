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
            ->withCount('teamMembers')
            ->paginate(10);
            
        $activeCount = ResponsePoint::where('is_active', true)->count();
        $inactiveCount = ResponsePoint::where('is_active', false)->count();
        $totalTeamMembers = DB::table('response_team_members')->count();
        
        return view('response-points.index', compact('responsePoints', 'activeCount', 'inactiveCount', 'totalTeamMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name_ar')->get();
        $operationAreas = OperationArea::orderBy('name')->get();
        
        return view('response-points.create', compact('provinces', 'operationAreas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:response_points',
            'operation_area_id' => 'required|exists:operation_areas,id',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:0',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        
        $responsePoint = ResponsePoint::create($request->all());
        
        return redirect()->route('response-points.index')
            ->with('success', 'تم إنشاء نقطة الاستجابة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponsePoint $responsePoint)
    {
        $responsePoint->load(['operationArea.province', 'operationArea.district', 'operationArea.subdistrict', 'operationArea.village', 'teamMembers']);
        
        return view('response-points.show', compact('responsePoint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResponsePoint $responsePoint)
    {
        $provinces = Province::orderBy('name_ar')->get();
        $operationAreas = OperationArea::orderBy('name')->get();
        
        return view('response-points.edit', compact('responsePoint', 'provinces', 'operationAreas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponsePoint $responsePoint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:response_points,code,' . $responsePoint->id,
            'operation_area_id' => 'required|exists:operation_areas,id',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:0',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        
        $responsePoint->update($request->all());
        
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
