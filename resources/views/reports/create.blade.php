@extends('layouts.app')

@section('title', 'إضافة بلاغ جديد')


    

    <div class="card">
        <h5 class="card-header border-bottom">نموذج إضافة بلاغ جديد</h5>
        <div class="card-body">
                    <form method="POST" action="{{ route('reports.store') }}" id="reportForm" enctype="multipart/form-data" class="form-report">
                    <style>
                        .form-report {
                            max-width: 100%;
                            margin: 0 auto;
                            padding: 1.5rem;
                        }
                        .form-report .row {
                            margin-right: 260;
                            margin-left: 0;
                        }
                    </style>
                        @csrf

                        <!-- معلومات البلاغ الأساسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold">معلومات البلاغ الأساسية</h6>
                                <hr class="mt-0" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">عنوان البلاغ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="أدخل عنوان البلاغ" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="report_type_id" class="form-label">نوع البلاغ <span class="text-danger">*</span></label>
                                <select id="report_type_id" name="report_type_id" class="form-select @error('report_type_id') is-invalid @enderror" required>
                                    <option value="">اختر نوع البلاغ</option>
                                    @foreach($reportTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('report_type_id')==$type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('report_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">وصف البلاغ <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="وصف تفاصيل البلاغ بشكل واضح" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="urgency_level" class="form-label">مستوى الأهمية <span class="text-danger">*</span></label>
                                <select id="urgency_level" name="urgency_level" class="form-select @error('urgency_level') is-invalid @enderror" required>
                                    <option value="">اختر مستوى الأهمية</option>
                                    @foreach($urgencyLevels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('urgency_level', 'normal')==$key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('urgency_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="report_date" class="form-label">تاريخ البلاغ</label>
                                <input type="date" class="form-control @error('report_date') is-invalid @enderror" id="report_date" name="report_date" value="{{ old('report_date', date('Y-m-d')) }}">
                                @error('report_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- معلومات المُبلغ ومكان البلاغ -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold">معلومات المُبلغ ومكان البلاغ</h6>
                                <hr class="mt-0" />
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="reporter_type" class="form-label">نوع المُبلغ <span class="text-danger">*</span></label>
                                <select id="reporter_type" name="reporter_type" class="form-select @error('reporter_type') is-invalid @enderror" required>
                                    <option value="">اختر نوع المُبلغ</option>
                                    @foreach($reporterTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('reporter_type', 'admin')==$key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('reporter_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3" id="observerSelectContainer" style="display: none;">
                                <label for="observer_id" class="form-label">اسم الراصد <span class="text-danger">*</span></label>
                                <select id="observer_id" name="observer_id" class="form-select @error('observer_id') is-invalid @enderror">
                                    <option value="">اختر الراصد</option>
                                    @foreach($observers as $observer)
                                    <option value="{{ $observer->id }}" @selected(old('observer_id')==$observer->id)>{{ $observer->name }}</option>
                                    @endforeach
                                </select>
                                @error('observer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="operation_area_id" class="form-label">منطقة العمليات <span class="text-danger">*</span></label>
                                <select id="operation_area_id" name="operation_area_id" class="form-select @error('operation_area_id') is-invalid @enderror" required>
                                    <option value="">اختر منطقة العمليات</option>
                                    @foreach($operationAreas as $area)
                                    <option value="{{ $area->id }}" @selected(old('operation_area_id')==$area->id)>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('operation_area_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="assigned_to" class="form-label">تعيين إلى نقطة الاستجابة</label>
                                <select id="assigned_to" name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                    <option value="">اختر نقطة الاستجابة</option>
                                    <!-- سيتم تحديث القائمة تلقائيًا عند اختيار منطقة العمليات -->
                                </select>
                                @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- مرفقات البلاغ -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold">مرفقات البلاغ</h6>
                                <hr class="mt-0" />
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="attachments" class="form-label d-block">تحميل ملفات مرفقة</label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" name="attachments[]" multiple accept="image/*, application/pdf, .doc, .docx, .xls, .xlsx, .txt">
                                    <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('attachments').click();">
                                        <i class="ti ti-paperclip"></i>
                                    </button>
                                </div>
                                <small class="text-muted">يمكن تحميل عدة ملفات من نوع صور، PDF، مستندات Word أو Excel بحد أقصى 10 ميجابايت لكل ملف</small>
                                @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <div id="attachmentsPreview" class="d-flex flex-wrap gap-2">
                                    <!-- سيتم عرض معاينات الملفات هنا -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- ملاحظات إضافية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold">ملاحظات إضافية</h6>
                                <hr class="mt-0" />
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="أي ملاحظات إضافية تخص البلاغ">{{ old('notes') }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- يمكن إضافة حقول مخفية لبيانات الموقع عند الحاجة -->
                            <input type="hidden" name="location_data[latitude]" id="location_latitude" value="{{ old('location_data.latitude') }}">
                            <input type="hidden" name="location_data[longitude]" id="location_longitude" value="{{ old('location_data.longitude') }}">
                        </div>
                        
                        <!-- أزرار الحفظ والإلغاء -->
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <a href="{{ route('reports.index') }}" class="btn btn-label-secondary me-1">
                                    <i class="ti ti-x me-1"></i> إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> حفظ البلاغ
                                </button>
                            </div>
                        </div>

@section('scripts')
<script>
$(document).ready(function() {
    console.log('تشخيص: سكريبت المنسدلات بدأ العمل');

    // دالة لجلب نقاط الاستجابة
    function fetchResponsePoints(areaId) {
        const assignedToSelect = $('#assigned_to');
        assignedToSelect.empty().append('<option value="">اختر نقطة الاستجابة</option>');

        if (!areaId) {
            console.log('لم يتم اختيار منطقة عمليات');
            return;
        }

        assignedToSelect.prop('disabled', true);
        assignedToSelect.append('<option value="" disabled selected>جاري التحميل...</option>');

        $.ajax({
            url: `/test-operation-areas/${areaId}/response-points`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('تشخيص: تم جلب البيانات بنجاح', response);
                assignedToSelect.empty().append('<option value="">اختر نقطة الاستجابة</option>');

                if (response && Array.isArray(response.data) && response.data.length > 0) {
                    response.data.forEach(function(point) {
                        assignedToSelect.append(new Option(`${point.name} (${point.code})`, point.id));
                    });
                } else {
                    console.log('تشخيص: لا توجد نقاط استجابة متاحة لهذه المنطقة.');
                    assignedToSelect.append('<option value="" disabled>لا توجد نقاط استجابة متاحة</option>');
                }
                assignedToSelect.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('تشخيص: حدث خطأ في جلب البيانات', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
                assignedToSelect.empty().append('<option value="">اختر نقطة الاستجابة</option>');
                assignedToSelect.append('<option value="" disabled>حدث خطأ في تحميل البيانات</option>');
                assignedToSelect.prop('disabled', false);
            }
        });
    }

    // استمع لتغيير منطقة العمليات
    $('#operation_area_id').on('change', function() {
        fetchResponsePoints($(this).val());
    });

    // تشغيل تلقائي إذا كان هناك قيمة محددة مسبقًا
    if ($('#operation_area_id').val()) {
        console.log('تشخيص: تحميل النقاط للقيمة المحددة مسبقًا');
        fetchResponsePoints($('#operation_area_id').val());
    }
});
</script>
@endsection
