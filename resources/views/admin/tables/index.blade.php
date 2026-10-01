@extends('admin.layouts.app')

@section('title', 'Meja & QR Code')
@section('page-title', 'Pengelolaan Meja & QR Code')
@section('page-subtitle', 'Kelola kapasitas meja, ketersediaan, dan QR pemesanan.')
@section('page-actions')
    <a href="{{ route('admin.tables.create') }}" class="adm-btn">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-plus"/></svg>Tambah Meja
    </a>
@endsection

@section('content')
<section class="adm-card" aria-labelledby="table-list-title">
    <div class="adm-card-head">
        <h2 class="adm-card-title" id="table-list-title">Daftar Meja <span class="adm-badge">{{ $tables->total() }}</span></h2>
    </div>

    @if($tables->count() > 0)
        <div class="adm-table-wrap">
            <table class="adm-table adm-table--stack">
                <thead>
                    <tr>
                        <th scope="col">No. Meja</th>
                        <th scope="col">Kapasitas</th>
                        <th scope="col">Status</th>
                        <th scope="col">QR Code</th>
                        <th scope="col" class="is-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tables as $table)
                        <tr>
                            <td data-label="No. Meja" class="is-strong">{{ $table->table_number }}</td>
                            <td data-label="Kapasitas" class="is-muted">{{ $table->capacity }} orang</td>
                            <td data-label="Status">
                                @if($table->is_available)
                                    <span class="adm-badge adm-badge--ok">Tersedia</span>
                                @else
                                    <span class="adm-badge adm-badge--err">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td data-label="QR Code">
                                @if($table->qr_code)
                                    <span class="adm-badge adm-badge--ok"><svg class="adm-icon" aria-hidden="true"><use href="#i-check"/></svg>Ada</span>
                                @else
                                    <span class="adm-badge adm-badge--err">Tidak Ada</span>
                                @endif
                            </td>
                            <td data-label="Aksi" class="is-actions">
                                <div class="adm-row-actions">
                                <a href="{{ route('admin.tables.show', $table->id) }}" class="adm-icon-btn" aria-label="Lihat meja {{ $table->table_number }}" title="Detail">
                                    <svg class="adm-icon" aria-hidden="true"><use href="#i-eye"/></svg>
                                </a>
                                <a href="{{ route('admin.tables.edit', $table->id) }}" class="adm-icon-btn" aria-label="Edit meja {{ $table->table_number }}" title="Edit">
                                    <svg class="adm-icon" aria-hidden="true"><use href="#i-edit"/></svg>
                                </a>
                                <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" data-confirm="Meja {{ $table->table_number }} akan dihapus." data-confirm-title="Hapus meja?" data-confirm-ok="Hapus" data-confirm-danger>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adm-icon-btn" aria-label="Hapus meja {{ $table->table_number }}" title="Hapus">
                                        <svg class="adm-icon" aria-hidden="true"><use href="#i-trash"/></svg>
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $tables->links('admin.layouts.pagination') }}
    @else
        <div class="adm-empty">
            <svg class="adm-icon" aria-hidden="true"><use href="#i-grid"/></svg>
            <strong>Belum ada meja.</strong>
            <p>Tambahkan meja untuk mulai menerima pesanan dine-in.</p>
        </div>
    @endif
</section>
@endsection