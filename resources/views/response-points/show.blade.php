@extends('layouts.app')

@section('title', 'عرض نقطة استجابة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تفاصيل نقطة الاستجابة</h5>
                <div>
                    <a href="{{ route('response-points.edit', $responsePoint->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> تعديل
                    </a>
                    <a href="{{ route('response-points.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> العودة للقائمة
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">المعلومات الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 30%">الرمز</th>
                                        <td>{{ $responsePoint->code }}</td>
                                    </tr>
                                    <tr>
                                        <th>الاسم</th>
                                        <td>{{ $responsePoint->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>الحالة</th>
                                        <td>
                                            @if ($responsePoint->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الإنشاء</th>
                                        <td>{{ $responsePoint->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>آخر تحديث</th>
                                        <td>{{ $responsePoint->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">الموقع</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 30%">منطقة العمليات</th>
                                        <td>
                                            @if ($responsePoint->operationArea)
                                                <a href="{{ route('operation-areas.show', $responsePoint->operationArea->id) }}">
                                                    {{ $responsePoint->operationArea->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>المحافظة</th>
                                        <td>{{ $responsePoint->province->name_ar ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>المنطقة</th>
                                        <td>{{ $responsePoint->district->name_ar ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الناحية</th>
                                        <td>{{ $responsePoint->subdistrict->name_ar ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>القرية</th>
                                        <td>{{ $responsePoint->village->name_ar ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>العنوان التفصيلي</th>
                                        <td>{{ $responsePoint->address ?? 'غير محدد' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الوصف</h6>
                            </div>
                            <div class="card-body">
                                {{ $responsePoint->description ?? 'لا يوجد وصف' }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">أعضاء الفريق ({{ $responsePoint->teamMembers->count() }})</h6>
                                <a href="{{ route('response-team-members.create') }}?response_point_id={{ $responsePoint->id }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus"></i> إضافة عضو
                                </a>
                            </div>
                            <div class="card-body">
                                @if($responsePoint->teamMembers->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>الاسم</th>
                                                    <th>اسم المستخدم</th>
                                                    <th>الرتبة</th>
                                                    <th>قائد الفريق</th>
                                                    <th>الحالة</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($responsePoint->teamMembers as $member)
                                                <tr>
                                                    <td>{{ $member->id }}</td>
                                                    <td>{{ $member->name }}</td>
                                                    <td>{{ $member->username }}</td>
                                                    <td>{{ $member->rank ?? '-' }}</td>
                                                    <td>
                                                        @if($member->is_leader)
                                                            <span class="badge bg-primary">قائد</span>
                                                        @else
                                                            <span class="badge bg-secondary">عضو</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($member->is_active)
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
                                                                        @if($member->is_active)
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
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <p>لا يوجد أعضاء في هذه النقطة</p>
                                        <a href="{{ route('response-team-members.create') }}?response_point_id={{ $responsePoint->id }}" class="btn btn-primary">
                                            <i class="bx bx-plus"></i> إضافة عضو جديد
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
