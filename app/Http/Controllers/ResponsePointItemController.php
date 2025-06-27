<?php

namespace App\Http\Controllers;

use App\Models\ResponsePoint;
use App\Models\ResponsePointItem;
use Illuminate\Http\Request;

class ResponsePointItemController extends Controller
{
    /**
     * عرض صفحة إضافة عناصر جديدة لنقطة استجابة
     */
    public function create()
    {
        $responsePoints = ResponsePoint::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
            
        return view('response-points.items.create', compact('responsePoints'));
    }

    /**
     * حفظ العناصر الجديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'response_point_id' => 'required|exists:response_points,id',
            'items' => 'required|array',
            'items.*.code' => 'required|string|max:50|unique:response_point_items,code',
            'items.*.name' => 'required|string|max:255',
            'items.*.mobile' => 'required|string|max:20',
            'items.*.whatsapp' => 'nullable|string|max:20',
            'items.*.telegram_id' => 'nullable|string|max:255',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.is_leader' => 'nullable|boolean',
        ]);

        foreach ($request->items as $item) {
            ResponsePointItem::create([
                'response_point_id' => $request->response_point_id,
                'code' => $item['code'],
                'name' => $item['name'],
                'mobile' => $item['mobile'],
                'whatsapp' => $item['whatsapp'] ?? null,
                'telegram_id' => $item['telegram_id'] ?? null,
                'description' => $item['description'] ?? null,
                'is_leader' => $item['is_leader'] ?? false,
            ]);
        }

        return redirect()
            ->route('response-points.items.index', $request->response_point_id)
            ->with('success', 'تم إضافة العناصر بنجاح');
    }

    /**
     * عرض عناصر نقطة استجابة معينة
     */
    /**
     * البحث عن نقاط الاستجابة بالاسم
     */
    public function searchPoints(Request $request)
    {
        $search = $request->get('q');
        $points = ResponsePoint::where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%");
            })
            ->where('is_active', true)
            ->take(10)
            ->get(['id', 'name', 'code']);

        return response()->json($points);
    }

    public function index($responsePoint)
    {
        // إذا كان المعلم هو 'all'، فسنعرض كل العناصر من جميع نقاط الاستجابة
        if ($responsePoint === 'all') {
            $items = ResponsePointItem::with('responsePoint')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $title = 'جميع العناصر';
            return view('response-points.items.index', compact('items', 'title'));
        } else {
            // عرض العناصر لنقطة استجابة محددة
            $responsePoint = ResponsePoint::findOrFail($responsePoint);
            $items = $responsePoint->items()->paginate(10);
            return view('response-points.items.index', compact('responsePoint', 'items'));
        }
    }

    /**
     * حذف عنصر معين
     */
    public function destroy(ResponsePointItem $item)
    {
        $responsePointId = $item->response_point_id;
        $item->delete();

        return redirect()
            ->route('response-points.items.index', $responsePointId)
            ->with('success', 'تم حذف العنصر بنجاح');
    }
}
