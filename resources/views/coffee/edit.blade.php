<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kopi - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">
    <x-sidebar />
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>
        <main class="p-8 max-w-4xl w-full mx-auto">
            <h1 class="text-2xl font-semibold text-white mb-6">Edit Data Kopi</h1>
            <form method="POST" action="{{ route('data-kopi.update', $coffee) }}" class="space-y-4 bg-[#181512] p-6 rounded-2xl border border-[#26211C]">
                @csrf @method('PUT')
                @include('coffee._form', ['coffee' => $coffee])
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('data-kopi.index') }}" class="px-4 py-2 bg-stone-800 hover:bg-stone-700 text-stone-200 text-sm rounded-xl transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] text-sm font-medium rounded-xl transition">Update</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>