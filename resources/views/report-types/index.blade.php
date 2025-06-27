@extends('layouts.app')

@section('title', 'أنواع البلاغات')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">لوحة التحكم /</span> أنواع البلاغات
        </h4>
        <a href="{{ route('report-types.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            إضافة نوع جديد
        </a>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">قائمة أنواع البلاغات</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرمز</th>
                        <th>الوصف</th>
                        <th>اللون</th>
                        <th>عدد البلاغات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportTypes as $type)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $type->name }}</td>
                        <td><code>{{ $type->code }}</code></td>
                        <td>{{ Str::limit($type->description, 50) }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $type->color }}">
                                {{ $type->color }}
                            </span>
                        </td>
                        <td>{{ $type->reports_count }}</td>
                        <td>
                            @if($type->is_active)
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-secondary">معطل</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('report-types.edit', $type) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('report-types.destroy', $type) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا النوع؟')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                                <form action="{{ route('report-types.toggle', $type) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $type->is_active ? 'btn-warning' : 'btn-success' }}">
                                        <i class="bx {{ $type->is_active ? 'bx-power-off' : 'bx-power-on' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">لا توجد أنواع بلاغات مضافة</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reportTypes->hasPages())
        <div class="card-footer">
            {{ $reportTypes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
