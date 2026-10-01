<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_method' => ['nullable', 'in:cash,qris,transfer'],
        ]);

        $startDate = Carbon::parse($filters['start_date'] ?? Carbon::today()->toDateString())->startOfDay();
        $endDate = Carbon::parse($filters['end_date'] ?? Carbon::today()->toDateString())->endOfDay();

        $query = Order::with(['restaurantTable', 'orderDetails.menu', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->whereIn('status', [Order::STATUS_SUDAH_DIAMBI, 'Sudah Diambil']);

        if (! empty($filters['payment_method'])) {
            $query->whereHas('payment', function ($paymentQuery) use ($filters) {
                $paymentQuery->where('payment_method', $filters['payment_method']);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $totalTransactions = $orders->count();
        $totalItemsSold = $orders->sum(function ($order) {
            return $order->orderDetails->sum('quantity');
        });
        $totalRevenue = $orders->sum('total_amount');

        $topItems = $orders
            ->flatMap(fn ($order) => $order->orderDetails)
            ->groupBy(fn ($detail) => $detail->menu?->name ?? 'Menu')
            ->map(fn ($items, $name) => (object) [
                'menu_name' => $name,
                'total_qty' => $items->sum('quantity'),
                'total_sales' => $items->sum('subtotal'),
            ])
            ->sortByDesc('total_qty')
            ->take(5)
            ->values();

        $paymentSummary = $orders
            ->groupBy(fn ($order) => $order->payment?->payment_method ?? 'cash')
            ->map(fn ($group, $method) => (object) [
                'payment_method' => $method,
                'total_amount' => $group->sum('total_amount'),
                'count' => $group->count(),
            ])
            ->values();

        return view('admin.reports.index', compact(
            'orders',
            'totalTransactions',
            'totalItemsSold',
            'totalRevenue',
            'topItems',
            'paymentSummary',
            'startDate',
            'endDate'
        ));
    }
}
