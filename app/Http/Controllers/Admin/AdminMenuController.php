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
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);
        
        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }
        
        $data['is_available'] = $request->has('is_available');
        
        Menu::create($data);
        
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
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
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);
        
        $menu = Menu::findOrFail($id);
        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }
        
        $data['is_available'] = $request->has('is_available');
        
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
