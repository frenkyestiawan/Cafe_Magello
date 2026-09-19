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
        $pendingOrders = Order::where('status', 'Menunggu')->count();
        $processingOrders = Order::where('status', 'Diproses')->count();
        $completedOrders = Order::where('status', 'Selesai')->count();
        $totalRevenue = Order::where('status', 'Selesai')->sum('total_amount');
        
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
