@extends('admin.layouts.app')

@section('title', 'Edit Meja')
@section('page-title', 'Edit Meja')
@section('page-subtitle', 'Perbarui nomor, kapasitas, dan ketersediaan meja.')
@section('page-actions')
    <a href="{{ route('admin.tables.index') }}" class="adm-btn adm-btn--ghost">
        <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg>Kembali
    </a>
@endsection

@section('content')
<section class="adm-card">
    <form action="{{ route('admin.tables.update', $table->id) }}" method="POST" class="adm-card-body adm-form">
        @csrf
        @method('PUT')

        <div class="adm-form-grid">
            <div>
                <label for="table_number" class="adm-label">Nomor Meja</label>
                <div class="adm-input-action">
                    <input type="text" id="table_number" name="table_number" class="adm-input {{ $errors->has('table_number') ? 'is-invalid' : '' }}" value="{{ old('table_number', $table->table_number) }}" required>
                    <button type="button" class="adm-icon-btn" data-generate-table-number aria-label="Naikkan nomor meja" title="Naikkan nomor meja">
                        <svg class="adm-icon" aria-hidden="true"><use href="#i-plus"/></svg>
                    </button>
                </div>
                <p class="adm-help">Naikkan nomor meja untuk menyiapkan nomor berikutnya.</p>
                @error('table_number') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="capacity" class="adm-label">Kapasitas (orang)</label>
                <input type="number" id="capacity" name="capacity" class="adm-input {{ $errors->has('capacity') ? 'is-invalid' : '' }}" value="{{ old('capacity', $table->capacity) }}" required min="1">
                @error('capacity') <p class="adm-error">{{ $message }}</p> @enderror
            </div>

            <label class="adm-check is-full">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $table->is_available) ? 'checked' : '' }}>
                Tersedia
            </label>

            <div class="adm-alert adm-alert--warn is-full">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-alert"/></svg>
                <p>Jika nomor meja berubah, QR Code akan diperbarui otomatis.</p>
            </div>
        </div>

        <div class="adm-form-actions">
            <button type="submit" class="adm-btn">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-check"/></svg>Update Meja
            </button>
        </div>
    </form>
</section>

<script>
document.querySelector('[data-generate-table-number]').addEventListener('click', function () {
    const input = document.getElementById('table_number');
    const currentNumber = Number.parseInt(input.value, 10);
    if (!Number.isNaN(currentNumber)) input.value = String(currentNumber + 1).padStart(2, '0');
});
</script>
@endsection