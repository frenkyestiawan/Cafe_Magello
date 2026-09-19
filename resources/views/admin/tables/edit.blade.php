@extends('admin.layouts.app')

@section('title', 'Edit Meja')
@section('page-title', 'Edit Meja')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <a href="{{ route('admin.tables.index') }}" class="text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Meja
        </a>
    </div>
    
    <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="table_number" class="block text-gray-700 font-medium mb-2">Nomor Meja</label>
                    <div class="flex space-x-2">
                        <input type="text" id="table_number" name="table_number" required
                               class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('table_number', $table->table_number) }}">
                        <button type="button" onclick="autoGenerateNumber()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Klik tombol sync untuk auto-generate nomor berikutnya.</p>
                    @error('table_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="capacity" class="block text-gray-700 font-medium mb-2">Kapasitas (orang)</label>
                    <input type="number" id="capacity" name="capacity" required min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('capacity', $table->capacity) }}">
                    @error('capacity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available" value="1" class="mr-2"
                               {{ old('is_available', $table->is_available) ? 'checked' : '' }}>
                        <span class="text-gray-700">Tersedia</span>
                    </label>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Jika nomor meja diubah, QR Code akan otomatis diperbarui.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>Update Meja
            </button>
        </div>
    </form>
</div>

<script>
function autoGenerateNumber() {
    const currentNumber = document.getElementById('table_number').value;
    if (currentNumber) {
        const num = parseInt(currentNumber);
        const nextNumber = (num + 1).toString().padStart(2, '0');
        document.getElementById('table_number').value = nextNumber;
    }
}
</script>
@endsection