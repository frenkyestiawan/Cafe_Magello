<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Http;

class AdminTableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::orderBy('table_number')->paginate(10);
        return view('admin.tables.index', compact('tables'));
    }
    
    public function create()
    {
        // Auto-generate next table number
        $lastTable = RestaurantTable::orderBy('table_number', 'desc')->first();
        $nextNumber = '01';
        
        if ($lastTable) {
            $lastNumber = intval($lastTable->table_number);
            $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        }
        
        return view('admin.tables.create', compact('nextNumber'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number|regex:/^[0-9]+$/',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);
        
        // Format table number to ensure it's 2 digits
        $tableNumber = str_pad(intval($request->table_number), 2, '0', STR_PAD_LEFT);
        
        $data = $request->all();
        $data['table_number'] = $tableNumber;
        $data['is_available'] = $request->has('is_available');
        
        $table = RestaurantTable::create($data);
        
        // Generate QR Code using online API
        $qrCodeUrl = 'http://127.0.0.1:8000/order/table/' . $table->table_number;
        $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCodeUrl);
        
        // Save the QR Code API URL for display
        $table->update(['qr_code' => $qrCodeApiUrl]);
        
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
            'table_number' => 'required|string|unique:restaurant_tables,table_number,' . $id . '|regex:/^[0-9]+$/',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);
        
        $table = RestaurantTable::findOrFail($id);
        
        // Format table number to ensure it's 2 digits
        $tableNumber = str_pad(intval($request->table_number), 2, '0', STR_PAD_LEFT);
        
        $data = $request->all();
        $data['table_number'] = $tableNumber;
        $data['is_available'] = $request->has('is_available');
        
        // Regenerate QR Code if table number changed
        if ($table->table_number !== $tableNumber) {
            $qrCodeUrl = 'http://127.0.0.1:8000/order/table/' . $tableNumber;
            $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCodeUrl);
            $data['qr_code'] = $qrCodeApiUrl;
        }
        
        $table->update($data);
        
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $table = RestaurantTable::findOrFail($id);
        $table->delete();
        
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }
    
    public function regenerateQrCode($id)
    {
        $table = RestaurantTable::findOrFail($id);
        
        $qrCodeUrl = 'http://127.0.0.1:8000/order/table/' . $table->table_number;
        $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCodeUrl);
        
        $table->update(['qr_code' => $qrCodeApiUrl]);
        
        return redirect()->back()->with('success', 'QR Code berhasil diperbarui.');
    }
}
