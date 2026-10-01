@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Ringkasan transaksi ' . $startDate->format('d M Y') . ' sampai ' . $endDate->format('d M Y') . '.')
@section('page-actions')
    <button type="button" onclick="window.print()" class="adm-btn adm-btn--ghost adm-no-print">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-printer"/></svg>Cetak
    </button>
@endsection

@section('content')
<div class="adm-stack">
    <section class="adm-card adm-no-print" aria-labelledby="report-filter-title">
        <div class="adm-card-head"><h2 class="adm-card-title" id="report-filter-title">Filter Laporan</h2></div>
        <form action="{{ route('admin.reports.index') }}" method="GET" class="adm-card-body adm-report-filters">
            <div>
                <label for="start_date" class="adm-label">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="adm-input"
                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="end_date" class="adm-label">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" class="adm-input"
                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="payment_method" class="adm-label">Metode Pembayaran</label>
                <select id="payment_method" name="payment_method" class="adm-select">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>
            <div class="adm-report-filter-action">
                <button type="submit" class="adm-btn">
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-search"/></svg>Terapkan Filter
                </button>
            </div>
        </form>
    </section>

    <section class="adm-stats" aria-label="Ringkasan penjualan">
        <article class="adm-stat adm-tone-accent">
            <div><p class="adm-stat-label">Jumlah Transaksi</p><p class="adm-stat-value">{{ number_format($totalTransactions) }}</p></div>
            <span class="adm-stat-icon"><svg class="adm-icon" aria-hidden="true"><use href="#i-receipt"/></svg></span>
        </article>
        <article class="adm-stat adm-tone-info">
            <div><p class="adm-stat-label">Total Item Terjual</p><p class="adm-stat-value">{{ number_format($totalItemsSold) }} <small>pcs</small></p></div>
            <span class="adm-stat-icon"><svg class="adm-icon" aria-hidden="true"><use href="#i-cart"/></svg></span>
        </article>
        <article class="adm-stat adm-tone-ok">
            <div><p class="adm-stat-label">Total Pendapatan</p><p class="adm-stat-value is-money">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p></div>
            <span class="adm-stat-icon"><svg class="adm-icon" aria-hidden="true"><use href="#i-money"/></svg></span>
        </article>
        <article class="adm-stat adm-tone-warn">
            <div><p class="adm-stat-label">Rata-rata Transaksi</p><p class="adm-stat-value is-money">Rp {{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 0, ',', '.') : 0 }}</p></div>
            <span class="adm-stat-icon"><svg class="adm-icon" aria-hidden="true"><use href="#i-chart"/></svg></span>
        </article>
    </section>

    <div class="adm-grid-2">
        <section class="adm-card" aria-labelledby="top-items-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="top-items-title"><svg class="adm-icon" aria-hidden="true"><use href="#i-flame"/></svg>Menu Terlaris</h2></div>
            <div class="adm-card-body adm-stack">
                @forelse($topItems as $item)
                    <div class="adm-report-row">
                        <div class="adm-report-row-title"><span class="adm-report-rank">{{ $loop->iteration }}</span><strong>{{ $item->menu_name }}</strong></div>
                        <div class="adm-report-row-value"><strong>{{ $item->total_qty }} pcs</strong><small>Rp {{ number_format($item->total_sales, 0, ',', '.') }}</small></div>
                    </div>
                @empty
                    <div class="adm-empty"><strong>Belum ada data penjualan menu.</strong></div>
                @endforelse
            </div>
        </section>

        <section class="adm-card" aria-labelledby="payment-summary-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="payment-summary-title"><svg class="adm-icon" aria-hidden="true"><use href="#i-money"/></svg>Metode Pembayaran</h2></div>
            <div class="adm-card-body adm-stack">
                @forelse($paymentSummary as $payment)
                    <div class="adm-report-row">
                        <strong>{{ strtoupper($payment->payment_method) }}</strong>
                        <div class="adm-report-row-value"><strong>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</strong><small>{{ $payment->count }} transaksi</small></div>
                    </div>
                @empty
                    <div class="adm-empty"><strong>Belum ada data pembayaran.</strong></div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="adm-card" aria-labelledby="transactions-title">
        <div class="adm-card-head">
            <h2 class="adm-card-title" id="transactions-title">Daftar Transaksi</h2>
            <span class="adm-badge">{{ $orders->count() }} transaksi</span>
        </div>
        @if($orders->count() > 0)
            <div class="adm-table-wrap">
                <table class="adm-table adm-table--stack">
                    <thead>
                        <tr>
                            <th scope="col">No. Pesanan</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Meja</th>
                            <th scope="col">Pembayaran</th>
                            <th scope="col">Total Nominal</th>
                            <th scope="col">Tanggal &amp; Waktu</th>
                            <th scope="col" class="is-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td data-label="No. Pesanan" class="is-strong">{{ $order->order_code }}</td>
                                <td data-label="Pelanggan">{{ $order->customer_name }}</td>
                                <td data-label="Meja" class="is-muted">{{ $order->restaurantTable->table_number ?? 'Takeaway' }}</td>
                                <td data-label="Pembayaran"><span class="adm-badge">{{ strtoupper($order->payment?->payment_method ?? 'cash') }}</span></td>
                                <td data-label="Total Nominal" class="is-strong">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td data-label="Tanggal & Waktu" class="is-muted">{{ $order->created_at->format('d M Y, H:i') }}</td>
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

        @else
            <div class="adm-empty">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
                <strong>Tidak ada transaksi ditemukan pada periode ini.</strong>
            </div>
        @endif
    </section>
</div>
@endsection