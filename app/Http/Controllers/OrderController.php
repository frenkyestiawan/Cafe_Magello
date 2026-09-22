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

        return view('customers.index', compact('menus', 'categories', 'cart', 'subtotal', 'tableId'));
    }

    public function selectTable()
    {
        return view('customers.select-table');
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

    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('order.index')->with('error', 'Keranjang kosong');
        }

        $tableId = session('table_id');
        if (!$tableId) {
            return redirect()->route('order.select-table')->with('error', 'Silakan pilih meja terlebih dahulu');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('customers.checkout', compact('cart', 'subtotal'));
    }

    public function syncCart(Request $request)
    {
        $cart = $request->input('cart', []);
        $normalized = [];

        foreach ((array) $cart as $menuId => $item) {
            $quantity = (int) ($item['quantity'] ?? $item['qty'] ?? 0);
            if ($quantity <= 0) {
                continue;
            }

            $id = (int) ($item['id'] ?? $menuId);
            $menu = Menu::find($id);
            $variantId = $item['variant_id'] ?? null;
            $variantName = $item['variant_name'] ?? null;

            $normalized[$id] = [
                'id' => $id,
                'name' => $item['name'] ?? $menu?->name ?? 'Menu',
                'price' => (float) ($item['price'] ?? $menu?->price ?? 0),
                'quantity' => $quantity,
                'variant_id' => $variantId,
                'variant_name' => $variantName,
                'level' => $item['level'] ?? null,
                'notes' => $item['notes'] ?? null,
            ];
        }

        session(['cart' => $normalized]);

        return response()->json([
            'success' => true,
            'cart' => $normalized,
        ]);
    }

    public function addToCart(Request $request)
    {
        $menuId = $request->input('menu_id');
        $menu = Menu::with('variants')->findOrFail($menuId);
        $variantId = $request->input('variant_id');
        $variant = $variantId ? $menu->variants()->find($variantId) : $menu->variants()->where('is_available', true)->orderBy('name')->first();

        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += 1;
            $cart[$menuId]['variant_id'] = $variant?->id ?? $cart[$menuId]['variant_id'] ?? null;
            $cart[$menuId]['variant_name'] = $variant?->name ?? $cart[$menuId]['variant_name'] ?? null;
            $cart[$menuId]['price'] = $variant ? (float) $variant->price : ($cart[$menuId]['price'] ?? $menu->price);
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $variant ? (float) $variant->price : (float) $menu->price,
                'quantity' => 1,
                'variant_id' => $variant?->id,
                'variant_name' => $variant?->name,
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

        return view('customers.show', compact('order'));
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

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10,13}$/',
        ]);

        $orderCode = 'ORD' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);

        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $order = Order::create([
            'restaurant_table_id' => $tableId,
            'order_code' => $orderCode,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'status' => Order::STATUS_MENUNGGU,
            'total_amount' => $totalAmount,
            'payment_status' => 'unpaid',
        ]);

        foreach ($cart as $item) {
            $variant = null;
            if (!empty($item['variant_id'])) {
                $variant = \App\Models\MenuVariant::find($item['variant_id']);
            }

            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id' => $item['id'],
                'menu_variant_id' => $variant?->id,
                'variant_name' => $item['variant_name'] ?? $variant?->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'level' => $item['level'] ?? null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        session(['cart' => []]);

        return redirect()->route('payment.show', $order->id)->with('success', 'Pesanan berhasil dibuat');
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
