@extends('admin.layouts.app')

@section('title', 'Detail Menu')
@section('page-title', 'Detail Menu')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Detail Menu</h3>
        <div class="space-x-2">
            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.menus.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-64 object-cover rounded-lg">
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                </div>
            @endif
        </div>
        
        <div>
            <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ $menu->name }}</h4>
            <p class="text-xl text-blue-600 font-semibold mb-4">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
            
            <div class="space-y-2">
                <p class="text-gray-600"><strong>Kategori:</strong> {{ $menu->category->name ?? '-' }}</p>
                <p class="text-gray-600"><strong>Status:</strong> 
                    @if($menu->is_available)
                        <span class="text-green-600">Tersedia</span>
                    @else
                        <span class="text-red-600">Tidak Tersedia</span>
                    @endif
                </p>
                <p class="text-gray-600"><strong>Deskripsi:</strong> {{ $menu->description ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection