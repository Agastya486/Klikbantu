<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Berbagi Kebaikan - Pilih Donasi</title>
        <link href="./src/output.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { scrollbar-width: none; }
        </style>
    </head>
    <body class="bg-[#f8f9fa] text-[#333] pb-24">

        <header class="sticky top-0 z-50 bg-white px-5 py-4 shadow-sm">
            <div class="max-w-[500px] mx-auto">
                <div class="relative">
                    <input type="text" placeholder="Cari bantuan..." class="w-full bg-gray-100 border-none rounded-full py-3 px-11 text-sm focus:ring-2 focus:ring-[#00aa13] outline-none">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-40">🔍</span>
                </div>
            </div>
        </header>

        <main class="max-w-[500px] mx-auto px-5">
            
            <div class="flex gap-2 overflow-x-auto py-4 no-scrollbar">
                <button class="bg-[#00aa13] text-white px-5 py-2 rounded-full text-xs font-semibold whitespace-nowrap">Semua</button>
                <button class="bg-white border border-gray-100 px-5 py-2 rounded-full text-xs font-semibold whitespace-nowrap shadow-sm hover:bg-[#f0fff0] cursor-pointer">Bencana Alam</button>
                <button class="bg-white border border-gray-100 px-5 py-2 rounded-full text-xs font-semibold whitespace-nowrap shadow-sm hover:bg-[#f0fff0] cursor-pointer">Anak Yatim</button>
                <button class="bg-white border border-gray-100 px-5 py-2 rounded-full text-xs font-semibold whitespace-nowrap shadow-sm hover:bg-[#f0fff0] cursor-pointer">Medis</button>
            </div>

            <h2 class="text-[1.1rem] font-bold mb-4">Pilih Kebaikanmu</h2>

            <div class="grid grid-cols-1 gap-4">
                
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 flex flex-col">
                    <img src="https://assetd.kompas.id/sFaBCPPrxizBXafXCvn085Ztl0I=/1024x577/smart/filters:format(webp):quality(80)/https://kompas.id/wp-content/uploads/2018/11/20181112ody1-operasi-jantung-MICS_1542084610-1.jpg" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <span class="text-[0.65rem] font-bold text-[#00aa13] uppercase tracking-wider">Kesehatan</span>
                        <h3 class="text-[0.95rem] font-bold mt-1 mb-3 leading-tight h-10 overflow-hidden">Bantu Biaya Operasi Jantung Dek Alif</h3>
                        <div class="bg-gray-100 h-1.5 rounded-full mb-2">
                            <div class="bg-[#00aa13] h-full rounded-full" style="width: 40%;"></div>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-[0.7rem] text-gray-400">Terkumpul <br><span class="text-[#333] font-bold text-[0.85rem]">Rp 12.400.000</span></p>
                            <p class="text-[0.7rem] text-gray-400 text-right">Sisa Hari <br><span class="text-[#333] font-bold text-[0.85rem]">12 Hari</span></p>
                        </div>
                        <a href="form-donasi.php" class="block text-center bg-[#00aa13] text-white font-bold py-2.5 rounded-xl text-sm transition-active active:scale-95">Donasi Sekarang</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 flex flex-col">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRVPjhnXSWazaPHXqyb8tTR_0C2YdaP82uGiA&s" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <span class="text-[0.65rem] font-bold text-[#00aa13] uppercase tracking-wider">Pendidikan</span>
                        <h3 class="text-[0.95rem] font-bold mt-1 mb-3 leading-tight h-10 overflow-hidden">Renovasi Madrasah di Pedalaman NTT</h3>
                        <div class="bg-gray-100 h-1.5 rounded-full mb-2">
                            <div class="bg-[#00aa13] h-full rounded-full" style="width: 75%;"></div>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-[0.7rem] text-gray-400">Terkumpul <br><span class="text-[#333] font-bold text-[0.85rem]">Rp 89.000.000</span></p>
                            <p class="text-[0.7rem] text-gray-400 text-right">Sisa Hari <br><span class="text-[#333] font-bold text-[0.85rem]">5 Hari</span></p>
                        </div>
                        <a href="form-donasi.php" class="block text-center bg-[#00aa13] text-white font-bold py-2.5 rounded-xl text-sm">Donasi Sekarang</a>
                    </div>
                </div>

            </div>
        </main>

        
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
            <a href="index.php" class="flex-1 text-center text-[0.7rem] text-gray-400 transition-colors">
                <span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda
            </a>
            <a href="#" class="flex-1 text-center text-[0.7rem] text-[#00aa13] font-bold">
                <span class="text-[1.4rem] block mb-0.5">💰</span>Donasi
            </a>
            <a href="riwayat.php" class="flex-1 text-center text-[0.7rem] text-gray-400">
                <span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat
            </a>
            <a href="akun.php" class="flex-1 text-center text-[0.7rem] text-gray-400">
                <span class="text-[1.4rem] block mb-0.5">👤</span>Akun
            </a>
        </nav>
    </body>
</html>