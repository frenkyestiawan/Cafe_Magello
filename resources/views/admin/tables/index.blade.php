@extends('admin.layouts.app')

@section('title', 'Meja & QR Code')
@section('page-title', 'Pengelolaan Meja & QR Code')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Meja</h3>
        <a href="{{ route('admin.tables.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Tambah Meja
        </a>
    </div>
    
    @if($tables->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Meja</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tables as $table)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $table->table_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $table->capacity }} orang</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($table->is_available)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.tables.show', $table->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.tables.edit', $table->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus meja ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $tables->links() }}
        </div>
    @else
        <p class="text-gray-500 text-center py-4">Belum ada meja.</p>
    @endif
</div>
@endsection