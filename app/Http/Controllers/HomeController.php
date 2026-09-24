<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $favoriteMenus = Menu::with(['category', 'variants'])
            ->where('is_available', true)
            ->withSum('orderDetails', 'quantity')
            ->orderByDesc('order_details_sum_quantity')
            ->orderBy('name')
            ->take(8)
            ->get();

        // Fallback jika belum ada pemesanan sama sekali (total order quantity == 0)
        if ($favoriteMenus->isEmpty() || (int) $favoriteMenus->sum('order_details_sum_quantity') === 0) {
            $favoriteMenus = Menu::with(['category', 'variants'])
                ->where('is_available', true)
                ->where('is_best_seller', true)
                ->orderBy('name')
                ->take(8)
                ->get();

            if ($favoriteMenus->isEmpty()) {
                $favoriteMenus = Menu::with(['category', 'variants'])
                    ->where('is_available', true)
                    ->orderBy('name')
                    ->take(8)
                    ->get();
            }
        }

        $menus = $favoriteMenus->map(function ($menu) {
            $imageUrl = !empty($menu->image_url) ? $menu->image_url : (!empty($menu->image) ? Storage::url($menu->image) : '');
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'category' => $menu->category?->name ?? 'Lainnya',
                'desc' => $menu->description ?? 'Menu pilihan Magello.',
                'image' => $imageUrl,
                'badge' => $menu->is_best_seller ? 'Best Seller' : null,
                'eta' => (int) ($menu->prep_time ?? 10),
                'variants' => $menu->variants->where('is_available', true)->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'price' => (float) $variant->price,
                    ];
                })->values()->all(),
            ];
        })->all();

        $categoryList = Category::whereHas('menus', function ($query) {
                $query->where('is_available', true);
            })
            ->orderBy('name')
            ->pluck('name')
            ->all();

        $categories = array_merge(['Semua'], $categoryList);

        return view('home', [
            'trackUrl' => route('order.index'),
            'tableNo' => '',
            'openTime' => '09:00',
            'closeTime' => '23:00',
            'kitchenBusy' => false,
            'categories' => $categories,
            'menus' => $menus,
        ]);
    }
}
