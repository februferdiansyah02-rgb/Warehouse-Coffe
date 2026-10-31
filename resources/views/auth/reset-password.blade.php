<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - KOPI.ONE Warehouse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">

    <!-- SISI KIRI: Branding & Headline -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden bg-[#181512] border-r border-[#26211C]">
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#D99B52]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center space-x-3 z-10">
            <div class="w-10 h-10 bg-[#26211C] text-[#D99B52] border border-[#3A322B] rounded-lg flex items-center justify-center font-bold text-lg">
                K
            </div>
            <div>
                <h2 class="font-bold text-white tracking-wider text-sm leading-none">KOPI.ONE</h2>
                <span class="text-[10px] text-stone-500 uppercase tracking-widest font-medium">CAFE MANAGEMENT</span>
            </div>
        </div>

        <div class="max-w-md z-10 my-auto">
            <span class="text-xs uppercase tracking-widest text-[#D99B52] font-semibold block mb-4">
                KATA SANDI BARU
            </span>
            <h1 class="text-4xl xl:text-5xl font-semibold text-white leading-tight tracking-tight mb-6">
                Buat kata sandi baru yang lebih kuat.
            </h1>
            <p class="text-stone-400 text-sm leading-relaxed">
                Gunakan kombinasi huruf, angka, dan simbol agar akun admin tetap aman.
            </p>
        </div>

        <div class="z-10 pt-6 border-t border-[#26211C]/60 flex items-center space-x-3">
            <span class="text-lg font-bold text-white">100%</span>
            <span class="text-xs text-stone-400">Transaksi dihitung ulang dan stok diperbarui otomatis.</span>
        </div>
    </div>

    <!-- SISI KANAN: Form Atur Ulang Kata Sandi -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 relative">
        <div class="w-full max-w-sm space-y-6">

            <div>
                <span class="text-[11px] uppercase tracking-widest text-[#D99B52] font-bold block mb-2">
                    ATUR ULANG
                </span>
                <h2 class="text-2xl font-medium text-white mb-1">
                    Kata sandi baru
                </h2>
                <p class="text-xs text-stone-400">
                    Masukkan kata sandi baru untuk akun admin Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-xs text-stone-400 mb-1.5 font-medium">Email admin</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                        class="w-full bg-[#181512] border border-[#2D2620] rounded-xl px-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-[#D99B52] focus:ring-1 focus:ring-[#D99B52] transition"
                        placeholder="admin@kopi.one">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400" />
                </div>

                <div>
                    <label for="password" class="block text-xs text-stone-400 mb-1.5 font-medium">Kata sandi baru</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full bg-[#181512] border border-[#2D2620] rounded-xl px-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-[#D99B52] focus:ring-1 focus:ring-[#D99B52] transition"
                        placeholder="Minimal 8 karakter">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs text-stone-400 mb-1.5 font-medium">Konfirmasi kata sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-[#181512] border border-[#2D2620] rounded-xl px-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-[#D99B52] focus:ring-1 focus:ring-[#D99B52] transition"
                        placeholder="Ulangi kata sandi baru">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-400" />
                </div>

                <button type="submit"
                    class="w-full bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium py-3 px-4 rounded-xl text-sm shadow-lg transition duration-200 mt-2">
                    Simpan kata sandi baru &rarr;
                </button>
            </form>

            <div class="pt-4 text-center border-t border-[#26211C]">
                <p class="text-xs text-stone-400">
                    Kembali ke 
                    <a href="{{ route('login') }}" class="text-[#D99B52] hover:underline font-medium">halaman masuk</a>
                </p>
            </div>

            <p class="text-[10px] text-stone-600 text-center">
                &bull; Pastikan kata sandi baru tidak mudah ditebak
            </p>
        </div>

        <button class="absolute bottom-6 right-6 w-8 h-8 rounded-full bg-[#181512] border border-[#26211C] flex items-center justify-center text-xs text-stone-400 hover:text-white transition">
            ?
        </button>
    </div>

</body>
</html>