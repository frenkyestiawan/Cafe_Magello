@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $adminName = auth()->user()->name ?? 'Admin';

    $stats = [
        ['label' => 'Total Pesanan Hari Ini', 'value' => $totalOrdersToday, 'icon' => 'cart',  'tone' => 'accent'],
        ['label' => 'Menunggu',               'value' => $pendingOrders,    'icon' => 'clock', 'tone' => 'warn'],
        ['label' => 'Diproses',               'value' => $processingOrders, 'icon' => 'flame', 'tone' => 'proc'],
        ['label' => 'Sudah Diambil',          'value' => $completedOrders,  'icon' => 'check', 'tone' => 'info'],
        ['label' => 'Total Pendapatan',       'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'icon' => 'money', 'tone' => 'ok', 'money' => true],
    ];
@endphp

<div class="adm-stack">

    {{-- Hero --}}
    <section class="adm-hero" aria-labelledby="hero-title">
        <div class="adm-hero-body">
            <h2 id="hero-title">Selamat datang, {{ $adminName }}!</h2>
            <p>Pantau pesanan dan kondisi Cafe Magello hari ini.</p>

            <div class="adm-hero-chips">
                <div class="adm-chip" data-weather data-lat="0.5071" data-lon="101.4478" data-place="Pekanbaru">
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-cloud"/></svg>
                    <div>
                        <strong data-weather-temp>--&deg;C</strong>
                        <small data-weather-desc>Cuaca Pekanbaru</small>
                    </div>
                </div>
                <div class="adm-chip" data-clock>
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-clock"/></svg>
                    <div>
                        <strong data-clock-time>--:--:--</strong>
                        <small data-clock-date>&nbsp;</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="adm-hero-cup" aria-hidden="true"><svg class="adm-icon"><use href="#i-coffee"/></svg></div>
    </section>

    {{-- Statistik --}}
    <section class="adm-stats" aria-label="Ringkasan hari ini">
        @foreach($stats as $stat)
            <article class="adm-stat adm-tone-{{ $stat['tone'] }}">
                <div>
                    <p class="adm-stat-label">{{ $stat['label'] }}</p>
                    <p class="adm-stat-value {{ ($stat['money'] ?? false) ? 'is-money' : '' }}">{{ $stat['value'] }}</p>
                </div>
                <span class="adm-stat-icon" aria-hidden="true"><svg class="adm-icon"><use href="#i-{{ $stat['icon'] }}"/></svg></span>
            </article>
        @endforeach
    </section>

    {{-- Pesanan terbaru --}}
    <section class="adm-card" aria-labelledby="recent-title">
        <div class="adm-card-head">
            <h3 class="adm-card-title" id="recent-title">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-receipt"/></svg>
                Pesanan Terbaru
            </h3>
            <div class="adm-toolbar">
                @if($recentOrders->count() > 0)
                    <label class="adm-search">
                        <svg class="adm-icon" aria-hidden="true"><use href="#i-search"/></svg>
                        <span class="adm-sr-only">Cari pesanan</span>
                        <input type="search" placeholder="Cari pesanan" data-filter="#recent-orders">
                    </label>
                @endif
                @if(Route::has('admin.orders.index'))
                    <a href="{{ route('admin.orders.index') }}" class="adm-link">
                        Lihat semua
                        <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-right"/></svg>
                    </a>
                @endif
            </div>
        </div>

        @if($recentOrders->count() > 0)
            <div class="adm-table-wrap">
                <table class="adm-table adm-table--stack" id="recent-orders">
                    <thead>
                        <tr>
                            <th scope="col">No. Pesanan</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Meja</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Waktu</th>
                            <th scope="col"><span class="adm-sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            @php
                                $statusLabel = $order->status_label;
                                [$badge, $statusText] = match ($order->status) {
                                    'menunggu'      => ['warn', $statusLabel],
                                    'diproses'      => ['proc', $statusLabel],
                                    'selesai'       => ['ok',   'Siap Diambil'],
                                    'sudah_diambil' => ['info', $statusLabel],
                                    default         => ['',     $statusLabel],
                                };
                            @endphp
                            <tr>
                                <td data-label="No. Pesanan" class="is-strong">{{ $order->order_code }}</td>
                                <td data-label="Pelanggan" class="is-muted">{{ $order->customer_name }}</td>
                                <td data-label="Meja" class="is-muted">{{ $order->restaurantTable->table_number ?? '-' }}</td>
                                <td data-label="Total" class="is-muted">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td data-label="Status">
                                    <span class="adm-badge {{ $badge ? 'adm-badge--' . $badge : '' }}">{{ $statusText }}</span>
                                </td>
                                <td data-label="Waktu" class="is-muted">{{ $order->created_at->format('H:i') }}</td>
                                <td class="is-actions">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="adm-btn adm-btn--ghost adm-btn--sm">
                                        <svg class="adm-icon" aria-hidden="true"><use href="#i-eye"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        <tr data-filter-empty hidden>
                            <td colspan="7" class="is-muted">Tidak ada pesanan yang cocok.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="adm-empty">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
                <strong>Belum ada pesanan.</strong>
                <p>Pesanan baru dari pelanggan akan muncul di sini.</p>
            </div>
        @endif
    </section>

</div>
@endsection
