@extends('layouts.app')
@section('title', 'إضافة راصد جديد')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">إضافة راصد جديد</h2>
    @if($errors->has('code'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li>{{ $errors->first('code') }}</li>
            </ul>
        </div>
    @endif
    <form action="{{ route('observers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">اسم الراصد <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">رمز الراصد <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" required value="{{ old('code') }}">
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">رقم الواتساب</label>
            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">رقم الاتصال</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">معلومات/وصف الراصد</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">الرتبة (عدد النجوم)</label>
            <select name="rank_stars" class="form-select">
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}" @if(old('rank_stars',1)==$i) selected @endif>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">المحافظة <span class="text-danger">*</span></label>
            <select id="province-select" name="province_id" class="form-select" required>
                <option value="">اختر المحافظة...</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3" id="areas-container" style="display:none;">
            <label class="form-label">مناطق العمليات <span class="text-danger">*</span></label>
            <select name="operation_areas[]" id="areas-select" class="form-select" multiple required disabled>
                <!-- سيتم تعبئة الخيارات عبر جافاسكريبت -->
            </select>
            <div class="form-text">يمكن اختيار أكثر من منطقة بالضغط على Ctrl أو Shift</div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province-select');
            const areasSelect = document.getElementById('areas-select');
            const areasContainer = document.getElementById('areas-container');

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                areasSelect.innerHTML = '';
                if (!provinceId) {
                    areasContainer.style.display = 'none';
                    areasSelect.disabled = true;
                    return;
                }
                fetch(`/operation-areas/by-province/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        areasSelect.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(area => {
                                const option = document.createElement('option');
                                option.value = area.id;
                                option.textContent = area.name;
                                areasSelect.appendChild(option);
                            });
                            areasSelect.disabled = false;
                            areasContainer.style.display = '';
                        } else {
                            areasSelect.disabled = true;
                            areasContainer.style.display = '';
                        }
                    })
                    .catch(() => {
                        areasSelect.innerHTML = '';
                        areasSelect.disabled = true;
                        areasContainer.style.display = '';
                    });
            });
        });
        </script>
        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="is_active" class="form-select">
                <option value="1" @if(old('is_active',1)==1) selected @endif>نشط</option>
                <option value="0" @if(old('is_active',1)==0) selected @endif>موقوف</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">حفظ الراصد</button>
        <a href="{{ route('observers.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
