@extends('layouts.app')

@section('title', 'أعضاء فريق الاستجابة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">أعضاء فريق الاستجابة</h5>
                <a href="{{ route('response-team-members.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> إضافة عضو جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- بطاقات الإحصائيات -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-white">الأعضاء النشطين</h5>
                        <h2 class="mb-0">{{ $activeCount }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="bx bx-user-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-white">الأعضاء غير النشطين</h5>
                        <h2 class="mb-0">{{ $inactiveCount }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="bx bx-user-x fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-white">قادة الفرق</h5>
                        <h2 class="mb-0">{{ $leaderCount }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="bx bx-crown fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- جدول أعضاء فريق الاستجابة -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
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
                                            <form action="{{ route('response-team-members.destroy', $member->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('هل أنت متأكد من حذف هذا العضو؟')">
                                                    <i class="bx bx-trash me-1"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">لا يوجد أعضاء مسجلين</td>
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
<div class="alert alert-info text-center mt-5">تم حذف قسم فريق الاستجابة من النظام نهائياً.</div>
@endsection
