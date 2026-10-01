@extends('admin.layouts.app')

@section('title', 'Pesanan')
@section('page-title', 'Monitoring Pesanan')
@section('page-subtitle', 'Pantau pesanan dan statusnya.')
@section('page-actions')
    <form action="{{ route('admin.orders.index') }}" method="GET" class="adm-search adm-search--select">
        <label for="statusFilter" class="adm-sr-only">Filter status pesanan</label>
        <select id="statusFilter" name="status" data-autosubmit>
            <option value="">Semua Status</option>
            <option value="menunggu" {{ strtolower((string) $status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="diproses" {{ strtolower((string) $status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ strtolower((string) $status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="sudah_diambil" {{ strtolower((string) $status) == 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
        </select>
    </form>
@endsection

@section('content')
<section class="adm-card" aria-labelledby="orders-list-title">
    <div class="adm-card-head">
        <h2 class="adm-card-title" id="orders-list-title">Daftar Pesanan <span class="adm-badge">{{ $orders->total() }}</span></h2>
    </div>

    @if($orders->count() > 0)
        <div class="adm-table-wrap">
            <table class="adm-table adm-table--stack">
                <thead>
                    <tr>
                        <th scope="col">No. Pesanan</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">No. HP</th>
                        <th scope="col">Meja</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Waktu</th>
                        <th scope="col" class="is-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            [$badge, $label] = match ($order->status) {
                                'menunggu' => ['warn', $order->status_label],
                                'diproses' => ['proc', $order->status_label],
                                'selesai' => ['ok', 'Siap Diambil'],
                                'sudah_diambil' => ['info', $order->status_label],
                                default => ['', $order->status_label],
                            };
                        @endphp
                        <tr>
                            <td data-label="No. Pesanan" class="is-strong">{{ $order->order_code }}</td>
                            <td data-label="Pelanggan">{{ $order->customer_name }}</td>
                            <td data-label="No. HP" class="is-muted">{{ $order->customer_phone ?? '-' }}</td>
                            <td data-label="Meja" class="is-muted">{{ $order->restaurantTable->table_number ?? '-' }}</td>
                            <td data-label="Menu" class="is-muted">
                                {{ $order->orderDetails->pluck('menu.name')->take(2)->implode(', ') ?: '-' }}
                                @if($order->orderDetails->count() > 2)
                                    <span class="adm-help">+{{ $order->orderDetails->count() - 2 }} lainnya</span>
                                @endif
                            </td>
                            <td data-label="Jumlah" class="is-muted">{{ $order->orderDetails->sum('quantity') }}</td>
                            <td data-label="Total" class="is-muted">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td data-label="Status"><span class="adm-badge {{ $badge ? 'adm-badge--' . $badge : '' }}">{{ $label }}</span></td>
                            <td data-label="Waktu" class="is-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td data-label="Aksi" class="is-actions">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="adm-btn adm-btn--ghost adm-btn--sm">
                                    <svg class="adm-icon" aria-hidden="true"><use href="#i-eye"/></svg>Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->appends(request()->query())->links('admin.layouts.pagination') }}
    @else
        <div class="adm-empty">
            <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
            <strong>Belum ada pesanan.</strong>
            <p>Pesanan baru akan muncul di sini.</p>
        </div>
    @endif
</section>
@endsection