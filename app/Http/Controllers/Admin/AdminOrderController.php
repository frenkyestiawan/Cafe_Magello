<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = Order::with(['restaurantTable', 'orderDetails.menu', 'payment']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.orders.index', compact('orders', 'status'));
    }
    
    public function show($id)
    {
        $order = Order::with(['restaurantTable', 'orderDetails.menu', 'payment'])
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,sudah_diambil,Menunggu,Diproses,Selesai,Sudah Diambil',
        ]);

        $order = Order::findOrFail($id);
        $status = strtolower(trim($request->status));
        $allowed = [
            Order::STATUS_MENUNGGU,
            Order::STATUS_DIPROSES,
            Order::STATUS_SELESAI,
            Order::STATUS_SUDAH_DIAMBI,
        ];

        if (!in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid.');
        }

        $order->status = $status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
