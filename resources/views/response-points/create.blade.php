@extends('layouts.app')

@section('title', 'إضافة نقطة استجابة')

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
                <h5 class="mb-0">إضافة نقطة استجابة جديدة</h5>
                <a href="{{ route('response-points.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> العودة للقائمة
                </a>
            </div>
            <div class="card-body">
                <form id="createForm">
                    @csrf
                    <div class="alert alert-success" id="success-message" style="display: none;">
                        تم إنشاء نقطة الاستجابة بنجاح
                    </div>
                    <div class="alert alert-danger" id="error-message" style="display: none;">
                        حدث خطأ أثناء إنشاء نقطة الاستجابة
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label required-field">الرمز</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="name" class="form-label required-field">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
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
                                    <option value="{{ $province->id }}">{{ $province->name_ar }}</option>
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
                                <option value="">-- اختر مناطق العمليات --</option>
                                <!-- سيتم تعبئة هذه القائمة بواسطة جافا سكريبت بعد اختيار المحافظة -->
                            </select>
                            <div class="form-text">يمكنك اختيار أكثر من منطقة عمليات</div>
                            @error('operation_areas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="observers" class="form-label">الراصدين</label>
                            <select class="form-select select2-multiple @error('observers') is-invalid @enderror" id="observers" name="observers[]" multiple>
                                <option value="">-- اختر الراصدين --</option>
                            </select>
                            @error('observers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="address" class="form-label">العنوان التفصيلي</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" id="submit-btn" class="btn btn-primary me-sm-3 me-1">حفظ</button>
                            <button type="reset" class="btn btn-label-secondary">إلغاء</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تنفيذ النموذج بواسطة AJAX
        $('#submit-btn').on('click', function(e) {
            e.preventDefault();
            
            // إزالة جميع رسائل الخطأ السابقة
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#error-message').hide();
            $('#success-message').hide();
            
            var formData = new FormData($('#createForm')[0]);
            
            $.ajax({
                url: '{{ route("response-points.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#success-message').show();
                    setTimeout(function() {
                        window.location.href = '{{ route("response-points.index") }}';
                    }, 1000);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $('#error-message').text('يرجى تصحيح الأخطاء التالية').show();
                        
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key).after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    } else {
                        $('#error-message').text('حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.').show();
                    }
                }
            });
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
                    console.log('جلب الراصدين للمحافظة', provinceId);
                    $.ajax({
                        url: '/observers/by-province/' + provinceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log('الراصدين المسترجعين:', data);
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
    });
</script>
@endsection
