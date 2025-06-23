@extends('layouts.app')

@section('title', 'إضافة منطقة عمليات جديدة')

@section('content')
<div class="container-xxl flex-grow-1">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">إضافة منطقة عمليات جديدة</h4>
                <a href="{{ route('operation-areas.index') }}" class="btn btn-secondary">
                    <i class='bx bx-arrow-back'></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif
                    <form id="createForm" action="{{ route('operation-areas.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
    <label for="code" class="form-label">رمز منطقة العمليات <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="col-md-6">
    <label for="name" class="form-label">اسم منطقة العمليات <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                            <div class="col-md-6">
                                <label for="is_active" class="form-label">الحالة</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">المحافظة <span class="text-danger">*</span></label>
                                <select class="form-select @error('province_id') is-invalid @enderror" id="province_id" name="province_id" required>
                                    <option value="">-- اختر المحافظة --</option>
                                </select>
                                @error('province_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="district_id" class="form-label">المنطقة</label>
                                <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" disabled>
                                    <option value="">-- اختر المنطقة --</option>
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="subdistrict_id" class="form-label">الناحية</label>
                                <select class="form-select @error('subdistrict_id') is-invalid @enderror" id="subdistrict_id" name="subdistrict_id" disabled>
                                    <option value="">-- اختر الناحية --</option>
                                </select>
                                @error('subdistrict_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="village_id" class="form-label">القرية</label>
                                <select class="form-select @error('village_id') is-invalid @enderror" id="village_id" name="village_id" disabled>
                                    <option value="">-- اختر القرية --</option>
                                </select>
                                @error('village_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label">وصف منطقة العمليات</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="observers" class="form-label">الراصدين (اختياري)</label>
                                <select class="form-select @error('observers') is-invalid @enderror" id="observers" name="observers[]" multiple>
                                    <option value="">-- اختر الراصدين --</option>
                                    <!-- سيتم ملء هذه القائمة لاحقًا عند إضافة نظام الراصدين -->
                                </select>
                                @error('observers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="response_points" class="form-label">نقاط الاستجابة (اختياري)</label>
                                <select class="form-select @error('response_points') is-invalid @enderror" id="response_points" name="response_points[]" multiple>
                                    <option value="">-- اختر نقاط الاستجابة --</option>
                                    <!-- سيتم ملء هذه القائمة لاحقًا عند إضافة نظام نقاط الاستجابة -->
                                </select>
                                @error('response_points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">حفظ منطقة العمليات</button>
                                <a href="{{ route('operation-areas.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تحميل المحافظات عند تحميل الصفحة
        loadProvinces();
        
        // تحميل المناطق عند اختيار المحافظة
        $('#province_id').on('change', function() {
            var provinceId = $(this).val();
            if (provinceId) {
                loadDistricts(provinceId);
                $('#district_id').prop('disabled', false);
                $('#subdistrict_id').prop('disabled', true).html('<option value="">-- اختر الناحية --</option>');
                $('#village_id').prop('disabled', true).html('<option value="">-- اختر القرية --</option>');
            } else {
                $('#district_id').prop('disabled', true).html('<option value="">-- اختر المنطقة --</option>');
                $('#subdistrict_id').prop('disabled', true).html('<option value="">-- اختر الناحية --</option>');
                $('#village_id').prop('disabled', true).html('<option value="">-- اختر القرية --</option>');
            }
        });
        
        // تحميل النواحي عند اختيار المنطقة
        $('#district_id').on('change', function() {
            var districtId = $(this).val();
            if (districtId) {
                loadSubdistricts(districtId);
                $('#subdistrict_id').prop('disabled', false);
                $('#village_id').prop('disabled', true).html('<option value="">-- اختر القرية --</option>');
            } else {
                $('#subdistrict_id').prop('disabled', true).html('<option value="">-- اختر الناحية --</option>');
                $('#village_id').prop('disabled', true).html('<option value="">-- اختر القرية --</option>');
            }
        });
        
        // تحميل القرى عند اختيار الناحية
        $('#subdistrict_id').on('change', function() {
            var subdistrictId = $(this).val();
            if (subdistrictId) {
                loadVillages(subdistrictId);
                $('#village_id').prop('disabled', false);
            } else {
                $('#village_id').prop('disabled', true).html('<option value="">-- اختر القرية --</option>');
            }
        });
        
        // معالجة إرسال النموذج
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // إعادة التوجيه يدويًا إلى صفحة القائمة
                    window.location.href = "{{ route('operation-areas.index') }}";
                },
                error: function(xhr) {
    // إعادة تعيين الأخطاء السابقة
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    if (xhr.responseJSON && xhr.responseJSON.errors) {
        var errors = xhr.responseJSON.errors;
        var firstError = '';
        $.each(errors, function(key, value) {
            $('#' + key).addClass('is-invalid');
            $('#' + key).next('.invalid-feedback').text(value[0]);
            if (!firstError) firstError = value[0];
        });
        if (firstError) {
            // عرض رسالة عامة في الأعلى
            if ($('.alert-danger').length === 0) {
                $('#createForm').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+firstError+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button></div>');
            }
        }
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
        // إذا لم تكن هناك أخطاء حقول ولكن هناك رسالة عامة
        if ($('.alert-danger').length === 0) {
            $('#createForm').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+xhr.responseJSON.message+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button></div>');
        }
    }
}
            });
        });
        
        // دالة تحميل المحافظات
        function loadProvinces() {
            $.ajax({
                url: "{{ route('locations.provinces') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var options = '<option value="">-- اختر المحافظة --</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    $('#province_id').html(options);
                }
            });
        }
        
        // دالة تحميل المناطق حسب المحافظة
        function loadDistricts(provinceId) {
            $.ajax({
                url: "{{ url('locations/districts') }}/" + provinceId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var options = '<option value="">-- اختر المنطقة --</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    $('#district_id').html(options);
                }
            });
        }
        
        // دالة تحميل النواحي حسب المنطقة
        function loadSubdistricts(districtId) {
            $.ajax({
                url: "{{ url('locations/subdistricts') }}/" + districtId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var options = '<option value="">-- اختر الناحية --</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    $('#subdistrict_id').html(options);
                }
            });
        }
        
        // دالة تحميل القرى حسب الناحية
        function loadVillages(subdistrictId) {
            $.ajax({
                url: "{{ url('locations/villages') }}/" + subdistrictId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var options = '<option value="">-- اختر القرية --</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    $('#village_id').html(options);
                }
            });
        }
    });
</script>
@endsection
