@extends('layouts.app')

@section('title', 'تعديل نقطة استجابة')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .required-field::after {
        content: " *";
        color: red;
    }
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d9dee3;
        padding: 0.375rem 0.75rem;
        height: auto;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #696cff;
        box-shadow: 0 0 0.25rem 0.05rem rgba(105, 108, 255, 0.1);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #696cff;
        border: none;
        color: #fff;
        padding: 2px 8px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 5px;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #696cff;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تعديل نقطة استجابة</h5>
                <a href="{{ route('response-points.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> العودة للقائمة
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('response-points.update', $responsePoint->id) }}" method="POST" id="editForm" onsubmit="submitForm(event)">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label required-field">الرمز</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $responsePoint->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="name" class="form-label required-field">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $responsePoint->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="province_id" class="form-label required-field">المحافظة</label>
                            <select class="form-select @error('province_id') is-invalid @enderror" id="province_id" name="province_id" required>
                                <option value="">-- اختر المحافظة --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id', $responsePoint->province_id) == $province->id ? 'selected' : '' }}>
                                        {{ $province->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="operation_areas" class="form-label required-field">مناطق العمليات</label>
                            <select class="form-select @error('operation_areas') is-invalid @enderror" id="operation_areas" name="operation_areas[]" multiple required>
                                @foreach($operationAreas as $area)
                                    <option value="{{ $area->id }}" {{ in_array($area->id, old('operation_areas', [$responsePoint->operation_area_id])) ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">يمكنك اختيار أكثر من منطقة عمليات</div>
                            @error('operation_areas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="observers" class="form-label">الراصدين</label>
                            <select class="form-select @error('observers') is-invalid @enderror" id="observers" name="observers[]" multiple>
                                @foreach($observers as $observer)
                                    <option value="{{ $observer->id }}" {{ in_array($observer->id, $selectedObserverIds ?? []) ? 'selected' : '' }}>
                                        {{ $observer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">يمكنك اختيار أكثر من راصد</div>
                            @error('observers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="address" class="form-label">العنوان</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $responsePoint->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $responsePoint->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $responsePoint->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    نقطة استجابة نشطة
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            <a href="{{ route('response-points.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // تفعيل خاصية البحث في مناطق العمليات
        $('#operation_areas').select2({
            placeholder: "اختر مناطق العمليات",
            allowClear: true,
            dir: "rtl",
            language: "ar",
            width: '100%',
            theme: "classic"
        });
        
        // تفعيل خاصية البحث في الراصدين
        $('#observers').select2({
            placeholder: "اختر الراصدين",
            allowClear: true,
            dir: "rtl",
            language: "ar",
            width: '100%',
            theme: "classic"
        });
        
        // عند تغيير المحافظة
        $('#province_id').on('change', function() {
            var provinceId = $(this).val();
            if (provinceId) {
                // جلب مناطق العمليات حسب المحافظة
                $.ajax({
                    url: '/operation-areas/by-province/' + provinceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#operation_areas').empty();
                        if (data.length === 0) {
                            $('#operation_areas').append('<option value="">-- لا توجد مناطق عمليات --</option>');
                        } else {
                            $.each(data, function(key, value) {
                                $('#operation_areas').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                        $('#operation_areas').trigger('change');
                    }
                });
                // جلب الراصدين حسب المحافظة
                $.ajax({
                    url: '/observers/by-province/' + provinceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#observers').empty();
                        if (data.length === 0) {
                            $('#observers').append('<option value="">-- لا يوجد راصدين --</option>');
                        } else {
                            $.each(data, function(key, value) {
                                $('#observers').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                        $('#observers').trigger('change');
                    }
                });
            } else {
                // تفريغ القوائم إذا لم يتم اختيار محافظة
                $('#operation_areas').empty();
                $('#operation_areas').append('<option value="">-- اختر مناطق العمليات --</option>');
                $('#observers').empty();
                $('#observers').append('<option value="">-- اختر الراصدين --</option>');
                $('#operation_areas').trigger('change');
                $('#observers').trigger('change');
            }
        });
        
        // تحميل البيانات عند تحميل الصفحة إذا كانت المحافظة محددة مسبقًا
        var initialProvinceId = $('#province_id').val();
        if (initialProvinceId) {
            $('#province_id').trigger('change');
        }
        
        // وظيفة إرسال النموذج عبر AJAX
        window.submitForm = function(event) {
            event.preventDefault();
            
            var form = $('#editForm');
            var formData = form.serialize();
            var url = form.attr('action');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // إظهار رسالة نجاح وإعادة التوجيه
                        window.location.href = '/response-dashboard';
                    } else {
                        // إظهار رسالة خطأ
                        alert('حدث خطأ أثناء حفظ البيانات');
                    }
                },
                error: function(xhr) {
                    // إظهار أخطاء التحقق
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'يرجى تصحيح الأخطاء التالية:\n';
                        
                        for (var key in errors) {
                            errorMessage += '- ' + errors[key][0] + '\n';
                            
                            // إضافة فئة الخطأ للحقل
                            $('#' + key).addClass('is-invalid');
                            
                            // إظهار رسالة الخطأ
                            if ($('#' + key).next('.invalid-feedback').length === 0) {
                                $('#' + key).after('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                            } else {
                                $('#' + key).next('.invalid-feedback').text(errors[key][0]);
                            }
                        }
                        
                        alert(errorMessage);
                    } else {
                        alert('حدث خطأ أثناء حفظ البيانات');
                    }
                }
            });
        };
    });
</script>
@endsection
