<?php

namespace Modules\LMS\Http\Controllers\Admin\Financial;

use App\Http\Controllers\Controller;
use Modules\LMS\Repositories\Purchase\PurchaseRepository;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $options = [];
        if ($request->has('payment_type') && $request->payment_type != 'all') {
            if ($request->payment_type == 'offline') {
                $options['whereHas'] = ['purchase', function ($query) {
                    $query->where('payment_method', 'offline');
                }];
            } else if ($request->payment_type == 'online') {
                $options['whereHas'] = ['purchase', function ($query) {
                    $query->where('payment_method', '!=', 'offline');
                }];
            }
        }

        if ($request->has('date_filter') && $request->date_filter != 'all') {
            if ($request->date_filter == 'weekly') {
                $options['where'] = ['created_at', '>=', now()->startOfWeek()];
            } else if ($request->date_filter == 'monthly') {
                $options['where'] = ['created_at', '>=', now()->startOfMonth()];
            }
        }
        
        $response = PurchaseRepository::paginate(15, relations: ['user.userable', 'course.instructors.userable', 'courseBundle', 'purchase'], options: $options);
        $sales = $response['data'] ?? [];
        $reports = PurchaseRepository::salesReports();
        return view('portal::admin.financial.sale.index', compact('sales', 'reports'));
    }


    public function invoice($id)
    {
        $response = PurchaseRepository::first($id, relations: ['user.userable', 'course.instructors.userable', 'courseBundle', 'course.organization.userable']);
        $invoice = $response['data'] ?? [];
        return view('portal::admin.financial.sale.invoice', compact('invoice'));
    }
}
