@extends('layouts.app')

@section('title', 'إضافة نوع بلاغ جديد')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y" style="margin-right: 50px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">لوحة التحكم /</span>
            <span class="text-muted fw-light">أنواع البلاغات /</span> إضافة نوع جديد
        </h4>
    </div>

    <div class="card">
        <h5 class="card-header border-bottom">نموذج إضافة نوع بلاغ جديد</h5>
        <div class="card-body">
            <form method="POST" action="{{ route('report-types.store') }}" class="row g-3">
                @csrf
                
                <div class="col-md-6">
                    <label for="name" class="form-label">اسم النوع <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="code" class="form-label">الرمز <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">مثال: THEFT, BURGLARY, FIRE, etc.</div>
                </div>

                <div class="col-md-12">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="color" class="form-label">اللون <span class="text-danger">*</span></label>
                    <input type="color" class="form-control form-control-color w-100 @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', '#696cff') }}" required>
                    @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label d-block">الحالة</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="is_active" id="active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">مفعل</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_active" id="inactive" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="inactive">معطل</label>
                    </div>
                    @error('is_active')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">حفظ</button>
                    <a href="{{ route('report-types.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
