@extends('layouts.app')

@section('title', 'تعديل منطقة العمليات')

@section('content')
<div class="container-xxl flex-grow-1">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">تعديل منطقة العمليات: {{ $operationArea->code }}</h4>
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
                    <form action="{{ route('operation-areas.update', $operationArea->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
    <label for="code" class="form-label">رمز منطقة العمليات <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $operationArea->code) }}" required>
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="col-md-6">
    <label for="name" class="form-label">اسم منطقة العمليات <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $operationArea->name) }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                            
                            <div class="col-md-6">
                                <label for="is_active" class="form-label">الحالة</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active', $operationArea->is_active) == '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ old('is_active', $operationArea->is_active) == '0' ? 'selected' : '' }}>غير نشط</option>
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
                                <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id">
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
                                <select class="form-select @error('subdistrict_id') is-invalid @enderror" id="subdistrict_id" name="subdistrict_id">
                                    <option value="">-- اختر الناحية --</option>
                                </select>
                                @error('subdistrict_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="village_id" class="form-label">القرية</label>
                                <select class="form-select @error('village_id') is-invalid @enderror" id="village_id" name="village_id">
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $operationArea->description) }}</textarea>
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
                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
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
        
        // دالة تحميل المحافظات
        function loadProvinces() {
            $.ajax({
                url: "{{ route('locations.provinces') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var options = '<option value="">-- اختر المحافظة --</option>';
                    var selectedProvinceId = "{{ old('province_id', $operationArea->province_id ?? '') }}";
                    
                    $.each(data, function(key, value) {
                        var selected = (value.id == selectedProvinceId) ? 'selected' : '';
                        options += '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>';
                    });
                    
                    $('#province_id').html(options);
                    
                    // تحميل المناطق إذا كانت المحافظة محددة
                    if (selectedProvinceId) {
                        loadDistricts(selectedProvinceId);
                    }
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
                    var selectedDistrictId = "{{ old('district_id', $operationArea->district_id ?? '') }}";
                    
                    $.each(data, function(key, value) {
                        var selected = (value.id == selectedDistrictId) ? 'selected' : '';
                        options += '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>';
                    });
                    
                    $('#district_id').html(options).prop('disabled', false);
                    
                    // تحميل النواحي إذا كانت المنطقة محددة
                    if (selectedDistrictId) {
                        loadSubdistricts(selectedDistrictId);
                    }
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
                    var selectedSubdistrictId = "{{ old('subdistrict_id', $operationArea->subdistrict_id ?? '') }}";
                    
                    $.each(data, function(key, value) {
                        var selected = (value.id == selectedSubdistrictId) ? 'selected' : '';
                        options += '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>';
                    });
                    
                    $('#subdistrict_id').html(options).prop('disabled', false);
                    
                    // تحميل القرى إذا كانت الناحية محددة
                    if (selectedSubdistrictId) {
                        loadVillages(selectedSubdistrictId);
                    }
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
                    var selectedVillageId = "{{ old('village_id', $operationArea->village_id ?? '') }}";
                    
                    $.each(data, function(key, value) {
                        var selected = (value.id == selectedVillageId) ? 'selected' : '';
                        options += '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>';
                    });
                    
                    $('#village_id').html(options).prop('disabled', false);
                }
            });
        }
    });
</script>
@endsection
