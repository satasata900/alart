@extends('layouts.app')

@section('title', 'إدارة نقاط الاستجابة')

@section('styles')
<style>
    .nav-tabs .nav-link {
        font-size: 1.1rem;
        padding: 0.75rem 1.5rem;
    }
    
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #696cff;
    }
    
    .tab-pane {
        padding: 1.5rem 0;
    }
    
    .stats-card {
        transition: transform 0.3s;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">لوحة إدارة نقاط الاستجابة وفرق العمل</h4>
                
                <!-- بطاقات الإحصائيات -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white">نقاط الاستجابة</h5>
                                        <h2 class="mb-0">{{ $responsePointsCount }}</h2>
                                    </div>
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bx bx-map-pin fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card bg-success text-white stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white">النقاط النشطة</h5>
                                        <h2 class="mb-0">{{ $activePointsCount }}</h2>
                                    </div>
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bx bx-check-circle fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card bg-info text-white stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white">أعضاء الفرق</h5>
                                        <h2 class="mb-0">{{ $teamMembersCount }}</h2>
                                    </div>
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bx bx-group fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card bg-warning text-white stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white">قادة الفرق</h5>
                                        <h2 class="mb-0">{{ $teamLeadersCount }}</h2>
                                    </div>
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bx bx-crown fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- شريط التبويبات -->
                <ul class="nav nav-tabs" id="responsePointsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="points-tab" data-bs-toggle="tab" data-bs-target="#points" type="button" role="tab" aria-controls="points" aria-selected="true">
                            <i class="bx bx-map-pin me-1"></i>
                            نقاط الاستجابة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button" role="tab" aria-controls="teams" aria-selected="false">
                            <i class="bx bx-group me-1"></i>
                            فرق الاستجابة
                        </button>
                    </li>
                </ul>
                
                <!-- محتوى التبويبات -->
                <div class="tab-content" id="responsePointsTabsContent">
                    <!-- تبويب نقاط الاستجابة -->
                    <div class="tab-pane fade show active" id="points" role="tabpanel" aria-labelledby="points-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>قائمة نقاط الاستجابة</h5>
                            <a href="{{ route('response-points.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> إضافة نقطة استجابة جديدة
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الرمز</th>
                                        <th>الاسم</th>
                                        <th>منطقة العمليات</th>
                                        <th>عدد أعضاء الفريق</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($responsePoints as $point)
                                    <tr>
                                        <td>{{ $point->id }}</td>
                                        <td>{{ $point->code }}</td>
                                        <td>{{ $point->name }}</td>
                                        <td>
                                            @if ($point->operationArea)
                                                <a href="{{ route('operation-areas.show', $point->operationArea->id) }}">
                                                    {{ $point->operationArea->name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $point->responseTeamMembers->count() }}</span>
                                        </td>
                                        <td>
                                            @if ($point->is_active)
                                                <span class="badge bg-success">نشطة</span>
                                            @else
                                                <span class="badge bg-danger">غير نشطة</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('response-points.show', $point->id) }}">
                                                        <i class="bx bx-show me-1"></i> عرض
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('response-points.edit', $point->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> تعديل
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('response-points.team', $point->id) }}">
                                                        <i class="bx bx-group me-1"></i> إدارة الفريق
                                                    </a>
                                                    <form action="{{ route('response-points.toggle', $point->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            @if ($point->is_active)
                                                                <i class="bx bx-power-off me-1 text-danger"></i> تعطيل
                                                            @else
                                                                <i class="bx bx-power-off me-1 text-success"></i> تفعيل
                                                            @endif
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد نقاط استجابة مسجلة</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $responsePoints->links() }}
                        </div>
                    </div>
                    
                    <!-- تبويب فرق الاستجابة -->
                    <div class="tab-pane fade" id="teams" role="tabpanel" aria-labelledby="teams-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>قائمة أعضاء فرق الاستجابة</h5>
                            <a href="{{ route('response-team-members.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> إضافة عضو فريق جديد
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>اسم المستخدم</th>
                                        <th>نقطة الاستجابة</th>
                                        <th>الرتبة</th>
                                        <th>قائد الفريق</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($teamMembers as $member)
                                    <tr>
                                        <td>{{ $member->id }}</td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->username }}</td>
                                        <td>
                                            @if ($member->responsePoint)
                                                <a href="{{ route('response-points.show', $member->responsePoint->id) }}">
                                                    {{ $member->responsePoint->name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $member->rank ?? '-' }}</td>
                                        <td>
                                            @if ($member->is_leader)
                                                <span class="badge bg-primary">قائد</span>
                                            @else
                                                <span class="badge bg-secondary">عضو</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($member->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('response-team-members.show', $member->id) }}">
                                                        <i class="bx bx-show me-1"></i> عرض
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('response-team-members.edit', $member->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> تعديل
                                                    </a>
                                                    <form action="{{ route('response-team-members.toggle', $member->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            @if ($member->is_active)
                                                                <i class="bx bx-power-off me-1 text-danger"></i> تعطيل
                                                            @else
                                                                <i class="bx bx-power-off me-1 text-success"></i> تفعيل
                                                            @endif
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا يوجد أعضاء فريق مسجلين</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $teamMembers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
