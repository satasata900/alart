@extends('layouts.app')

@section('title', 'تعديل عضو فريق استجابة')

@section('styles')
<style>
    .required-field::after {
        content: " *";
        color: red;
    }
</style>
@endsection

@section('content')
<div class="alert alert-info text-center mt-5">تم حذف قسم فريق الاستجابة من النظام نهائياً.</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تعديل عضو فريق استجابة</h5>
                <a href="{{ route('response-team-members.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> العودة للقائمة
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('response-team-members.update', $responseTeamMember->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label required-field">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $responseTeamMember->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="username" class="form-label required-field">اسم المستخدم</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $responseTeamMember->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">كلمة المرور (اترك فارغاً للإبقاء على كلمة المرور الحالية)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="response_point_id" class="form-label required-field">نقطة الاستجابة</label>
                            <select class="form-select @error('response_point_id') is-invalid @enderror" id="response_point_id" name="response_point_id" required>
                                <option value="">-- اختر نقطة الاستجابة --</option>
                                @foreach($responsePoints as $point)
                                    <option value="{{ $point->id }}" {{ old('response_point_id', $responseTeamMember->response_point_id) == $point->id ? 'selected' : '' }}>
                                        {{ $point->name }} ({{ $point->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('response_point_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="rank" class="form-label">الرتبة</label>
                            <input type="text" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ old('rank', $responseTeamMember->rank) }}">
                            @error('rank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $responseTeamMember->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label">رقم الواتساب</label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $responseTeamMember->whatsapp) }}">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_leader" name="is_leader" value="1" {{ old('is_leader', $responseTeamMember->is_leader) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_leader">
                                    قائد الفريق
                                </label>
                            </div>
                            <div class="form-text text-warning">
                                <i class="bx bx-info-circle"></i>
                                تعيين هذا العضو كقائد للفريق سيلغي حالة القيادة عن أي عضو آخر في نفس نقطة الاستجابة
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $responseTeamMember->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    عضو نشط
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            <a href="{{ route('response-team-members.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
