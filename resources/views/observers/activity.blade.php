@extends('layouts.app')
@section('title', 'سجل نشاطات الراصد')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">سجل نشاطات الراصد: {{ $observer->name }}</h2>
    <a href="{{ route('observers.index') }}" class="btn btn-secondary mb-3">&larr; عودة لقائمة الراصدين</a>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>العملية</th>
                    <th>الوصف</th>
                    <th>IP</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->created_at ? date('Y-m-d H:i', strtotime($log->created_at)) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
