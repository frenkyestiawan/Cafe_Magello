@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Pesanan #{{ $order->order_code }}</h3>
            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <!-- Order Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold text-gray-800 mb-3">Informasi Pelanggan</h4>
            <p class="text-sm text-gray-600"><strong>Nama:</strong> {{ $order->customer_name }}</p>
            <p class="text-sm text-gray-600"><strong>No. HP:</strong> {{ $order->customer_phone ?? '-' }}</p>
            <p class="text-sm text-gray-600"><strong>Meja:</strong> {{ $order->restaurantTable->table_number ?? '-' }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold text-gray-800 mb-3">Status Pesanan</h4>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <div class="flex items-center space-x-2">
                    <select name="status" class="border border-gray-300 rounded px-3 py-2">
                        <option value="menunggu" {{ $order->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="sudah_diambil" {{ $order->status == 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="mb-6">
        <h4 class="font-semibold text-gray-800 mb-3">Item Pesanan</h4>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderDetails as $item)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $item->menu->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $item->menu->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Payment Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold text-gray-800 mb-3">Informasi Pembayaran</h4>
            @if($order->payment)
                <p class="text-sm text-gray-600"><strong>Metode:</strong> {{ $order->payment->payment_method }}</p>
                <p class="text-sm text-gray-600"><strong>Status:</strong> {{ $order->payment->status }}</p>
                <p class="text-sm text-gray-600"><strong>Dibayar:</strong> {{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y H:i') : '-' }}</p>
            @else
                <p class="text-sm text-gray-600">Belum ada informasi pembayaran</p>
            @endif
        </div>
        
        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold text-gray-800 mb-3">Total Pembayaran</h4>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection