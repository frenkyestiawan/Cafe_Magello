<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;

class AdminTableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::orderBy('table_number')->paginate(10);
        return view('admin.tables.index', compact('tables'));
    }
    
    public function create()
    {
        return view('admin.tables.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);
        
        $data = $request->all();
        $data['is_available'] = $request->has('is_available');
        
        $table = RestaurantTable::create($data);
        
        // Generate QR Code (disabled temporarily due to library compatibility issues)
        // QR Code will be added later with a different approach
        $table->update(['qr_code' => null]);
        
        \Storage::disk('public')->put($qrCodePath, $qrCode);
        
        $table->update(['qr_code' => $qrCodePath]);
        
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }
    
    public function show($id)
    {
        $table = RestaurantTable::with('orders')->findOrFail($id);
        return view('admin.tables.show', compact('table'));
    }
    
    public function edit($id)
    {
        $table = RestaurantTable::findOrFail($id);
        return view('admin.tables.edit', compact('table'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number,' . $id,
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);
        
        $table = RestaurantTable::findOrFail($id);
        $data = $request->all();
        $data['is_available'] = $request->has('is_available');
        
        // QR Code generation disabled temporarily
        // if ($table->table_number !== $request->table_number) {
        //     QR Code regeneration logic will be added later
        // }
        
        $table->update($data);
        
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $table = RestaurantTable::findOrFail($id);
        
        if ($table->qr_code) {
            \Storage::disk('public')->delete($table->qr_code);
        }
        
        $table->delete();
        
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }
    
    public function regenerateQrCode($id)
    {
        // QR Code generation disabled temporarily due to library compatibility issues
        // This feature will be implemented with a different approach
        return redirect()->back()->with('info', 'Fitur QR Code sedang dalam perbaikan. Silakan gunakan nomor meja untuk pemesanan.');
    }
}
