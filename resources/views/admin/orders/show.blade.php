@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Pesanan ' . $order->order_code . ' · ' . $order->created_at->format('d M Y H:i'))
@section('page-actions')
    <a href="{{ route('admin.orders.index') }}" class="adm-btn adm-btn--ghost">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg>Kembali
    </a>
@endsection

@section('content')
@php
    [$badge, $statusLabel] = match ($order->status) {
        'menunggu' => ['warn', $order->status_label],
        'diproses' => ['proc', $order->status_label],
        'selesai' => ['ok', 'Siap Diambil'],
        'sudah_diambil' => ['info', $order->status_label],
        default => ['', $order->status_label],
    };
@endphp

<div class="adm-stack">
    <div class="adm-grid-2">
        <section class="adm-card" aria-labelledby="customer-info-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="customer-info-title">Informasi Pelanggan</h2></div>
            <div class="adm-card-body adm-stack">
                <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                <p><strong>No. HP:</strong> {{ $order->customer_phone ?? '-' }}</p>
                <p><strong>Meja:</strong> {{ $order->restaurantTable->table_number ?? '-' }}</p>
            </div>
        </section>

        <section class="adm-card" aria-labelledby="order-status-title">
            <div class="adm-card-head">
                <h2 class="adm-card-title" id="order-status-title">Status Pesanan</h2>
                <span class="adm-badge {{ $badge ? 'adm-badge--' . $badge : '' }}">{{ $statusLabel }}</span>
            </div>
            <div class="adm-card-body">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="adm-order-status">
                @csrf
                    <label for="status" class="adm-sr-only">Ubah status pesanan</label>
                    <select id="status" name="status" class="adm-select">
                        <option value="menunggu" {{ $order->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="sudah_diambil" {{ $order->status == 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                    </select>
                    <button type="submit" class="adm-btn">
                        <svg class="adm-icon" aria-hidden="true"><use href="#i-check"/></svg>Perbarui
                    </button>
            </form>
            </div>
        </section>
    </div>

    <section class="adm-card" aria-labelledby="order-items-title">
        <div class="adm-card-head"><h2 class="adm-card-title" id="order-items-title">Item Pesanan</h2></div>
        <div class="adm-table-wrap">
            <table class="adm-table adm-table--stack">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col">Menu</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $item)
                        <tr>
                            <td data-label="Menu" class="is-strong">
                                {{ $item->menu->name }}
                                @if(!empty($item->variant_name))
                                    <span class="adm-help">({{ $item->variant_name }})</span>
                                @endif
                            </td>
                            <td data-label="Kategori" class="is-muted">{{ $item->menu->category->name ?? '-' }}</td>
                            <td data-label="Harga" class="is-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td data-label="Jumlah" class="is-muted">{{ $item->quantity }}</td>
                            <td data-label="Subtotal" class="is-muted">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td data-label="Catatan" class="is-muted">{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="adm-grid-2">
        <section class="adm-card" aria-labelledby="payment-info-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="payment-info-title">Informasi Pembayaran</h2></div>
            <div class="adm-card-body adm-stack">
            @if($order->payment)
                <p><strong>Metode:</strong> {{ $order->payment->payment_method }}</p>
                <p><strong>Status:</strong> {{ $order->payment->status }}</p>
                <p><strong>Dibayar:</strong> {{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y H:i') : '-' }}</p>
            @else
                <p class="is-muted">Belum ada informasi pembayaran</p>
            @endif
            </div>
        </section>

        <section class="adm-card" aria-labelledby="order-total-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="order-total-title">Total Pembayaran</h2></div>
            <div class="adm-card-body"><p class="adm-order-total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p></div>
        </section>
    </div>
</div>
@endsection