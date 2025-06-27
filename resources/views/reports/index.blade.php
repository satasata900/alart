@extends('layouts.app')

@section('title', 'قائمة البلاغات')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">لوحة التحكم /</span> البلاغات
    </h4>

    <!-- إحصائيات البلاغات -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">إجمالي البلاغات</span>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{ $totalReports }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-file-report"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">بلاغات جديدة</span>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{ $newReports }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ti ti-file-plus"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">قيد المعالجة</span>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{ $inProgressReports }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ti ti-loader"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">تمت المعالجة</span>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{ $resolvedReports }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">فلتر البحث</h5>
            <button class="btn btn-sm btn-label-secondary p-1 toggle-filters" type="button" data-bs-toggle="collapse"
                data-bs-target="#filterCollapse">
                <i class="ti ti-chevrons-down"></i>
            </button>
        </div>
        <div class="card-body collapse" id="filterCollapse">
            <form id="reportsFilterForm" action="{{ route('reports.index') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="filter_report_type">نوع البلاغ</label>
                        <select id="filter_report_type" name="report_type_id" class="form-select">
                            <option value="">اختر نوع البلاغ</option>
                            @foreach($reportTypes as $type)
                            <option value="{{ $type->id }}" @selected(request('report_type_id')==$type->id)>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="filter_status">حالة البلاغ</label>
                        <select id="filter_status" name="status" class="form-select">
                            <option value="">اختر الحالة</option>
                            @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(request('status')==$key)>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="filter_urgency">مستوى الأهمية</label>
                        <select id="filter_urgency" name="urgency_level" class="form-select">
                            <option value="">اختر مستوى الأهمية</option>
                            @foreach($urgencyLevels as $key => $label)
                            <option value="{{ $key }}" @selected(request('urgency_level')==$key)>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="filter_operation_area">منطقة العمليات</label>
                        <select id="filter_operation_area" name="operation_area_id" class="form-select">
                            <option value="">اختر منطقة العمليات</option>
                            @foreach($operationAreas as $area)
                            <option value="{{ $area->id }}" @selected(request('operation_area_id')==$area->id)>
                                {{ $area->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="filter_date_from">من تاريخ</label>
                        <input type="date" class="form-control" id="filter_date_from" name="date_from"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="filter_date_to">إلى تاريخ</label>
                        <input type="date" class="form-control" id="filter_date_to" name="date_to"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="filter_keyword">البحث بالعنوان أو الوصف</label>
                        <input type="text" class="form-control" id="filter_keyword" name="keyword"
                            placeholder="أدخل كلمة للبحث..." value="{{ request('keyword') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-end gap-1">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search me-1"></i> بحث
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-label-secondary">
                            <i class="ti ti-refresh me-1"></i> إعادة ضبط
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول البلاغات -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة البلاغات</h5>
            <a href="{{ route('reports.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-sm-1"></i>
                <span class="d-none d-sm-inline-block">إضافة بلاغ جديد</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover border-top">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>نوع البلاغ</th>
                            <th>منطقة العمليات</th>
                            <th>تاريخ البلاغ</th>
                            <th>الأهمية</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration + $reports->firstItem() - 1 }}</td>
                            <td>
                                <a href="{{ route('reports.show', $report) }}" class="text-body fw-medium">
                                    {{ Str::limit($report->title, 30) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">
                                    {{ $report->reportType?->name ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td>{{ $report->operationArea?->name ?? 'غير محدد' }}</td>
                            <td>{{ $report->report_date?->format('Y-m-d') ?? 'غير محدد' }}</td>
                            <td>
                                <span class="badge {{ $report->urgency_badge }}">
                                    {{ $urgencyLevels[$report->urgency_level] ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $report->status_badge }}">
                                    {{ $statuses[$report->status] ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('reports.show', $report) }}">
                                            <i class="ti ti-eye me-1"></i> عرض
                                        </a>
                                        <a class="dropdown-item" href="{{ route('reports.edit', $report) }}">
                                            <i class="ti ti-pencil me-1"></i> تعديل
                                        </a>
                                        <form action="{{ route('reports.destroy', $report) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا البلاغ؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item">
                                                <i class="ti ti-trash me-1"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <img src="{{ asset('assets/img/illustrations/empty.png') }}" alt="No Data"
                                        class="mb-3" style="width: 120px">
                                    <h6 class="text-muted">لا توجد بلاغات للعرض</h6>
                                    <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="ti ti-plus me-1"></i> إضافة بلاغ جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $reports->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // تحميل حالة فلتر البحث
    $(function() {
        const filterCollapse = document.getElementById('filterCollapse');
        const isFilterApplied = @json(count(request()->except(['page', '_token'])) > 0);

        if (isFilterApplied) {
            new bootstrap.Collapse(filterCollapse, { toggle: true });
        }
        
        // تفعيل سيلكت 2 لتحسين القوائم المنسدلة
        $('#filter_report_type, #filter_status, #filter_urgency, #filter_operation_area').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dir: 'rtl'
        });
    });
</script>
@endsection
