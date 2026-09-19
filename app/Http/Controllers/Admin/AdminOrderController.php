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
            'status' => 'required|in:Menunggu,Diproses,Selesai,Sudah Diambil',
        ]);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
