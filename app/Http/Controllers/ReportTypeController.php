<?php

namespace App\Http\Controllers;

use App\Models\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReportTypeController extends Controller
{
    /**
     * عرض قائمة أنواع البلاغات
     */
    public function index()
    {
        $reportTypes = ReportType::withCount('reports')
            ->latest()
            ->paginate(10);

        return view('report-types.index', compact('reportTypes'));
    }

    /**
     * عرض نموذج إضافة نوع بلاغ جديد
     */
    public function create()
    {
        return view('report-types.create');
    }

    /**
     * حفظ نوع بلاغ جديد
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'اسم نوع البلاغ مطلوب',
            'name.max' => 'اسم نوع البلاغ يجب ألا يتجاوز 255 حرفاً',
            'code.required' => 'رمز نوع البلاغ مطلوب',
            'code.max' => 'رمز نوع البلاغ يجب ألا يتجاوز 50 حرفاً',
            'code.unique' => 'رمز نوع البلاغ مستخدم بالفعل، الرجاء اختيار رمز آخر',
            'color.required' => 'لون نوع البلاغ مطلوب',
            'color.regex' => 'صيغة اللون غير صحيحة، يجب أن تكون بصيغة #RRGGBB'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:report_types,code',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'is_active' => 'boolean'
        ], $messages);

        ReportType::create($validated);

        return redirect()
            ->route('report-types.index')
            ->with('success', 'تم إضافة نوع البلاغ بنجاح');
    }

    /**
     * عرض نموذج تعديل نوع بلاغ
     */
    public function edit(ReportType $reportType)
    {
        return view('report-types.edit', compact('reportType'));
    }

    /**
     * تحديث نوع بلاغ
     */
    public function update(Request $request, ReportType $reportType)
    {
        $messages = [
            'name.required' => 'اسم نوع البلاغ مطلوب',
            'name.max' => 'اسم نوع البلاغ يجب ألا يتجاوز 255 حرفاً',
            'code.required' => 'رمز نوع البلاغ مطلوب',
            'code.max' => 'رمز نوع البلاغ يجب ألا يتجاوز 50 حرفاً',
            'code.unique' => 'رمز نوع البلاغ مستخدم بالفعل، الرجاء اختيار رمز آخر',
            'color.required' => 'لون نوع البلاغ مطلوب',
            'color.regex' => 'صيغة اللون غير صحيحة، يجب أن تكون بصيغة #RRGGBB'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('report_types')->ignore($reportType)],
            'description' => 'nullable|string',
            'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'is_active' => 'boolean'
        ], $messages);

        $reportType->update($validated);

        return redirect()
            ->route('report-types.index')
            ->with('success', 'تم تحديث نوع البلاغ بنجاح');
    }

    /**
     * حذف نوع بلاغ
     */
    public function destroy(ReportType $reportType)
    {
        // التحقق من عدم وجود بلاغات مرتبطة
        if ($reportType->reports()->exists()) {
            return back()->with('error', 'لا يمكن حذف هذا النوع لوجود بلاغات مرتبطة به');
        }

        $reportType->delete();

        return redirect()
            ->route('report-types.index')
            ->with('success', 'تم حذف نوع البلاغ بنجاح');
    }

    /**
     * تفعيل/تعطيل نوع بلاغ
     */
    public function toggle(ReportType $reportType)
    {
        $reportType->update(['is_active' => !$reportType->is_active]);

        $status = $reportType->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} نوع البلاغ بنجاح");
    }

}
