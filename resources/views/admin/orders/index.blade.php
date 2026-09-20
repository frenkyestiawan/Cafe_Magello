@extends('admin.layouts.app')

@section('title', 'Pesanan')
@section('page-title', 'Monitoring Pesanan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Pesanan</h3>
        
        <div class="flex space-x-2">
            <select id="statusFilter" onchange="filterByStatus()" class="border border-gray-300 rounded px-3 py-2">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ strtolower((string) $status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ strtolower((string) $status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ strtolower((string) $status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="sudah_diambil" {{ strtolower((string) $status) == 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
            </select>
        </div>
    </div>
    
    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->customer_phone ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->restaurantTable->table_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $order->orderDetails->pluck('menu.name')->take(2)->implode(', ') }}
                                @if($order->orderDetails->count() > 2)
                                    <span class="text-gray-400">+{{ $order->orderDetails->count() - 2 }} lainnya</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->orderDetails->sum('quantity') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @php
                                    $label = $order->status_label;
                                @endphp

                                @if($order->status === 'menunggu')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $label }}</span>
                                @elseif($order->status === 'diproses')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $label }}</span>
                                @elseif($order->status === 'selesai')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $label }}</span>
                                @elseif($order->status === 'sudah_diambil')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $label }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $label }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <p class="text-gray-500 text-center py-4">Belum ada pesanan.</p>
    @endif
</div>

<script>
function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}
</script>
@endsection