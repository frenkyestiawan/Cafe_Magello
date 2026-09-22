@extends('admin.layouts.app')

@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <a href="{{ route('admin.menus.index') }}" class="text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Menu
        </a>
    </div>
    
    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Nama Menu</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('name', $menu->name) }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 font-medium mb-2">Kategori</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Variant Menu</label>
                    <div id="variant-list" class="space-y-3"></div>
                    <button type="button" id="add-variant" class="mt-3 text-sm text-blue-600 hover:text-blue-800">+ Tambah variant</button>
                    @error('variants')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('variants.*.name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('variants.*.price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium mb-2">Foto Menu</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @if($menu->image)
                        <p class="text-sm text-gray-500 mt-1">Foto saat ini: <a href="{{ asset('storage/' . $menu->image) }}" target="_blank" class="text-blue-600">Lihat</a></p>
                    @endif
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $menu->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available" value="1" class="mr-2"
                               {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                        <span class="text-gray-700">Tersedia</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>Update Menu
            </button>
        </div>
    </form>
</div>

<script>
    const variantNames = ['Small', 'Medium', 'Large'];
    const variantList = document.getElementById('variant-list');
    const addVariantBtn = document.getElementById('add-variant');
    const maxVariants = 3;
    const existingVariants = @json($menu->variants->map(fn ($variant) => ['name' => $variant->name, 'price' => (float) $variant->price])->values()->all());

    function buildVariantRow(name = 'Small', price = 0, index = 0) {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex flex-col md:flex-row md:items-end gap-3 rounded border border-gray-200 p-3';
        wrapper.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Variant</label>
                <select name="variants[${index}][name]" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    ${variantNames.map(option => `<option value="${option}" ${option === name ? 'selected' : ''}>${option}</option>`).join('')}
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Harga Variant</label>
                <input type="number" name="variants[${index}][price]" min="0" step="1000" value="${price}" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="button" class="remove-variant text-red-500 hover:text-red-700 px-2 py-2">Hapus</button>
        `;

        wrapper.querySelector('.remove-variant').addEventListener('click', function () {
            const rows = variantList.querySelectorAll('> div');
            if (rows.length > 1) {
                wrapper.remove();
            }
        });

        return wrapper;
    }

    function renderVariantRows() {
        variantList.innerHTML = '';
        const rows = existingVariants.length ? existingVariants : [{ name: 'Small', price: 0 }];
        rows.forEach((variant, index) => {
            variantList.appendChild(buildVariantRow(variant.name || 'Small', variant.price || 0, index));
        });

        if (rows.length < maxVariants) {
            addVariantBtn.hidden = false;
        } else {
            addVariantBtn.hidden = true;
        }
    }

    addVariantBtn.addEventListener('click', function () {
        const rows = variantList.querySelectorAll('> div');
        if (rows.length >= maxVariants) return;
        const nextIndex = rows.length;
        variantList.appendChild(buildVariantRow(variantNames[nextIndex] || 'Small', 0, nextIndex));
        if (variantList.querySelectorAll('> div').length >= maxVariants) {
            addVariantBtn.hidden = true;
        }
    });

    renderVariantRows();
</script>
@endsection