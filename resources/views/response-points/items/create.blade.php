@extends('layouts.app')

@section('title', 'إضافة عناصر جديدة')

@push('styles')
<style>
    .form-select {
        height: 38px;
        padding: 6px;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">إضافة عناصر جديدة</h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="itemsForm" action="{{ route('response-points.items.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label d-block">نقطة الاستجابة</label>
                            <select name="response_point_id" id="response_point_id" class="form-select @error('response_point_id') is-invalid @enderror" required>
                                <option value="">اختر نقطة الاستجابة</option>
                                @foreach($responsePoints as $point)
                                <option value="{{ $point->id }}">{{ $point->name }} ({{ $point->code }})</option>
                                @endforeach
                            </select>
                            @error('response_point_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="itemsContainer">
                            <div class="item-row row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">رمز العنصر</label>
                                    <input type="text" name="items[0][code]" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">اسم العنصر</label>
                                    <input type="text" name="items[0][name]" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">رقم الجوال</label>
                                    <input type="text" name="items[0][mobile]" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">رقم الواتس</label>
                                    <input type="text" name="items[0][whatsapp]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">معرف تلغرام</label>
                                    <input type="text" name="items[0][telegram_id]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">الوصف</label>
                                    <input type="text" name="items[0][description]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="items[0][is_leader]" class="form-check-input" value="1">
                                        <label class="form-check-label">قائد النقطة</label>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" style="display: none;">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="addItem" class="btn btn-secondary">
                                <i class="bx bx-plus"></i>
                                إضافة عنصر آخر
                            </button>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">حفظ العناصر</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemCount = 0;
        const container = document.getElementById('itemsContainer');
        const addButton = document.getElementById('addItem');

        addButton.addEventListener('click', function() {
            itemCount++;
            const template = `
                <div class="item-row row mb-3">
                    <div class="col-md-2">
                        <label class="form-label">رمز العنصر</label>
                        <input type="text" name="items[${itemCount}][code]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">اسم العنصر</label>
                        <input type="text" name="items[${itemCount}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">رقم الجوال</label>
                        <input type="text" name="items[${itemCount}][mobile]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">رقم الواتس</label>
                        <input type="text" name="items[${itemCount}][whatsapp]" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">معرف تلغرام</label>
                        <input type="text" name="items[${itemCount}][telegram_id]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الوصف</label>
                        <input type="text" name="items[${itemCount}][description]" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="items[${itemCount}][is_leader]" class="form-check-input" value="1">
                            <label class="form-check-label">قائد النقطة</label>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);

            // Show remove button for first row if we have more than one row
            if (itemCount === 1) {
                container.querySelector('.remove-item').style.display = 'block';
            }
        });

        // Event delegation for remove buttons
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const row = e.target.closest('.item-row');
                row.remove();
                
                // Hide remove button for first row if we only have one row
                const rows = container.querySelectorAll('.item-row');
                if (rows.length === 1) {
                    rows[0].querySelector('.remove-item').style.display = 'none';
                }
            }
        });
    });
</script>
@endpush
@endsection
