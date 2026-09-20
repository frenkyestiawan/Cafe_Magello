<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::today()->startOfDay();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        
        $orders = Order::with(['restaurantTable', 'orderDetails'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->whereIn('status', [Order::STATUS_SUDAH_DIAMBI, 'Sudah Diambil'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalTransactions = $orders->count();
        $totalItemsSold = $orders->sum(function ($order) {
            return $order->orderDetails->sum('quantity');
        });
        $totalRevenue = $orders->sum('total_amount');
        
        return view('admin.reports.index', compact(
            'orders',
            'totalTransactions',
            'totalItemsSold',
            'totalRevenue',
            'startDate',
            'endDate'
        ));
    }
}
