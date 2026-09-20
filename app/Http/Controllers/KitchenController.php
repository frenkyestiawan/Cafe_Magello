<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['restaurantTable', 'orderDetails.menu'])
            ->whereNotIn('status', [Order::STATUS_SUDAH_DIAMBI, 'Sudah Diambil'])
            ->orderBy('created_at', 'desc')
            ->get();

        $waitingOrders = $orders->filter(fn ($order) => $this->normalizeStatus($order->status) === Order::STATUS_MENUNGGU);
        $processingOrders = $orders->filter(fn ($order) => $this->normalizeStatus($order->status) === Order::STATUS_DIPROSES);
        $completedOrders = $orders->filter(fn ($order) => $this->normalizeStatus($order->status) === Order::STATUS_SELESAI);

        return view('kitchen.index', compact('waitingOrders', 'processingOrders', 'completedOrders'));
    }

    public function start(Order $order): RedirectResponse
    {
        if ($this->normalizeStatus($order->status) !== Order::STATUS_MENUNGGU) {
            return back()->with('error', 'Status pesanan tidak dapat diproses saat ini.');
        }

        $order->status = Order::STATUS_DIPROSES;
        $order->save();

        return back()->with('success', 'Pesanan sedang diproses.');
    }

    public function complete(Order $order): RedirectResponse
    {
        if ($this->normalizeStatus($order->status) !== Order::STATUS_DIPROSES) {
            return back()->with('error', 'Pesanan harus dalam status Diproses sebelum diselesaikan.');
        }

        $order->status = Order::STATUS_SELESAI;
        $order->save();

        return redirect()->route('kitchen.orders.print', $order->id);
    }

    public function print(Order $order): View
    {
        $order->load(['restaurantTable', 'orderDetails.menu']);

        return view('kitchen.print', compact('order'));
    }

    public function pickup(Order $order): RedirectResponse
    {
        if ($this->normalizeStatus($order->status) !== Order::STATUS_SELESAI) {
            return back()->with('error', 'Pesanan harus sudah selesai sebelum ditandai sudah diambil.');
        }

        $order->status = Order::STATUS_SUDAH_DIAMBI;
        $order->save();

        return redirect()->route('kitchen.dashboard')->with('success', 'Pesanan telah ditandai sudah diambil.');
    }

    protected function normalizeStatus(?string $status): string
    {
        $statusMap = [
            'pending' => Order::STATUS_MENUNGGU,
            'menunggu' => Order::STATUS_MENUNGGU,
            'Menunggu' => Order::STATUS_MENUNGGU,
            'processing' => Order::STATUS_DIPROSES,
            'diproses' => Order::STATUS_DIPROSES,
            'Diproses' => Order::STATUS_DIPROSES,
            'ready' => Order::STATUS_SELESAI,
            'selesai' => Order::STATUS_SELESAI,
            'Selesai' => Order::STATUS_SELESAI,
            'completed' => Order::STATUS_SELESAI,
            'sudah_diambil' => Order::STATUS_SUDAH_DIAMBI,
            'Sudah Diambil' => Order::STATUS_SUDAH_DIAMBI,
        ];

        return $statusMap[strtolower((string) $status)] ?? strtolower((string) $status);
    }
}
