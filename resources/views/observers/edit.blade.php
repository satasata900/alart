@extends('layouts.app')
@section('title', 'تعديل بيانات الراصد')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">تعديل بيانات الراصد: {{ $observer->name }}</h2>
    <a href="{{ route('observers.index') }}" class="btn btn-secondary mb-3">&larr; عودة لقائمة الراصدين</a>
    <form method="POST" action="{{ route('observers.update', $observer) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">اسم الراصد</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $observer->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">اسم المستخدم</label>
                <input type="text" name="username" class="form-control" value="{{ old('username', $observer->username) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رمز الراصد</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $observer->code) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الواتساب</label>
                <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $observer->whatsapp) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الهاتف</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $observer->phone) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الرتبة</label>
                <select name="rank_stars" class="form-select" required>
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" @if(old('rank_stars', $observer->rank_stars)==$i) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الحالة</label>
                <select name="is_active" class="form-select">
                    <option value="1" @if(old('is_active', $observer->is_active)) selected @endif>نشط</option>
                    <option value="0" @if(!old('is_active', $observer->is_active)) selected @endif>موقوف</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">المحافظة</label>
                <select name="province_id" id="province" class="form-select" required>
                    <option value="">اختر المحافظة</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" @if(old('province_id', $observer->province_id)==$province->id) selected @endif>{{ $province->name_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">مناطق العمليات</label>
                <select name="operation_areas[]" id="operation_areas" class="form-select" multiple required>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" @if(collect(old('operation_areas', $observer->operationAreas->pluck('id')->toArray()))->contains($area->id)) selected @endif>{{ $area->name }}</option>
                    @endforeach
                </select>
                @error('operation_areas')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">كلمة المرور الجديدة (اتركها فارغة إذا لا تريد التغيير)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control">{{ old('description', $observer->description) }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-success">حفظ التعديلات</button>
    </form>
</div>
<script>
    document.getElementById('province').addEventListener('change', function() {
        var provinceId = this.value;
        var areasSelect = document.getElementById('operation_areas');
        areasSelect.innerHTML = '';
        if (provinceId) {
            fetch('/operation-areas/by-province/' + provinceId)
                .then(response => response.json())
                .then(data => {
                    data.forEach(function(area) {
                        var option = document.createElement('option');
                        option.value = area.id;
                        option.text = area.name;
                        areasSelect.appendChild(option);
                    });
                });
        }
    });
</script>
