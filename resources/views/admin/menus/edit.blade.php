@extends('admin.layouts.app')

@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu')
@section('page-subtitle', 'Perbarui detail menu dan pilihan variannya.')
@section('page-actions')
    <a href="{{ route('admin.menus.index') }}" class="adm-btn adm-btn--ghost">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg>
        Kembali
    </a>
@endsection

@section('content')
<section class="adm-card">
    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="adm-card-body adm-form">
        @csrf
        @method('PUT')

        <div class="adm-form-grid">
            <div>
                <label for="name" class="adm-label">Nama Menu</label>
                <input type="text" id="name" name="name" class="adm-input {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $menu->name) }}" required>
                @error('name') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="adm-label">Kategori</label>
                <select id="category_id" name="category_id" class="adm-select {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <div class="is-full">
                <span class="adm-label">Varian Menu</span>
                <div id="variant-list" class="adm-variant-list"></div>
                <button type="button" id="add-variant" class="adm-btn adm-btn--ghost adm-btn--sm">
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-plus"/></svg>Tambah varian
                </button>
                @error('variants') <p class="adm-error">{{ $message }}</p> @enderror
                @error('variants.*.name') <p class="adm-error">{{ $message }}</p> @enderror
                @error('variants.*.price') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image" class="adm-label">Foto Menu</label>
                <input type="file" id="image" name="image" accept="image/*" class="adm-input {{ $errors->has('image') ? 'is-invalid' : '' }}">
                @if($menu->image)
                    <p class="adm-help">Foto saat ini: <a class="adm-link" href="{{ asset('storage/' . $menu->image) }}" target="_blank" rel="noopener">Lihat foto</a></p>
                @endif
                @error('image') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="adm-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="adm-textarea">{{ old('description', $menu->description) }}</textarea>
                @error('description') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <label class="adm-check is-full">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                Tersedia
            </label>
        </div>

        <div class="adm-form-actions">
            <button type="submit" class="adm-btn">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-check"/></svg>Update Menu
            </button>
        </div>
    </form>
</section>

<script>
    const variantNames = ['Small', 'Medium', 'Large'];
    const variantList = document.getElementById('variant-list');
    const addVariantBtn = document.getElementById('add-variant');
    const maxVariants = 3;
    const existingVariants = @json($menu->variants->map(fn ($variant) => ['name' => $variant->name, 'price' => (float) $variant->price])->values()->all());

    function buildVariantRow(name = 'Small', price = 0, index = 0) {
        const wrapper = document.createElement('div');
        wrapper.className = 'adm-variant-row';
        wrapper.innerHTML = `
            <div>
                <label class="adm-label" for="variant-name-${index}">Nama Varian</label>
                <select id="variant-name-${index}" name="variants[${index}][name]" class="adm-select">
                    ${variantNames.map(option => `<option value="${option}" ${option === name ? 'selected' : ''}>${option}</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="adm-label" for="variant-price-${index}">Harga Varian</label>
                <input id="variant-price-${index}" type="number" name="variants[${index}][price]" min="0" step="1000" value="${price}" class="adm-input">
            </div>
            <button type="button" class="remove-variant adm-btn adm-btn--danger adm-btn--sm">Hapus</button>
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