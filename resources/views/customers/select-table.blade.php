<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Meja - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="max-w-md mx-auto mt-20 bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Pilih Meja</h1>

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('order.set-table') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="table_number" class="block text-sm font-medium mb-2">Nomor Meja</label>
                <input type="text" id="table_number" name="table_number" placeholder="Contoh: 1" required class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700">Pilih Meja</button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-slate-600 hover:text-slate-900">Kembali ke beranda</a>
        </div>
    </div>
</body>
</html>
