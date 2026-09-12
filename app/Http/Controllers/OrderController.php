<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\RestaurantTable;

class OrderController extends Controller
{
    public function index()
    {
        $tableId = session('table_id');
        $menus = Menu::where('is_available', true)->get();
        $categories = Menu::select('category_id')->distinct()->get();

        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('order.index', compact('menus', 'categories', 'cart', 'subtotal', 'tableId'));
    }

    public function selectTable()
    {
        return view('order.select-table');
    }

    public function orderWithTable($tableNumber)
    {
        $table = RestaurantTable::where('table_number', $tableNumber)->first();

        if (!$table) {
            return redirect()->route('order.select-table')->with('error', 'Meja tidak ditemukan');
        }

        session(['table_id' => $table->id]);

        return redirect()->route('order.index')->with('success', 'Meja ' . $tableNumber . ' berhasil dipilih');
    }

    public function addToCart(Request $request)
    {
        $menuId = $request->input('menu_id');
        $menu = Menu::findOrFail($menuId);

        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => 1,
                'level' => $request->input('level'),
                'notes' => $request->input('notes'),
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Item ditambahkan ke pesanan');
    }

    public function updateCart(Request $request)
    {
        $menuId = $request->input('menu_id');
        $quantity = $request->input('quantity');

        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            if ($quantity > 0) {
                $cart[$menuId]['quantity'] = $quantity;
            } else {
                unset($cart[$menuId]);
            }
        }

        session(['cart' => $cart]);

        return redirect()->back();
    }

    public function removeFromCart($menuId)
    {
        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
        }

        session(['cart' => $cart]);

        return redirect()->back();
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.menu', 'restaurantTable'])->findOrFail($id);

        return view('order.show', compact('order'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        $tableId = session('table_id');
        if (!$tableId) {
            return redirect()->back()->with('error', 'Silakan pilih meja terlebih dahulu');
        }

        $orderCode = 'ORD' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);

        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $order = Order::create([
            'restaurant_table_id' => $tableId,
            'order_code' => $orderCode,
            'customer_name' => $request->input('customer_name'),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'payment_status' => 'unpaid',
        ]);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'level' => $item['level'] ?? null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        session(['cart' => []]);

        return redirect()->route('order.show', $order->id)->with('success', 'Pesanan berhasil dibuat');
    }

    public function setTable(Request $request)
    {
        $tableNumber = $request->input('table_number');
        $table = RestaurantTable::where('table_number', $tableNumber)->first();

        if ($table) {
            session(['table_id' => $table->id]);
            return redirect()->back()->with('success', 'Meja berhasil dipilih');
        }

        return redirect()->back()->with('error', 'Meja tidak ditemukan');
    }
}
