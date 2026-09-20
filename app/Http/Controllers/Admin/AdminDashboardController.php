<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::whereIn('status', [Order::STATUS_MENUNGGU, 'Menunggu'])->count();
        $processingOrders = Order::whereIn('status', [Order::STATUS_DIPROSES, 'Diproses'])->count();
        $completedOrders = Order::whereIn('status', [Order::STATUS_SELESAI, 'Selesai'])->count();
        $totalRevenue = Order::whereIn('status', [Order::STATUS_SELESAI, 'Selesai'])->sum('total_amount');
        
        $recentOrders = Order::with(['restaurantTable', 'orderDetails.menu'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard.index', compact(
            'totalOrdersToday',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
