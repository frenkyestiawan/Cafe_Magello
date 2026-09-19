@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">Laporan Penjualan</h3>
    
    <!-- Filter Form -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="start_date" class="block text-gray-700 font-medium mb-2">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ $startDate->format('Y-m-d') }}">
            </div>
            
            <div>
                <label for="end_date" class="block text-gray-700 font-medium mb-2">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ $endDate->format('Y-m-d') }}">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </div>
    </form>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-50 p-4 rounded">
            <p class="text-sm text-blue-600 font-medium">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-blue-800">{{ $totalTransactions }}</p>
        </div>
        
        <div class="bg-green-50 p-4 rounded">
            <p class="text-sm text-green-600 font-medium">Total Item Terjual</p>
            <p class="text-2xl font-bold text-green-800">{{ $totalItemsSold }}</p>
        </div>
        
        <div class="bg-purple-50 p-4 rounded">
            <p class="text-sm text-purple-600 font-medium">Total Pendapatan</p>
            <p class="text-2xl font-bold text-purple-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="mb-4">
        <h4 class="font-semibold text-gray-800 mb-3">Daftar Transaksi</h4>
        
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembayaran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->customer_name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->restaurantTable->table_number ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Tidak ada transaksi dalam periode ini.</p>
        @endif
    </div>
</div>
@endsection