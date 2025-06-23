@extends('layouts.app')

@section('title', 'تفاصيل منطقة العمليات')

@section('content')
<div class="container-xxl flex-grow-1">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">تفاصيل منطقة العمليات: {{ $operationArea->code }}</h4>
                <div>
                    <a href="{{ route('operation-areas.edit', $operationArea->id) }}" class="btn btn-primary me-2">
                        <i class='bx bx-edit'></i>
                        تعديل
                    </a>
                    <a href="{{ route('operation-areas.index') }}" class="btn btn-secondary">
                        <i class='bx bx-arrow-back'></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات منطقة العمليات</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">رمز منطقة العمليات:</div>
                        <div class="col-md-8">{{ $operationArea->code }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">الحالة:</div>
                        <div class="col-md-8">
                            @if($operationArea->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">الوصف:</div>
                        <div class="col-md-8">{{ $operationArea->description }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">تاريخ الإنشاء:</div>
                        <div class="col-md-8">{{ $operationArea->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">آخر تحديث:</div>
                        <div class="col-md-8">{{ $operationArea->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">الموقع الجغرافي</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5 fw-bold">المحافظة:</div>
                        <div class="col-md-7">{{ $operationArea->province->name_ar ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 fw-bold">المنطقة:</div>
                        <div class="col-md-7">{{ $operationArea->district->name_ar ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 fw-bold">الناحية:</div>
                        <div class="col-md-7">{{ $operationArea->subdistrict->name_ar ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 fw-bold">القرية:</div>
                        <div class="col-md-7">{{ $operationArea->village->name_ar ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <!-- يمكن إضافة بطاقات إضافية هنا مثل الراصدين ونقاط الاستجابة في المستقبل -->
        </div>
    </div>

    <!-- يمكن إضافة أقسام إضافية هنا مثل الإحصائيات أو التقارير المرتبطة بمنطقة العمليات -->
</div>
@endsection
