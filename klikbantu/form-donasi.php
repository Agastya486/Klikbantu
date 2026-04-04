<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KlikBantu - Form Donasi</title>
        <link href="./src/output.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="bg-[#f5f5f5] text-[#333] leading-relaxed">

        <div class="max-w-[500px] mx-auto my-10 px-5"> <!-- Untuk membatasi lebar dari konten, biar gak mentok dari ujung ke ujung -->
            <!-- Header -->
            <header class="text-center mb-8">
                <h1 class="text-[#006600] text-4xl font-bold mb-2">Berbagi Kebaikan</h1>
                <p class="text-[#555] text-lg">Mari salurkan zakat atau donasi dengan mudah dan aman.</p>
            </header>

            <!-- Upper nav -->
            <nav class="flex gap-2 mb-7 bg-white p-1.5 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                <a href="index.html" class="flex-1 text-center py-2.5 px-4 rounded-lg no-underline font-semibold text-[0.95rem] text-[#555] transition-all hover:bg-[#f0f9f0] hover:text-[#006600]">
                    🏠 Tampilan utama
                </a>
                <a href="donatur.html" class="flex-1 text-center py-2.5 px-4 rounded-lg no-underline font-semibold text-[0.95rem] bg-[#00aa13] text-white transition-all">
                    📝 Form Donasi
                </a>
                <a href="riwayat.html" class="flex-1 text-center py-2.5 px-4 rounded-lg no-underline font-semibold text-[0.95rem] text-[#555] transition-all hover:bg-[#f0f9f0] hover:text-[#006600]">
                    📋 Riwayat Donasi
                </a>
            </nav>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] py-8 px-7 mb-10">
                <h2 id="form-title" class="text-[#006600] text-center mb-7 text-2xl font-semibold">Form Donasi</h2>
                
                <form class="flex flex-col gap-6" method="post">
                    <!-- Nama -->
                    <div class="flex flex-col gap-2">
                        <label for="nama-donatur" class="font-semibold text-[#444] text-base">Nama Donatur</label>
                        <input type="text" id="nama-donatur" name="nama" placeholder="Contoh: Devano Agastya H" required 
                            class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
                        
                        <div class="flex flex-col gap-1 mt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-[#666] hover:text-[#00aa13] transition-colors">
                                <input type="checkbox" id="checkbox-anonim" class="w-4 h-4 accent-[#00aa13] cursor-pointer">
                                Donasi sebagai Anonim
                            </label>
                            <p id="msg-anonim" class="hidden text-[11px] text-[#00aa13] font-medium ml-6 italic">
                                ✨ Nama dan email anda akan dirahasiakan dari publik.
                            </p>
                        </div>
                    </div>
                    <!-- Jumlah donasi -->
                    <div class="flex flex-col gap-2">
                        <label for="jumlah-donasi" class="font-semibold text-[#444] text-base">Jumlah Donasi (Rp)</label>
                        <input type="number" id="jumlah-donasi" name="jumlah" min="10000" step="1000" placeholder="Minimal 10.000" required 
                            class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="button" id="btn-submit" class="flex-1 bg-[#00aa13] text-white p-4 text-lg font-bold rounded-xl cursor-pointer hover:bg-[#00960f] active:translate-y-0.5 transition-all">
                            Donasi Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script src="form-donasi.js"></script>
    </body>
</html>