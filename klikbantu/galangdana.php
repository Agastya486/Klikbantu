<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Galang Dana</title>
    <link href="./src/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#f5f5f5] text-[#333] leading-relaxed">

    <div class="max-w-[500px] mx-auto my-10 px-5">
        <header class="text-center mb-8">
            <h1 class="text-[#006600] text-4xl font-bold mb-2">Mulai Aksi</h1>
            <p class="text-[#555] text-lg">Buat kampanye galang dana kamu sendiri dengan mudah.</p>
        </header>

        <nav class="flex gap-2 mb-7 bg-white p-1.5 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
            <a href="index.html" class="flex-1 text-center py-2.5 px-4 rounded-lg no-underline font-semibold text-[0.95rem] text-[#555] transition-all hover:bg-[#f0f9f0] hover:text-[#006600]">
                🏠 Home
            </a>
            <a href="galang-dana.html" class="flex-1 text-center py-2.5 px-4 rounded-lg no-underline font-semibold text-[0.95rem] bg-[#00aa13] text-white transition-all">
                🚀 Galang Dana
            </a>
        </nav>

        <div class="bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] py-8 px-7 mb-10">
            <h2 class="text-[#006600] text-center mb-7 text-2xl font-semibold">Buat Galang Dana</h2>
            
            <form class="flex flex-col gap-6" method="post" enctype="multipart/form-data">
                
                <div class="flex flex-col gap-2">
                    <label for="judul-camp" class="font-semibold text-[#444] text-base">Judul Kampanye</label>
                    <input type="text" id="judul-camp" name="judul" placeholder="Contoh: Bantuan Sembako untuk Lansia" required 
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
                </div>

                <div class="flex flex-col gap-2">
                    <label for="target-dana" class="font-semibold text-[#444] text-base">Target Dana (Rp)</label>
                    <input type="number" id="target-dana" name="target" min="100000" placeholder="Minimal 100.000" required 
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
                </div>

                <div class="flex flex-col gap-2">
                    <label for="kategori" class="font-semibold text-[#444] text-base">Kategori</label>
                    <select name="kategori" id="kategori" required 
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all bg-white focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15">
                        <option value="" disabled selected>Pilih kategori bantuan</option>
                        <option value="kesehatan">Kesehatan</option>
                        <option value="pendidikan">Pendidikan</option>
                        <option value="bencana">Bencana Alam</option>
                        <option value="sosial">Sosial/Panti Asuhan</option>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="foto-banner" class="font-semibold text-[#444] text-base">Foto Sampul Kampanye</label>
                    <div class="relative">
                        <input type="file" id="foto-banner" name="foto" accept="image/*" required 
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-[#f0f9f0] file:text-[#006600]
                            hover:file:bg-[#e2f5e2] cursor-pointer
                            border border-[#d1d5db] rounded-xl p-2" />
                    </div>
                    <p class="text-xs text-gray-400 mt-1">*Direkomendasikan untuk menggunakan foto landscape kualitas tinggi.</p>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="deskripsi" class="font-semibold text-[#444] text-base">Ceritakan Masalahnya</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan secara singkat siapa yang dibantu..." required 
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15"></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full bg-[#00aa13] text-white p-4 text-lg font-bold rounded-xl cursor-pointer hover:bg-[#00960f] active:translate-y-0.5 transition-all shadow-lg shadow-[#00aa13]/20">
                        Launch Kampanye 🚀
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>