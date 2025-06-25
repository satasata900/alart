<?php

namespace App\Http\Controllers;

use App\Models\ResponsePoint;
use App\Models\ResponseTeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// تم حذف الكنترولر بالكامل بناءً على طلب المستخدم
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teamMembers = ResponseTeamMember::with('responsePoint.operationArea')
            ->paginate(10);
            
        $activeCount = ResponseTeamMember::where('is_active', true)->count();
        $inactiveCount = ResponseTeamMember::where('is_active', false)->count();
        $leaderCount = ResponseTeamMember::where('is_leader', true)->count();
        
        return view('response-team-members.index', compact('teamMembers', 'activeCount', 'inactiveCount', 'leaderCount'));
    }

    /**
     * Display a listing of team members for a specific response point.
     */
    public function indexByResponsePoint(ResponsePoint $responsePoint)
    {
        $teamMembers = $responsePoint->teamMembers()->paginate(10);
        
        $activeCount = $responsePoint->teamMembers()->where('is_active', true)->count();
        $inactiveCount = $responsePoint->teamMembers()->where('is_active', false)->count();
        $leaderCount = $responsePoint->teamMembers()->where('is_leader', true)->count();
        
        return view('response-team-members.index-by-point', compact('responsePoint', 'teamMembers', 'activeCount', 'inactiveCount', 'leaderCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $responsePoints = ResponsePoint::where('is_active', true)->orderBy('name')->get();
        
        return view('response-team-members.create', compact('responsePoints'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:response_team_members',
            'password' => 'required|string|min:6',
            'response_point_id' => 'required|exists:response_points,id',
            'rank' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'is_leader' => 'boolean',
        ]);
        
        // If this member is set as leader, remove leader status from other members in the same point
        if ($request->is_leader) {
            ResponseTeamMember::where('response_point_id', $request->response_point_id)
                ->where('is_leader', true)
                ->update(['is_leader' => false]);
        }
        
        $teamMember = ResponseTeamMember::create($request->all());
        
        return redirect()->route('response-team-members.index')
            ->with('success', 'تم إنشاء عضو فريق الاستجابة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponseTeamMember $responseTeamMember)
    {
        $responseTeamMember->load('responsePoint.operationArea');
        
        return view('response-team-members.show', compact('responseTeamMember'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResponseTeamMember $responseTeamMember)
    {
        $responsePoints = ResponsePoint::orderBy('name')->get();
        
        return view('response-team-members.edit', compact('responseTeamMember', 'responsePoints'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponseTeamMember $responseTeamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('response_team_members')->ignore($responseTeamMember->id),
            ],
            'password' => 'nullable|string|min:6',
            'response_point_id' => 'required|exists:response_points,id',
            'rank' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'is_leader' => 'boolean',
        ]);
        
        // If this member is set as leader, remove leader status from other members in the same point
        if ($request->is_leader) {
            ResponseTeamMember::where('response_point_id', $request->response_point_id)
                ->where('id', '!=', $responseTeamMember->id)
                ->where('is_leader', true)
                ->update(['is_leader' => false]);
        }
        
        // Only update password if provided
        if (!$request->filled('password')) {
            $request->request->remove('password');
        }
        
        $responseTeamMember->update($request->all());
        
        return redirect()->route('response-team-members.index')
            ->with('success', 'تم تحديث عضو فريق الاستجابة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponseTeamMember $responseTeamMember)
    {
        $responseTeamMember->delete();
        
        return redirect()->route('response-team-members.index')
            ->with('success', 'تم حذف عضو فريق الاستجابة بنجاح');
    }
    
    /**
     * Toggle the active status of the team member.
     */
    public function toggle(ResponseTeamMember $responseTeamMember)
    {
        $responseTeamMember->is_active = !$responseTeamMember->is_active;
        $responseTeamMember->save();
        
        $status = $responseTeamMember->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('response-team-members.index')
            ->with('success', "تم {$status} عضو فريق الاستجابة بنجاح");
    }
}
