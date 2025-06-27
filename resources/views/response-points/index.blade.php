@extends('layouts.app')

@section('title', 'نقاط الاستجابة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">نقاط الاستجابة</h5>
                <a href="{{ route('response-points.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> إضافة نقطة استجابة
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
                        <h5 class="card-title text-white">نقاط الاستجابة النشطة</h5>
                        <h2 class="mb-0">{{ $activeCount }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="bx bx-shield-quarter fs-1"></i>
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
                        <h5 class="card-title text-white">نقاط الاستجابة غير النشطة</h5>
                        <h2 class="mb-0">{{ $inactiveCount }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="bx bx-shield-x fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- تم إزالة إحصائية أعضاء الفريق -->
</div>

<!-- جدول نقاط الاستجابة -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الرمز</th>
                                <th>الاسم</th>
                                <th>منطقة العمليات</th>
                                <th>عدد الأعضاء</th>
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
                                        {{ $point->operationArea->name ?? '' }}
                                        @if ($point->operationArea->province)
                                            ({{ $point->operationArea->province->name_ar ?? '' }})
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    
                                </td>
                                <td>
                                    @if ($point->is_active)
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
                                            <a class="dropdown-item" href="{{ route('response-points.show', $point->id) }}">
                                                <i class="bx bx-show me-1"></i> عرض
                                            </a>
                                            <a class="dropdown-item" href="{{ route('response-points.edit', $point->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> تعديل
                                            </a>
                                            <!-- تم إزالة رابط فريق الاستجابة -->
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
                                            <form action="{{ route('response-points.destroy', $point->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('هل أنت متأكد من حذف نقطة الاستجابة؟')">
                                                    <i class="bx bx-trash me-1"></i> حذف
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
@endsection
