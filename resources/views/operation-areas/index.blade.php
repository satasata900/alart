@extends('layouts.app')

@section('title', 'مناطق العمليات')

@section('content')
<div class="container-xxl flex-grow-1">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">مناطق العمليات</h4>
                <a href="{{ route('operation-areas.create') }}" class="btn btn-primary">
                    <i class='bx bx-plus'></i>
                    إضافة منطقة عمليات جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- الإحصائيات -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="stats-card stats-card-primary">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $totalAreas ?? 0 }}</h3>
                        <p>إجمالي مناطق العمليات</p>
                    </div>
                    <div>
                        <i class='bx bx-map fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="stats-card stats-card-success">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $activeAreas ?? 0 }}</h3>
                        <p>مناطق العمليات النشطة</p>
                    </div>
                    <div>
                        <i class='bx bx-check-circle fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="stats-card stats-card-info">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $provincesCount ?? 0 }}</h3>
                        <p>المحافظات</p>
                    </div>
                    <div>
                        <i class='bx bx-building fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="stats-card stats-card-warning">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $districtsCount ?? 0 }}</h3>
                        <p>المناطق</p>
                    </div>
                    <div>
                        <i class='bx bx-map-alt fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول مناطق العمليات -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">قائمة مناطق العمليات</h5>
                    <div class="d-flex">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-search'></i></span>
                            <input type="text" id="search-areas" class="form-control" placeholder="بحث في مناطق العمليات...">
                        </div>
                    </div>
                </div>
                <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الرمز</th>
                                    <th>الاسم</th>
                                    <th>الموقع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operationAreas ?? [] as $area)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $area->code }}</td>
                                    <td>{{ $area->name }}</td>
                                    <td>
                                        {{ $area->province?->name_ar ?? '' }}
                                        {{ isset($area->district?->name_ar) ? ' - ' . $area->district->name_ar : '' }}
                                        {{ isset($area->subdistrict?->name_ar) ? ' - ' . $area->subdistrict->name_ar : '' }}
                                        {{ isset($area->village?->name_ar) ? ' - ' . $area->village->name_ar : '' }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('operation-areas.show', $area->id) }}" class="btn btn-sm btn-info me-2">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <a href="{{ route('operation-areas.edit', $area->id) }}" class="btn btn-sm btn-primary me-2">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <form action="{{ route('operation-areas.destroy', $area->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد مناطق عمليات مضافة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($operationAreas) && $operationAreas->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $operationAreas->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // بحث في الجدول
    $(document).ready(function() {
        $("#search-areas").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection
