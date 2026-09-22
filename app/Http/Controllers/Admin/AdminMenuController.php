<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->orderBy('name')->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'variants' => ['required', 'array', 'min:1', 'max:3'],
            'variants.*.name' => ['required', 'in:Small,Medium,Large', 'distinct'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
        ]);
        
        $data = $request->except(['image', 'variants']);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $data['is_available'] = $request->boolean('is_available');
        $data['price'] = $request->input('variants.0.price', 0);
        
        $menu = Menu::create($data);

        foreach ($request->input('variants', []) as $variant) {
            $menu->variants()->create([
                'name' => $variant['name'],
                'price' => $variant['price'],
                'is_available' => true,
            ]);
        }
        
        return redirect()->route('admin.menus.show', $menu->id)->with('success', 'Menu berhasil ditambahkan.');
    }
    
    public function show($id)
    {
        $menu = Menu::with('category')->findOrFail($id);
        return view('admin.menus.show', compact('menu'));
    }
    
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'variants' => ['nullable', 'array', 'min:1', 'max:3'],
            'variants.*.name' => ['required_with:variants', 'in:Small,Medium,Large', 'distinct'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
        ]);
        
        $menu = Menu::findOrFail($id);
        $data = $request->except(['image', 'variants']);
        
        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }
        
        $data['is_available'] = $request->boolean('is_available');

        if ($request->has('variants')) {
            $data['price'] = (float) $request->input('variants.0.price', $menu->price);
            $menu->variants()->delete();

            foreach ($request->input('variants', []) as $variant) {
                $menu->variants()->create([
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'is_available' => true,
                ]);
            }
        }
        
        $menu->update($data);
        
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();
        
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
