<?php

namespace Modules\LMS\Http\Controllers\Admin\Financial;

use App\Http\Controllers\Controller;
use Modules\LMS\Repositories\Purchase\PurchaseRepository;
use Modules\LMS\Enums\PurchaseType;

class OfflineSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $options = [];
        
        $options['where'] = [function ($query) use ($request) {
            $query->where('type', PurchaseType::ENROLLED)
                  ->orWhereHas('purchase', function ($q) {
                      $q->where('payment_method', 'offline');
                  });
        }];

        if ($request->has('payment_type') && $request->payment_type != 'all') {
            if ($request->payment_type == 'offline') {
                $options['where'] = [function ($query) {
                    $query->whereHas('purchase', function ($q) {
                        $q->where('payment_method', 'offline');
                    });
                }];
            } else if ($request->payment_type == 'enrolled') {
                $options['where'] = [function ($query) {
                    $query->where('type', PurchaseType::ENROLLED);
                }];
            }
        }

        if ($request->has('date_filter') && $request->date_filter != 'all') {
            if ($request->date_filter == 'weekly') {
                $options['whereDate'] = ['created_at', '>=', now()->startOfWeek()];
            } else if ($request->date_filter == 'monthly') {
                $options['whereDate'] = ['created_at', '>=', now()->startOfMonth()];
            }
        }
        
        $response = PurchaseRepository::paginate(15, relations: ['user.userable', 'course.instructors.userable', 'courseBundle', 'purchase'], options: $options);
        $sales = $response['data'] ?? [];
        
        // Compute basic reports for offline & enrolled
        $reports = [
            'total_sales' => collect($sales)->sum('price'),
            'total_platform_fee' => collect($sales)->sum('platform_fee'),
            'total_course_sale' => collect($sales)->sum('price'), // Approximate
            'total_admin_enrollment' => collect($sales)->where('type', PurchaseType::ENROLLED)->count(),
            'total_offline_payment' => collect($sales)->where('purchase.payment_method', 'offline')->count(),
        ];

        return view('portal::admin.financial.offline-sale.index', compact('sales', 'reports'));
    }
}
