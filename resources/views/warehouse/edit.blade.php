<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Gudang - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased" x-data="{ mobileOpen: false }">

    <x-sidebar />

    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <div class="flex items-center space-x-3">
                <button @click="mobileOpen = !mobileOpen" class="p-2 -ml-2 rounded-md text-[#D99B52] hover:text-white hover:bg-[#26211C] transition" aria-label="Buka menu navigasi">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            </div>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>

        <main class="p-4 sm:p-6 lg:p-8 max-w-4xl w-full mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-white">Profil Gudang</h1>
                    <p class="text-xs text-stone-400 mt-1">Identitas gudang/perusahaan yang ditampilkan di sistem.</p>
                </div>
            </div>

            @if (session('status') === 'warehouse-updated')
                <div class="mb-6 p-4 bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-sm rounded-xl">
                    Profil gudang berhasil diperbarui.
                </div>
            @endif

            <form method="POST" action="{{ route('warehouse.update') }}" enctype="multipart/form-data" class="space-y-6 bg-[#181512] p-4 sm:p-6 rounded-2xl border border-[#26211C]">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-stone-400 mb-1.5">Nama Gudang</label>
                        <input type="text" name="name" value="{{ old('name', $profile->name) }}" required
                               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
                        @error('name')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-stone-400 mb-1.5">Nama Perusahaan</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}"
                               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
                        @error('company_name')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-stone-400 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}"
                               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
                        @error('phone')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-stone-400 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $profile->email) }}"
                               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
                        @error('email')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-stone-400 mb-1.5">Penanggung Jawab</label>
                        <input type="text" name="pic" value="{{ old('pic', $profile->pic) }}"
                               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
                        @error('pic')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-stone-400 mb-1.5">Logo Gudang</label>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
                        @error('logo')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                        @if ($profile->logo)
                            <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo" class="mt-2 h-12 w-12 object-contain rounded-lg border border-[#26211C]">
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-stone-400 mb-1.5">Alamat Gudang</label>
                    <textarea name="address" rows="2" class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">{{ old('address', $profile->address) }}</textarea>
                    @error('address')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs text-stone-400 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">{{ old('description', $profile->description) }}</textarea>
                    @error('description')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-stone-800 hover:bg-stone-700 text-stone-200 text-sm rounded-xl transition text-center">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] text-sm font-medium rounded-xl transition">Simpan</button>
                </div>
            </form>
        </main>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>