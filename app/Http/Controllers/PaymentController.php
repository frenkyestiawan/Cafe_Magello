<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function showPayment($orderId)
    {
        $order = Order::with(['restaurantTable', 'orderDetails.menu'])->findOrFail($orderId);

        // Check if payment already exists
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && $payment->status === 'paid') {
            return redirect()->route('order.show', $orderId)->with('success', 'Pembayaran sudah selesai');
        }

        return view('customers.payment', compact('order'));
    }

    public function checkStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $payment = Payment::where('order_id', $orderId)->first();

        // For demo purposes, simulate payment success
        // In production, this would check with actual payment gateway
        if (!$payment) {
            $payment = Payment::create([
                'order_id' => $orderId,
                'amount' => $order->total_amount,
                'payment_method' => 'qris',
                'status' => 'paid',
                'transaction_id' => 'TXN' . time(),
                'paid_at' => now(),
            ]);
        } else {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        // Keep kitchen workflow consistent: payment is independent from kitchen status.
        // Order should stay in the kitchen queue as waiting until staff starts it.
        $order->update([
            'payment_status' => 'paid',
            'status' => Order::STATUS_MENUNGGU,
        ]);

        return redirect()->route('order.show', $orderId)->with('success', 'Pembayaran berhasil! Pesanan Anda masuk antrian dapur.');
    }
}
