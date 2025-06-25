@extends('layouts.app')

@section('title', 'عرض عضو فريق استجابة')

@section('content')
<div class="alert alert-info text-center mt-5">تم حذف قسم فريق الاستجابة من النظام نهائياً.</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تفاصيل عضو فريق الاستجابة</h5>
                <div>
                    <a href="{{ route('response-team-members.edit', $responseTeamMember->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> تعديل
                    </a>
                    <a href="{{ route('response-team-members.index') }}" class="btn btn-secondary">
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
                                        <th style="width: 30%">الاسم</th>
                                        <td>{{ $responseTeamMember->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>اسم المستخدم</th>
                                        <td>{{ $responseTeamMember->username }}</td>
                                    </tr>
                                    <tr>
                                        <th>الرتبة</th>
                                        <td>{{ $responseTeamMember->rank ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>قائد الفريق</th>
                                        <td>
                                            @if ($responseTeamMember->is_leader)
                                                <span class="badge bg-primary">نعم</span>
                                            @else
                                                <span class="badge bg-secondary">لا</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>الحالة</th>
                                        <td>
                                            @if ($responseTeamMember->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات الاتصال والموقع</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 30%">رقم الهاتف</th>
                                        <td>{{ $responseTeamMember->phone ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم الواتساب</th>
                                        <td>{{ $responseTeamMember->whatsapp ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>نقطة الاستجابة</th>
                                        <td>
                                            @if ($responseTeamMember->responsePoint)
                                                <a href="{{ route('response-points.show', $responseTeamMember->responsePoint->id) }}">
                                                    {{ $responseTeamMember->responsePoint->name }} ({{ $responseTeamMember->responsePoint->code }})
                                                </a>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>منطقة العمليات</th>
                                        <td>
                                            @if ($responseTeamMember->responsePoint && $responseTeamMember->responsePoint->operationArea)
                                                <a href="{{ route('operation-areas.show', $responseTeamMember->responsePoint->operationArea->id) }}">
                                                    {{ $responseTeamMember->responsePoint->operationArea->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
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
                                <h6 class="mb-0">معلومات النظام</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 30%">تاريخ الإنشاء</th>
                                        <td>{{ $responseTeamMember->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>آخر تحديث</th>
                                        <td>{{ $responseTeamMember->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card-footer text-center p-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('response-team-members.edit', $responseTeamMember->id) }}" class="btn btn-primary">
                                    <i class="bx bx-edit"></i> تعديل
                                </a>
                                
                                <form action="{{ route('response-team-members.toggle', $responseTeamMember->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn {{ $responseTeamMember->is_active ? 'btn-danger' : 'btn-success' }}">
                                        <i class="bx bx-power-off"></i> 
                                        {{ $responseTeamMember->is_active ? 'تعطيل' : 'تفعيل' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('response-team-members.destroy', $responseTeamMember->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا العضو؟')">
                                        <i class="bx bx-trash"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-info text-center mt-5">تم حذف قسم فريق الاستجابة من النظام نهائياً.</div>
@endsection
