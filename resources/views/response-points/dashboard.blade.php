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
                <h4 class="mb-4">لوحة إدارة نقاط الاستجابة</h4>
                
                <!-- بطاقات الإحصائيات -->
                <div class="row mb-4">
                    <div class="col-md-6">
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
                    
                    <div class="col-md-6">
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
                </div>
                
                <!-- محتوى نقاط الاستجابة -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>قائمة نقاط الاستجابة</h5>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الرمز</th>
                                        <th>الاسم</th>
                                        <th>منطقة العمليات</th>
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
            </div>
        </div>
    </div>
</div>
@endsection
