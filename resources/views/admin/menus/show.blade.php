@extends('admin.layouts.app')

@section('title', 'Detail Menu')
@section('page-title', 'Detail Menu')
@section('page-subtitle', 'Informasi lengkap menu dan status ketersediaannya.')
@section('page-actions')
    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="adm-btn">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-edit"/></svg>Edit Menu
    </a>
    <a href="{{ route('admin.menus.index') }}" class="adm-btn adm-btn--ghost">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg>Kembali
    </a>
@endsection

@section('content')
<section class="adm-card">
    <div class="adm-card-body adm-menu-detail">
        <div>
            @if($menu->image)
                <div class="adm-menu-preview">
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                </div>
            @else
                <div class="adm-menu-preview" role="img" aria-label="Tidak ada gambar untuk {{ $menu->name }}">
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-inbox"/></svg>
                </div>
            @endif
        </div>

        <div class="adm-stack">
            <div>
                <h2>{{ $menu->name }}</h2>
                <p class="adm-menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="adm-label">Kategori</span>
                <p>{{ $menu->category->name ?? '-' }}</p>
            </div>
            <div>
                <span class="adm-label">Status</span>
                <p>
                    @if($menu->is_available)
                        <span class="adm-badge adm-badge--ok">Tersedia</span>
                    @else
                        <span class="adm-badge adm-badge--err">Tidak Tersedia</span>
                    @endif
                </p>
            </div>
            <div>
                <span class="adm-label">Deskripsi</span>
                <p>{{ $menu->description ?: '-' }}</p>
            </div>
        </div>
    </div>
</section>
@endsection