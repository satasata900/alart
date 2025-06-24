@extends('layouts.app')
@section('title', 'عرض الراصد')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">بيانات الراصد: {{ $observer->name }}</h2>
    <a href="{{ route('observers.index') }}" class="btn btn-secondary mb-3">&larr; عودة لقائمة الراصدين</a>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">اسم الراصد</dt>
                <dd class="col-sm-9">{{ $observer->name }}</dd>
                <dt class="col-sm-3">اسم المستخدم</dt>
                <dd class="col-sm-9">{{ $observer->username }}</dd>
                <dt class="col-sm-3">رمز الراصد</dt>
                <dd class="col-sm-9">{{ $observer->code }}</dd>
                <dt class="col-sm-3">الواتساب</dt>
                <dd class="col-sm-9">{{ $observer->whatsapp }}</dd>
                <dt class="col-sm-3">الهاتف</dt>
                <dd class="col-sm-9">{{ $observer->phone }}</dd>
                <dt class="col-sm-3">الرتبة</dt>
                <dd class="col-sm-9">{{ $observer->rank_stars }} / 5</dd>
                <dt class="col-sm-3">الحالة</dt>
                <dd class="col-sm-9">{!! $observer->is_active ? '<span class="badge bg-success">نشط</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</dd>
                <dt class="col-sm-3">آخر دخول</dt>
                <dd class="col-sm-9">{{ $observer->last_login ? $observer->last_login->format('Y-m-d H:i') : '-' }}</dd>
                <dt class="col-sm-3">المناطق</dt>
                <dd class="col-sm-9">
                    @foreach($observer->operationAreas as $area)
                        <span class="badge bg-info">{{ $area->name }}</span>
                    @endforeach
                </dd>
                <dt class="col-sm-3">الوصف</dt>
                <dd class="col-sm-9">{{ $observer->description }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
