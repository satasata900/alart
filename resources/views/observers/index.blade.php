@extends('layouts.app')
@section('title', 'إدارة الراصدين')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">إدارة الراصدين</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">عدد الراصدين</h5>
                    <p class="display-6">{{ $total }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">النشطون</h5>
                    <p class="display-6 text-success">{{ $active }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">غير النشطين</h5>
                    <p class="display-6 text-danger">{{ $inactive }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3 text-end">
        <a href="{{ route('observers.create') }}" class="btn btn-primary">إضافة راصد جديد</a>
    </div>
    <form method="get" class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="بحث بالاسم أو اسم المستخدم أو الرمز" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary" type="submit">بحث</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>اسم المستخدم</th>
                    <th>رمز الراصد</th>
                    <th>الواتساب</th>
                    <th>الهاتف</th>
                    <th>الرتبة</th>
                    <th>الحالة</th>
                    <th>آخر دخول</th>
                    <th>المناطق</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($observers as $observer)
                    <tr>
                        <td>{{ $observer->id }}</td>
                        <td>{{ $observer->name }}</td>
                        <td>{{ $observer->username }}</td>
                        <td>{{ $observer->code }}</td>
                        <td>{{ $observer->whatsapp }}</td>
                        <td>{{ $observer->phone }}</td>
                        <td>
                            @for($i=0; $i<$observer->rank_stars; $i++)
                                <span class="text-warning">&#9733;</span>
                            @endfor
                        </td>
                        <td>
                            <form method="POST" action="{{ route('observers.toggle', $observer) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $observer->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $observer->is_active ? 'تعطيل' : 'تفعيل' }}
                                </button>
                            </form>
                        </td>
                        <td>{{ $observer->last_login ? $observer->last_login->format('Y-m-d H:i') : '-' }}</td>
                        <td>
                            @foreach($observer->operationAreas as $area)
                                <span class="badge bg-info">{{ $area->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('observers.show', $observer) }}" class="btn btn-sm btn-info ms-1">عرض</a>
<a href="{{ route('observers.edit', $observer) }}" class="btn btn-sm btn-warning ms-1">تعديل</a>
<form method="POST" action="{{ route('observers.destroy', $observer) }}" style="display:inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الراصد؟');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger ms-1">حذف</button>
</form>
<a href="{{ route('observers.activity', $observer) }}" class="btn btn-sm btn-outline-dark">سجل النشاطات</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $observers->links() }}
    </div>
</div>
@endsection
