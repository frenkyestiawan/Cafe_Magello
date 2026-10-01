@extends('admin.layouts.app')

@section('title', 'Detail Meja')
@section('page-title', 'Meja ' . $table->table_number)
@section('page-subtitle', 'Detail kapasitas, QR pemesanan, dan riwayat meja.')
@section('page-actions')
    <a href="{{ route('admin.tables.edit', $table->id) }}" class="adm-btn">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-edit"/></svg>Edit Meja
    </a>
    <a href="{{ route('admin.tables.index') }}" class="adm-btn adm-btn--ghost">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg>Kembali
    </a>
@endsection

@section('content')
<div class="adm-stack">
    <div class="adm-grid-2">
        <section class="adm-card" aria-labelledby="table-info-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="table-info-title">Informasi Meja</h2></div>
            <div class="adm-card-body adm-stack">
                <p><strong>Nomor Meja:</strong> {{ $table->table_number }}</p>
                <p><strong>Kapasitas:</strong> {{ $table->capacity }} orang</p>
                <p><strong>Status:</strong>
                    @if($table->is_available)
                        <span class="adm-badge adm-badge--ok">Tersedia</span>
                    @else
                        <span class="adm-badge adm-badge--err">Tidak Tersedia</span>
                    @endif
                </p>
            </div>
        </section>

        <section class="adm-card" aria-labelledby="table-qr-title">
            <div class="adm-card-head"><h2 class="adm-card-title" id="table-qr-title">QR Code</h2></div>
            <div class="adm-card-body adm-qr-panel">
                @if($table->qr_code)
                    <img src="{{ $table->qr_code }}" alt="QR pemesanan untuk meja {{ $table->table_number }}" class="adm-qr-image" loading="lazy">
                    <p class="adm-help">Pindai QR ini untuk membuka pemesanan meja {{ $table->table_number }}.</p>
                    <div class="adm-qr-actions">
                            <form action="{{ route('admin.tables.regenerate-qr', $table->id) }}" method="POST" data-confirm="QR Code meja {{ $table->table_number }} akan dibuat ulang." data-confirm-title="Perbarui QR Code?" data-confirm-ok="Perbarui">
                                @csrf
                                <button type="submit" class="adm-btn adm-btn--ghost">
                                    <svg class="adm-icon" aria-hidden="true"><use href="#i-qr"/></svg>Perbarui QR Code
                                </button>
                            </form>
                            <a href="{{ $table->qr_code }}" download="qr_code_{{ $table->table_number }}.png" class="adm-btn">
                                <svg class="adm-icon" aria-hidden="true"><use href="#i-qr"/></svg>Download QR Code
                            </a>
                    </div>
                @else
                    <div class="adm-empty">
                        <svg class="adm-icon" aria-hidden="true"><use href="#i-qr"/></svg>
                        <strong>QR Code belum dibuat.</strong>
                    </div>
                    <form action="{{ route('admin.tables.regenerate-qr', $table->id) }}" method="POST" class="adm-qr-actions">
                        @csrf
                        <button type="submit" class="adm-btn">
                            <svg class="adm-icon" aria-hidden="true"><use href="#i-qr"/></svg>Buat QR Code
                        </button>
                    </form>
                @endif
            </div>
    </div>

    <section class="adm-card" aria-labelledby="table-url-title">
        <div class="adm-card-head"><h2 class="adm-card-title" id="table-url-title">URL Pemesanan</h2></div>
        <div class="adm-card-body">
            <p class="adm-help">Pelanggan dapat membuka tautan ini untuk memesan dari meja.</p>
            <code class="adm-code-block">{{ url('/order/table/' . $table->table_number) }}</code>
        </div>
    </section>

    <section class="adm-card" aria-labelledby="table-orders-title">
        <div class="adm-card-head"><h2 class="adm-card-title" id="table-orders-title">Riwayat Pesanan</h2></div>
        @if($table->orders->count() > 0)
            <div class="adm-card-body adm-stack">
                @foreach($table->orders->take(5) as $order)
                    <article class="adm-order-history">
                        <div>
                            <strong>{{ $order->order_code }}</strong>
                            <p class="adm-help">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </article>
                @endforeach
                @if($table->orders->count() > 5)
                    <p class="adm-help">Dan {{ $table->orders->count() - 5 }} pesanan lainnya.</p>
                @endif
            </div>
        @else
            <div class="adm-empty">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
                <strong>Belum ada pesanan untuk meja ini.</strong>
            </div>
        @endif
    </section>
</div>
@endsection