@extends('admin.layouts.app')

@section('title', 'Menu')
@section('page-title', 'Pengelolaan Menu')
@section('page-subtitle', 'Atur katalog menu dan ketersediaannya.')
@section('page-actions')
    <a href="{{ route('admin.menus.create') }}" class="adm-btn">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-plus"/></svg>
        Tambah Menu
    </a>
@endsection

@section('content')
<section class="adm-card" aria-labelledby="menu-list-title">
    <div class="adm-card-head">
        <h2 class="adm-card-title" id="menu-list-title">Daftar Menu <span class="adm-badge">{{ $menus->total() }}</span></h2>
    </div>

    @if($menus->count() > 0)
        <div class="adm-table-wrap">
            <table class="adm-table adm-table--stack">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col">Gambar</th>
                        <th scope="col">Nama Menu</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="is-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td data-label="Gambar">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="adm-menu-thumb" loading="lazy">
                                @else
                                    <div class="adm-menu-thumb adm-menu-thumb--empty" aria-label="Tidak ada gambar">
                                        <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Nama Menu" class="is-strong">{{ $menu->name }}</td>
                            <td data-label="Kategori" class="is-muted">{{ $menu->category->name ?? '-' }}</td>
                            <td data-label="Harga" class="is-muted">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                @if($menu->is_available)
                                    <span class="adm-badge adm-badge--ok">Tersedia</span>
                                @else
                                    <span class="adm-badge adm-badge--err">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td data-label="Aksi" class="is-actions">
                                <div class="adm-row-actions">
                                <a href="{{ route('admin.menus.show', $menu->id) }}" class="adm-icon-btn" aria-label="Lihat {{ $menu->name }}" title="Lihat">
                                    <svg class="adm-icon" aria-hidden="true"><use href="#i-eye"/></svg>
                                </a>
                                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="adm-icon-btn" aria-label="Edit {{ $menu->name }}" title="Edit">
                                    <svg class="adm-icon" aria-hidden="true"><use href="#i-edit"/></svg>
                                </a>
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" data-confirm="Menu {{ $menu->name }} akan dihapus." data-confirm-title="Hapus menu?" data-confirm-ok="Hapus" data-confirm-danger>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adm-icon-btn" aria-label="Hapus {{ $menu->name }}" title="Hapus">
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
        {{ $menus->links('admin.layouts.pagination') }}
    @else
        <div class="adm-empty">
            <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
            <strong>Belum ada menu.</strong>
            <p>Tambahkan menu untuk mulai mengisi katalog.</p>
        </div>
    @endif
</section>
@endsection