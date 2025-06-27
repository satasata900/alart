@extends('layouts.app')

@section('title', isset($responsePoint) ? 'عناصر نقطة الاستجابة' : 'إدارة العناصر')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            @if(isset($responsePoint))
                عناصر نقطة الاستجابة: {{ $responsePoint->name }}
            @else
                {{ $title ?? 'جميع عناصر نقاط الاستجابة' }}
            @endif
        </h4>
        <a href="{{ route('response-points.items.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i>
            إضافة عناصر جديدة
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($items->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bx bx-package" style="font-size: 3rem;"></i>
                            </div>
                            <h6>لا توجد عناصر مضافة بعد</h6>
                            <p class="text-muted">قم بإضافة عناصر جديدة لنقطة الاستجابة</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رمز العنصر</th>
                                        <th>اسم العنصر</th>
                                        @if(!isset($responsePoint))
                                        <th>نقطة الاستجابة</th>
                                        @endif
                                        <th>رقم الجوال</th>
                                        <th>رقم الواتس</th>
                                        <th>معرف تلغرام</th>
                                        <th>الوصف</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإضافة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->name }}</td>
                                            @if(!isset($responsePoint))
                                            <td>
                                                <a href="{{ route('response-points.items.index', $item->response_point_id) }}">
                                                    {{ $item->responsePoint->name }}
                                                </a>
                                            </td>
                                            @endif
                                            <td>{{ $item->mobile }}</td>
                                            <td>{{ $item->whatsapp ?? '-' }}</td>
                                            <td>{{ $item->telegram_id ?? '-' }}</td>
                                            <td>{{ $item->description ?? '-' }}</td>
                                            <td>
                                                @if($item->is_leader)
                                                    <span class="badge bg-primary">قائد النقطة</span>
                                                @else
                                                    <span class="badge bg-secondary">عنصر</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <form action="{{ route('response-points.items.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
