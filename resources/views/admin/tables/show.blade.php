@extends('admin.layouts.app')

@section('title', 'Detail Meja')
@section('page-title', 'Detail Meja')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Detail Meja #{{ $table->table_number }}</h3>
        <div class="space-x-2">
            <a href="{{ route('admin.tables.edit', $table->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.tables.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="bg-gray-50 p-4 rounded mb-4">
                <h4 class="font-semibold text-gray-800 mb-3">Informasi Meja</h4>
                <p class="text-gray-600"><strong>Nomor Meja:</strong> {{ $table->table_number }}</p>
                <p class="text-gray-600"><strong>Kapasitas:</strong> {{ $table->capacity }} orang</p>
                <p class="text-gray-600"><strong>Status:</strong> 
                    @if($table->is_available)
                        <span class="text-green-600">Tersedia</span>
                    @else
                        <span class="text-red-600">Tidak Tersedia</span>
                    @endif
                </p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold text-gray-800 mb-3">URL Pemesanan</h4>
                <p class="text-sm text-gray-600 mb-2">Pelanggan dapat mengakses pemesanan melalui:</p>
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm">{{ url('/order/table/' . $table->table_number) }}</code>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Fitur QR Code sedang dalam perbaikan. Gunakan URL di atas untuk pemesanan.
                </p>
            </div>
        </div>
        
        <div>
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold text-gray-800 mb-3">Riwayat Pesanan</h4>
                @if($table->orders->count() > 0)
                    <div class="space-y-2">
                        @foreach($table->orders->take(5) as $order)
                            <div class="bg-white p-3 rounded border">
                                <p class="text-sm font-medium text-gray-800">{{ $order->order_code }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                                <p class="text-xs text-gray-500">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                    @if($table->orders->count() > 5)
                        <p class="text-sm text-gray-500 mt-2">Dan {{ $table->orders->count() - 5 }} pesanan lainnya...</p>
                    @endif
                @else
                    <p class="text-gray-500">Belum ada pesanan untuk meja ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection