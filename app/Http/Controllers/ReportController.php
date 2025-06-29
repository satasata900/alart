<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportType;
use App\Models\OperationArea;
use App\Models\Observer;
use App\Models\ResponsePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * عرض لوحة تحكم البلاغات
     */
    public function dashboard()
    {
        // إحصائيات عامة للبلاغات
        $totalReports = Report::count();
        $todayReports = Report::whereDate('created_at', now()->toDateString())->count();
        $urgentReports = Report::where('urgency_level', 'high')->count();
        $completedReports = Report::where('status', 'completed')->count();
        
        // البلاغات الأخيرة
        $latestReports = Report::with(['reportType', 'operationArea'])
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();
        
        // البلاغات حسب النوع
        $reportTypes = ReportType::withCount('reports')->orderBy('reports_count', 'desc')->get();
        $reportsByTypeData = [
            'types' => $reportTypes->pluck('name')->toArray(),
            'counts' => $reportTypes->pluck('reports_count')->toArray()
        ];
        
        // البلاغات حسب منطقة العمليات
        $operationAreas = OperationArea::withCount('reports')->orderBy('reports_count', 'desc')->get();
        $reportsByAreaData = [
            'areas' => $operationAreas->pluck('name')->toArray(),
            'counts' => $operationAreas->pluck('reports_count')->toArray()
        ];
        
        return view('reports.dashboard', compact(
            'totalReports', 
            'todayReports', 
            'urgentReports', 
            'completedReports', 
            'latestReports',
            'reportsByTypeData', 
            'reportsByAreaData'
        ));
    }

    /**
     * عرض قائمة التقارير مع إمكانية البحث والتصفية
     */
    public function index(Request $request)
    {
        $query = Report::with(['reportType', 'operationArea', 'observer', 'responsePoint', 'attachments']);
        
        // تطبيق تصفية التقارير
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('urgency') && $request->urgency != '') {
            $query->where('urgency_level', $request->urgency);
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('report_type_id', $request->type);
        }
        
        if ($request->has('area') && $request->area != '') {
            $query->where('operation_area_id', $request->area);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('report_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('report_date', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }
        
        // نوع الترتيب
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);
        
        $reports = $query->paginate(15)->appends($request->all());
        
        // بيانات التصفية
        $reportTypes = ReportType::where('is_active', true)->get();
        $operationAreas = OperationArea::all();
        
        // حالات البلاغات ومستويات الأهمية
        $statuses = [
            'new' => 'جديد',
            'in_progress' => 'قيد المعالجة',
            'resolved' => 'تمت المعالجة',
            'closed' => 'مغلق'
        ];
        
        $urgencyLevels = [
            'normal' => 'عادي',
            'urgent' => 'عاجل',
            'emergency' => 'طارئ'
        ];
        
        return view('reports.index', compact(
            'reports', 
            'reportTypes', 
            'operationAreas', 
            'statuses', 
            'urgencyLevels', 
            'request'
        ));
    }

    /**
     * عرض نموذج إنشاء بلاغ جديد
     */
    public function create()
    {
        // جلب البيانات اللازمة لإنشاء بلاغ جديد
        $reportTypes = ReportType::where('is_active', true)->get();
        $observers = Observer::where('is_active', true)->get();
        $operationAreas = OperationArea::all();
        $responsePoints = ResponsePoint::where('is_active', true)->get();
        
        // حالات البلاغات ومستويات الأهمية
        $statuses = [
            'new' => 'جديد',
            'in_progress' => 'قيد المعالجة',
            'resolved' => 'تمت المعالجة',
            'closed' => 'مغلق'
        ];
        
        $urgencyLevels = [
            'normal' => 'عادي',
            'urgent' => 'عاجل',
            'emergency' => 'طارئ'
        ];
        
        $reporterTypes = [
            'admin' => 'مدير النظام',
            'observer' => 'راصد'
        ];
        
        return view('reports.create', compact(
            'reportTypes', 
            'observers', 
            'operationAreas', 
            'responsePoints', 
            'statuses', 
            'urgencyLevels',
            'reporterTypes'
        ));
    }

    /**
     * حفظ بلاغ جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // التحقق من معرّف النموذج الفريد
        if (!$request->has('form_token')) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ في التحقق من صحة النموذج.');
        }

        $formToken = $request->input('form_token');
        $submittedForms = session('submitted_forms', []);
        
        // التحقق من أن النموذج لم يتم إرساله من قبل
        if (in_array($formToken, $submittedForms)) {
            // إذا كان البلاغ قد تم حفظه بنجاح سابقاً، نوجه المستخدم إلى الداشبورد
            return redirect()
                ->route('reports.dashboard')
                ->with('success', 'تم إنشاء البلاغ بنجاح وإضافته إلى لوحة البلاغات');
        }
        
        // التحقق من البيانات
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'report_type_id' => 'required|exists:report_types,id',
            'status' => 'required|in:new,in_progress,resolved,closed',
            'urgency_level' => 'required|in:normal,urgent,emergency',
            'reporter_type' => 'required|in:admin,observer',
            'operation_area_id' => 'required|exists:operation_areas,id',
            'observer_id' => 'nullable|required_if:reporter_type,observer|exists:observers,id',
            'assigned_to' => 'nullable|exists:response_points,id',
            'report_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'location_data' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,txt|max:10240',
        ]);
        
        try {
            // تجهيز بيانات البلاغ
            $reportData = $request->only([
                'title',
                'description',
                'report_type_id',
                'status',
                'urgency_level',
                'reporter_type',
                'operation_area_id',
                'observer_id',
                'assigned_to',
                'notes',
            ]);
            
            // إضافة بيانات تاريخ البلاغ
            $reportData['report_date'] = $request->has('report_date') ? $request->report_date : now();
            
            // إضافة بيانات الموقع إن وجدت
            $reportData['location_data'] = $request->has('location_data') ? $request->location_data : null;
            
            // إضافة بيانات منشئ البلاغ
            $reportData['created_by'] = auth()->id() ?? 1; // مؤقتاً نستخدم 1 للمستخدم الافتراضي

            // حفظ البلاغ
            DB::beginTransaction();
            
            $report = Report::create($reportData);
            
            // معالجة المرفقات إن وجدت
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments/reports/' . $report->id, 'public');
                    
                    // تحديد نوع الملف
                    $fileType = 'document';
                    $mime = $file->getMimeType();
                    if (strpos($mime, 'image') !== false) {
                        $fileType = 'image';
                    } elseif (strpos($mime, 'video') !== false) {
                        $fileType = 'video';
                    }
                    
                    // حفظ معلومات المرفق
                    $report->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $mime,
                        'file_type' => $fileType,
                        'size' => $file->getSize(),
                        'uploaded_by' => auth()->id() ?? 1
                    ]);
                }
            }
            
            DB::commit();
            
            // تسجيل النموذج في الجلسة بعد نجاح الحفظ
            $submittedForms[] = $formToken;
            session(['submitted_forms' => $submittedForms]);
            
            return redirect()
                ->route('reports.dashboard')
                ->with('success', 'تم إنشاء البلاغ بنجاح وإضافته إلى لوحة البلاغات');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء حفظ البلاغ: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل بلاغ محدد
     */
    public function show(string $id)
    {
        $report = Report::with([
            'reportType', 
            'operationArea',
            'observer',
            'responsePoint',
            'attachments'
        ])->findOrFail($id);
        
        // نقاط الاستجابة المتاحة للتعيين
        $responsePoints = ResponsePoint::where('is_active', true)
                        ->where('operation_area_id', $report->operation_area_id)
                        ->get();
        
        // حالات البلاغات
        $statuses = [
            'new' => 'جديد',
            'in_progress' => 'قيد المعالجة',
            'resolved' => 'تمت المعالجة',
            'closed' => 'مغلق'
        ];
        
        return view('reports.show', compact('report', 'responsePoints', 'statuses'));
    }

    /**
     * عرض نموذج تعديل بلاغ
     */
    public function edit(string $id)
    {
        $report = Report::with([
            'reportType',
            'operationArea',
            'observer',
            'responsePoint',
            'attachments'
        ])->findOrFail($id);
        
        // جلب البيانات اللازمة لتعديل البلاغ
        $reportTypes = ReportType::where('is_active', true)->get();
        $observers = Observer::where('is_active', true)->get();
        $operationAreas = OperationArea::all();
        $responsePoints = ResponsePoint::where('is_active', true)->get();
        
        // حالات البلاغات ومستويات الأهمية
        $statuses = [
            'new' => 'جديد',
            'in_progress' => 'قيد المعالجة',
            'resolved' => 'تمت المعالجة',
            'closed' => 'مغلق'
        ];
        
        $urgencyLevels = [
            'normal' => 'عادي',
            'urgent' => 'عاجل',
            'emergency' => 'طارئ'
        ];
        
        $reporterTypes = [
            'admin' => 'مدير النظام',
            'observer' => 'راصد'
        ];
        
        return view('reports.edit', compact(
            'report',
            'reportTypes', 
            'observers', 
            'operationAreas', 
            'responsePoints', 
            'statuses', 
            'urgencyLevels',
            'reporterTypes'
        ));
    }

    /**
     * تحديث بيانات البلاغ
     */
    public function update(Request $request, string $id)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'report_type_id' => 'required|exists:report_types,id',
            'status' => 'required|in:new,in_progress,resolved,closed',
            'urgency_level' => 'required|in:normal,urgent,emergency',
            'reporter_type' => 'required|in:admin,observer',
            'operation_area_id' => 'required|exists:operation_areas,id',
            'observer_id' => 'nullable|required_if:reporter_type,observer|exists:observers,id',
            'assigned_to' => 'nullable|exists:response_points,id',
            'report_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'location_data' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,txt|max:10240',
        ]);
        
        try {
            // الحصول على البلاغ
            $report = Report::findOrFail($id);
            
            // تجهيز بيانات التعديل
            $reportData = $request->only([
                'title',
                'description',
                'report_type_id',
                'status',
                'urgency_level',
                'reporter_type',
                'operation_area_id',
                'observer_id',
                'assigned_to',
                'notes',
            ]);
            
            // تحديث تاريخ البلاغ
            if ($request->has('report_date')) {
                $reportData['report_date'] = $request->report_date;
            }
            
            // تحديث بيانات الموقع إن وجدت
            if ($request->has('location_data')) {
                $reportData['location_data'] = $request->location_data;
            }
            
            DB::beginTransaction();
            
            // تحديث البلاغ
            $report->update($reportData);
            
            // معالجة المرفقات الجديدة إن وجدت
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments/reports/' . $report->id, 'public');
                    
                    // تحديد نوع الملف
                    $fileType = 'document';
                    $mime = $file->getMimeType();
                    if (strpos($mime, 'image') !== false) {
                        $fileType = 'image';
                    } elseif (strpos($mime, 'video') !== false) {
                        $fileType = 'video';
                    }
                    
                    // حفظ معلومات المرفق
                    $report->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $mime,
                        'file_type' => $fileType,
                        'size' => $file->getSize(),
                        'uploaded_by' => auth()->id() ?? 1
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()
                ->route('reports.show', $report)
                ->with('success', 'تم تحديث البلاغ بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث البلاغ: ' . $e->getMessage()]);
        }
    }

    /**
     * حذف بلاغ معين (حذف ناعم)
     */
    public function destroy(string $id)
    {
        try {
            $report = Report::findOrFail($id);
            
            // استخدام الحذف الناعم لضمان عدم فقدان البيانات
            $report->delete();
            
            return redirect()
                ->route('reports.index')
                ->with('success', 'تم حذف البلاغ بنجاح');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف البلاغ: ' . $e->getMessage()]);
        }
    }
    
    /**
     * تغيير حالة البلاغ
     */
    public function changeStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
        ]);
        
        try {
            $report = Report::findOrFail($id);
            $report->status = $request->status;
            $report->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة البلاغ بنجاح',
                'status' => $request->status,
                'statusText' => Report::STATUS_COLORS[$request->status] ?? 'bg-secondary'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة البلاغ: ' . $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * تعيين البلاغ إلى نقطة استجابة
     */
    public function assign(Request $request, string $id)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:response_points,id',
        ]);
        
        try {
            $report = Report::findOrFail($id);
            $report->assigned_to = $request->assigned_to;
            $report->save();
            
            $responsePoint = ResponsePoint::find($request->assigned_to);
            
            return response()->json([
                'success' => true,
                'message' => 'تم تعيين البلاغ إلى نقطة الاستجابة بنجاح',
                'responseName' => $responsePoint ? $responsePoint->name : ''
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تعيين البلاغ: ' . $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * تحميل مرفق جديد للبلاغ
     */
    public function uploadAttachment(Request $request, string $id)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,txt|max:10240',
            'description' => 'nullable|string|max:255',
        ]);
        
        try {
            $report = Report::findOrFail($id);
            $file = $request->file('file');
            
            $path = $file->store('attachments/reports/' . $report->id, 'public');
            
            // تحديد نوع الملف
            $fileType = 'document';
            $mime = $file->getMimeType();
            if (strpos($mime, 'image') !== false) {
                $fileType = 'image';
            } elseif (strpos($mime, 'video') !== false) {
                $fileType = 'video';
            }
            
            // حفظ معلومات المرفق
            $attachment = $report->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $mime,
                'file_type' => $fileType,
                'size' => $file->getSize(),
                'description' => $request->description,
                'uploaded_by' => auth()->id() ?? 1
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحميل المرفق بنجاح',
                'attachment' => $attachment,
                'url' => asset('storage/' . $path),
                'formattedSize' => $attachment->formatted_size
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل المرفق: ' . $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * حذف مرفق من البلاغ
     */
    public function deleteAttachment(string $attachment)
    {
        try {
            $attachment = ReportAttachment::findOrFail($attachment);
            
            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
            
            // حذف السجل من قاعدة البيانات
            $attachment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المرفق بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المرفق: ' . $e->getMessage()
            ], 422);
        }
    }
}
