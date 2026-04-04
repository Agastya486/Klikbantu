<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KlikBantu - Zakat</title>
        <link href="./src/output.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { scrollbar-width: none; }
            .zakat-card-active {
                border-color: #00aa13 !important;
                background-color: #f0fff0 !important;
            }
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.35s ease;
            }
            .accordion-content.open { max-height: 600px; }
            .accordion-arrow { transition: transform 0.3s ease; }
            .accordion-arrow.open { transform: rotate(180deg); }
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
            .tab-active { background-color: #00aa13; color: white; }
            .tab-inactive { background-color: transparent; color: rgba(255,255,255,0.7); }
        </style>
    </head>
    <body class="bg-[#f8f9fa] text-[#333] pb-28">

        <!-- Header -->
        <header class="bg-gradient-to-br from-[#00aa13] to-[#006600] text-white pt-14 pb-6 px-5 rounded-b-[32px] shadow-[0_4px_15px_rgba(0,102,0,0.2)]">
            <div class="max-w-[500px] mx-auto">
                <div class="flex items-center mb-3">
                    <a href="index.php" class="mr-3 text-white/80 text-xl">←</a>
                    <h1 class="text-[1.2rem] font-bold">Zakat</h1>
                </div>
                <p class="text-white/80 text-[0.82rem] mb-5">Tunaikan kewajiban zakatmu dengan mudah, aman, dan tepat sasaran.</p>
                <!-- Tabs -->
                <div class="flex gap-1.5 bg-white/20 backdrop-blur-sm p-1.5 rounded-2xl">
                    <button onclick="switchTab('hitung')" id="tab-hitung" class="flex-1 py-2 rounded-xl text-[0.8rem] font-semibold transition-all tab-active cursor-pointer">🧮 Hitung</button>
                    <button onclick="switchTab('bayar')" id="tab-bayar" class="flex-1 py-2 rounded-xl text-[0.8rem] font-semibold transition-all tab-inactive cursor-pointer">💳 Bayar</button>
                    <button onclick="switchTab('info')" id="tab-info" class="flex-1 py-2 rounded-xl text-[0.8rem] font-semibold transition-all tab-inactive cursor-pointer">📖 Info</button>
                </div>
            </div>
        </header>

        <main class="max-w-[500px] mx-auto px-5 mt-6">

            <!-- ===== TAB HITUNG ===== -->
            <div id="content-hitung">
                <h2 class="text-[0.7rem] font-bold text-gray-400 uppercase tracking-widest mb-3">Pilih Jenis Zakat</h2>
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <button onclick="selectZakat(this,'fitrah')" class="zakat-type-card flex flex-col items-center bg-white border-2 border-transparent rounded-2xl p-3 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <div class="w-11 h-11 bg-[#f0fff0] rounded-xl flex items-center justify-center mb-2"><span class="text-[1.3rem]">🌙</span></div>
                        <span class="text-[0.72rem] font-semibold text-center leading-tight">Zakat<br>Fitrah</span>
                    </button>
                    <button onclick="selectZakat(this,'maal')" class="zakat-type-card flex flex-col items-center bg-white border-2 border-transparent rounded-2xl p-3 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <div class="w-11 h-11 bg-[#f0fff0] rounded-xl flex items-center justify-center mb-2"><span class="text-[1.3rem]">💰</span></div>
                        <span class="text-[0.72rem] font-semibold text-center leading-tight">Zakat<br>Maal</span>
                    </button>
                    <button onclick="selectZakat(this,'penghasilan')" class="zakat-type-card flex flex-col items-center bg-white border-2 border-transparent rounded-2xl p-3 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <div class="w-11 h-11 bg-[#f0fff0] rounded-xl flex items-center justify-center mb-2"><span class="text-[1.3rem]">💼</span></div>
                        <span class="text-[0.72rem] font-semibold text-center leading-tight">Zakat<br>Penghasilan</span>
                    </button>
                    <button onclick="selectZakat(this,'tabungan')" class="zakat-type-card flex flex-col items-center bg-white border-2 border-transparent rounded-2xl p-3 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <div class="w-11 h-11 bg-[#f0fff0] rounded-xl flex items-center justify-center mb-2"><span class="text-[1.3rem]">🏦</span></div>
                        <span class="text-[0.72rem] font-semibold text-center leading-tight">Zakat<br>Tabungan</span>
                    </button>
                    <button onclick="selectZakat(this,'emas')" class="zakat-type-card flex flex-col items-center bg-white border-2 border-transparent rounded-2xl p-3 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <div class="w-11 h-11 bg-[#f0fff0] rounded-xl flex items-center justify-center mb-2"><span class="text-[1.3rem]">🥇</span></div>
                        <span class="text-[0.72rem] font-semibold text-center leading-tight">Zakat<br>Emas</span>
                    </button>
                    <button onclick="selectZakat(this,'perusahaan')" class="zakat-type-card flex flex-col items-center bg-white border-2 border-transparent rounded-2xl p-3 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <div class="w-11 h-11 bg-[#f0fff0] rounded-xl flex items-center justify-center mb-2"><span class="text-[1.3rem]">🏢</span></div>
                        <span class="text-[0.72rem] font-semibold text-center leading-tight">Zakat<br>Perusahaan</span>
                    </button>
                </div>

                <!-- Kalkulator Fitrah -->
                <div id="calc-fitrah" class="calc-section bg-white rounded-2xl p-5 shadow-sm mb-4">
                    <h3 class="font-bold text-[0.95rem] mb-0.5">Kalkulator Zakat Fitrah</h3>
                    <p class="text-[0.72rem] text-gray-400 mb-4">Setara 2,5 kg beras per jiwa</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-2 block">Jumlah Jiwa</label>
                            <div class="flex items-center gap-4">
                                <button onclick="changeJiwa(-1)" class="w-10 h-10 rounded-xl bg-gray-100 text-xl font-bold flex items-center justify-center active:bg-gray-200 cursor-pointer">−</button>
                                <span id="jumlah-jiwa" class="text-2xl font-bold text-[#00aa13] w-8 text-center">1</span>
                                <button onclick="changeJiwa(1)" class="w-10 h-10 rounded-xl bg-gray-100 text-xl font-bold flex items-center justify-center active:bg-gray-200 cursor-pointer">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Harga Beras per Kg</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                                <input type="number" id="harga-beras" value="15000" oninput="hitungFitrah()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]">
                            </div>
                        </div>
                        <div class="bg-[#f0fff0] rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <p class="text-[0.72rem] text-gray-400">Total Zakat Fitrah</p>
                                <p id="result-fitrah" class="text-[1.25rem] font-bold text-[#00aa13]">Rp 37.500</p>
                            </div>
                            <button onclick="bukaBayarDariKalkulator('fitrah', document.getElementById('result-fitrah').textContent)" class="bg-[#00aa13] text-white text-[0.8rem] font-bold px-4 py-2 rounded-xl active:scale-95 transition-all cursor-pointer">Bayar →</button>
                        </div>
                    </div>
                </div>

                <!-- Kalkulator Maal -->
                <div id="calc-maal" class="calc-section hidden bg-white rounded-2xl p-5 shadow-sm mb-4">
                    <h3 class="font-bold text-[0.95rem] mb-0.5">Kalkulator Zakat Maal</h3>
                    <p class="text-[0.72rem] text-gray-400 mb-4">Zakat 2,5% jika harta ≥ nisab (85gr emas)</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Total Harta (Rp)</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="total-harta" placeholder="0" oninput="hitungMaal()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Harga Emas per Gram</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="harga-emas-maal" value="1050000" oninput="hitungMaal()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div id="result-maal-box" class="hidden bg-[#f0fff0] rounded-xl p-4 flex justify-between items-center">
                            <div><p class="text-[0.72rem] text-gray-400">Zakat Maal (2,5%)</p><p id="result-maal" class="text-[1.25rem] font-bold text-[#00aa13]">Rp 0</p></div>
                            <button onclick="bukaBayarDariKalkulator('maal', document.getElementById('result-maal').textContent)" class="bg-[#00aa13] text-white text-[0.8rem] font-bold px-4 py-2 rounded-xl active:scale-95 transition-all cursor-pointer">Bayar →</button>
                        </div>
                        <div id="maal-tidak-wajib" class="hidden bg-yellow-50 rounded-xl p-4">
                            <p class="text-[0.8rem] text-yellow-700 font-semibold">⚠️ Harta belum mencapai nisab</p>
                            <p id="maal-nisab-info" class="text-[0.72rem] text-yellow-600 mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Kalkulator Penghasilan -->
                <div id="calc-penghasilan" class="calc-section hidden bg-white rounded-2xl p-5 shadow-sm mb-4">
                    <h3 class="font-bold text-[0.95rem] mb-0.5">Kalkulator Zakat Penghasilan</h3>
                    <p class="text-[0.72rem] text-gray-400 mb-4">2,5% dari penghasilan jika ≥ nisab (520kg beras)</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Penghasilan per Bulan</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="penghasilan" placeholder="0" oninput="hitungPenghasilan()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div id="result-penghasilan-box" class="hidden bg-[#f0fff0] rounded-xl p-4 flex justify-between items-center">
                            <div><p class="text-[0.72rem] text-gray-400">Zakat per Bulan</p><p id="result-penghasilan" class="text-[1.25rem] font-bold text-[#00aa13]">Rp 0</p></div>
                            <button onclick="bukaBayarDariKalkulator('penghasilan', document.getElementById('result-penghasilan').textContent)" class="bg-[#00aa13] text-white text-[0.8rem] font-bold px-4 py-2 rounded-xl active:scale-95 transition-all cursor-pointer">Bayar →</button>
                        </div>
                        <div id="penghasilan-tidak-wajib" class="hidden bg-yellow-50 rounded-xl p-4">
                            <p class="text-[0.8rem] text-yellow-700 font-semibold">⚠️ Penghasilan belum mencapai nisab</p>
                            <p class="text-[0.72rem] text-yellow-600 mt-1">Nisab zakat penghasilan ≈ Rp 7.800.000/bulan</p>
                        </div>
                    </div>
                </div>

                <!-- Kalkulator Tabungan -->
                <div id="calc-tabungan" class="calc-section hidden bg-white rounded-2xl p-5 shadow-sm mb-4">
                    <h3 class="font-bold text-[0.95rem] mb-0.5">Kalkulator Zakat Tabungan</h3>
                    <p class="text-[0.72rem] text-gray-400 mb-4">2,5% dari saldo jika ≥ nisab & sudah 1 tahun (haul)</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Saldo Tabungan</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="saldo-tabungan" placeholder="0" oninput="hitungTabungan()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div id="result-tabungan-box" class="hidden bg-[#f0fff0] rounded-xl p-4 flex justify-between items-center">
                            <div><p class="text-[0.72rem] text-gray-400">Zakat Tabungan (2,5%)</p><p id="result-tabungan" class="text-[1.25rem] font-bold text-[#00aa13]">Rp 0</p></div>
                            <button onclick="bukaBayarDariKalkulator('tabungan', document.getElementById('result-tabungan').textContent)" class="bg-[#00aa13] text-white text-[0.8rem] font-bold px-4 py-2 rounded-xl active:scale-95 transition-all cursor-pointer">Bayar →</button>
                        </div>
                        <div id="tabungan-tidak-wajib" class="hidden bg-yellow-50 rounded-xl p-4">
                            <p class="text-[0.8rem] text-yellow-700 font-semibold">⚠️ Tabungan belum mencapai nisab</p>
                            <p class="text-[0.72rem] text-yellow-600 mt-1">Nisab zakat tabungan ≈ Rp 89.250.000</p>
                        </div>
                    </div>
                </div>

                <!-- Kalkulator Emas -->
                <div id="calc-emas" class="calc-section hidden bg-white rounded-2xl p-5 shadow-sm mb-4">
                    <h3 class="font-bold text-[0.95rem] mb-0.5">Kalkulator Zakat Emas</h3>
                    <p class="text-[0.72rem] text-gray-400 mb-4">2,5% dari nilai emas jika ≥ 85 gram & sudah 1 tahun</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Berat Emas (gram)</label>
                            <input type="number" id="berat-emas" placeholder="0" oninput="hitungEmas()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl px-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]">
                        </div>
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Harga Emas per Gram</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="harga-emas" value="1050000" oninput="hitungEmas()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div id="result-emas-box" class="hidden bg-[#f0fff0] rounded-xl p-4 flex justify-between items-center">
                            <div><p class="text-[0.72rem] text-gray-400">Zakat Emas (2,5%)</p><p id="result-emas" class="text-[1.25rem] font-bold text-[#00aa13]">Rp 0</p></div>
                            <button onclick="bukaBayarDariKalkulator('emas', document.getElementById('result-emas').textContent)" class="bg-[#00aa13] text-white text-[0.8rem] font-bold px-4 py-2 rounded-xl active:scale-95 transition-all cursor-pointer">Bayar →</button>
                        </div>
                        <div id="emas-tidak-wajib" class="hidden bg-yellow-50 rounded-xl p-4">
                            <p class="text-[0.8rem] text-yellow-700 font-semibold">⚠️ Emas belum mencapai nisab (85 gram)</p>
                        </div>
                    </div>
                </div>

                <!-- Kalkulator Perusahaan -->
                <div id="calc-perusahaan" class="calc-section hidden bg-white rounded-2xl p-5 shadow-sm mb-4">
                    <h3 class="font-bold text-[0.95rem] mb-0.5">Kalkulator Zakat Perusahaan</h3>
                    <p class="text-[0.72rem] text-gray-400 mb-4">2,5% dari (aset lancar − kewajiban jangka pendek)</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Aset Lancar</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="aset-lancar" placeholder="0" oninput="hitungPerusahaan()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Kewajiban Jangka Pendek</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" id="kewajiban" placeholder="0" oninput="hitungPerusahaan()" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]"></div>
                        </div>
                        <div id="result-perusahaan-box" class="hidden bg-[#f0fff0] rounded-xl p-4 flex justify-between items-center">
                            <div><p class="text-[0.72rem] text-gray-400">Zakat Perusahaan (2,5%)</p><p id="result-perusahaan" class="text-[1.25rem] font-bold text-[#00aa13]">Rp 0</p></div>
                            <button onclick="bukaBayarDariKalkulator('perusahaan', document.getElementById('result-perusahaan').textContent)" class="bg-[#00aa13] text-white text-[0.8rem] font-bold px-4 py-2 rounded-xl active:scale-95 transition-all cursor-pointer">Bayar →</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== TAB BAYAR ===== -->
            <div id="content-bayar" class="hidden">
                <form id="form-pembayaran-zakat" onsubmit="event.preventDefault(); prosesZakat();" class="bg-white rounded-2xl p-5 shadow-sm mb-5">                    <h2 class="font-bold text-[0.95rem] mb-4">Form Pembayaran Zakat</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Nama Muzakki</label>
                            <input type="text" name="nama_muzakki" id="nama-muzakki" placeholder="Nama lengkap kamu" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl px-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]" required>
                        </div>
                        <!-- Anonim toggle -->
                        <div class="flex flex-col gap-1">
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-[#666] hover:text-[#00aa13] transition-colors">
                                <input type="checkbox" name="anonim" id="checkbox-anonim-zakat" class="w-4 h-4 accent-[#00aa13] cursor-pointer">
                                Bayar sebagai Anonim
                            </label>
                            <p id="msg-anonim-zakat" class="hidden text-[11px] text-[#00aa13] font-medium ml-6 italic">
                                ✨ Nama kamu akan dirahasiakan dari publik.
                            </p>
                        </div>
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Jenis Zakat</label>
                            <select name="jenis_zakat" id="jenis-zakat-bayar" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl px-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13] cursor-pointer">
                                <option value="">Pilih jenis zakat</option>
                                <option value="fitrah">Zakat Fitrah</option>
                                <option value="maal">Zakat Maal</option>
                                <option value="penghasilan">Zakat Penghasilan</option>
                                <option value="tabungan">Zakat Tabungan</option>
                                <option value="emas">Zakat Emas</option>
                                <option value="perusahaan">Zakat Perusahaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Jumlah Zakat</label>
                            <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[0.85rem]">Rp</span>
                            <input type="number" name="jumlah_zakat" id="jumlah-zakat-bayar" placeholder="0" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-[0.9rem] focus:outline-none focus:border-[#00aa13]" required></div>
                        </div>
                        <!-- Nominal cepat -->
                        <div>
                            <p class="text-[0.8rem] font-semibold text-gray-500 mb-2">Nominal Cepat</p>
                            <div class="grid grid-cols-3 gap-2">
                                <button onclick="setNominal(50000)" class="bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100">Rp 50rb</button>
                                <button onclick="setNominal(100000)" class="bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100">Rp 100rb</button>
                                <button onclick="setNominal(200000)" class="bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100">Rp 200rb</button>
                                <button onclick="setNominal(500000)" class="bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100">Rp 500rb</button>
                                <button onclick="setNominal(1000000)" class="bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100">Rp 1jt</button>
                                <button onclick="setNominal(2500000)" class="bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100">Rp 2,5jt</button>
                            </div>
                        </div>
                        <button class="w-full bg-[#00aa13] text-white font-bold py-4 rounded-xl text-[0.95rem] active:scale-95 transition-all shadow-lg shadow-green-100 mt-2 cursor-pointer">
                            Bayar Zakat Sekarang 🤲
                        </button>
                    </div>
                </form>

                <h2 class="text-[0.7rem] font-bold text-gray-400 uppercase tracking-widest mb-3">Disalurkan Melalui</h2>
                <div class="flex gap-3 overflow-x-auto pb-3 no-scrollbar">
                    <div class="flex-shrink-0 bg-white rounded-2xl p-3 shadow-sm flex flex-col items-center w-24">
                        <span class="text-2xl mb-1">🕌</span>
                        <span class="text-[0.65rem] font-semibold text-center">Baznas</span>
                    </div>
                    <div class="flex-shrink-0 bg-white rounded-2xl p-3 shadow-sm flex flex-col items-center w-24">
                        <span class="text-2xl mb-1">🌿</span>
                        <span class="text-[0.65rem] font-semibold text-center leading-tight">Dompet Dhuafa</span>
                    </div>
                    <div class="flex-shrink-0 bg-white rounded-2xl p-3 shadow-sm flex flex-col items-center w-24">
                        <span class="text-2xl mb-1">💚</span>
                        <span class="text-[0.65rem] font-semibold text-center leading-tight">LAZ Muhammadiyah</span>
                    </div>
                    <div class="flex-shrink-0 bg-white rounded-2xl p-3 shadow-sm flex flex-col items-center w-24">
                        <span class="text-2xl mb-1">☪️</span>
                        <span class="text-[0.65rem] font-semibold text-center leading-tight">Yatim Mandiri</span>
                    </div>
                </div>
            </div>

            <!-- ===== TAB INFO ===== -->
            <div id="content-info" class="hidden">
                <div class="bg-gradient-to-r from-[#00aa13] to-[#006600] rounded-2xl p-5 text-white mb-5">
                    <p class="text-[0.72rem] opacity-80 mb-1">Firman Allah SWT</p>
                    <p class="text-[0.88rem] font-semibold leading-relaxed">"Ambillah zakat dari sebagian harta mereka... dan doakanlah mereka."</p>
                    <p class="text-[0.72rem] opacity-70 mt-1">— QS. At-Taubah: 103</p>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-center"><p class="text-[1.4rem] font-bold text-[#00aa13]">8</p><p class="text-[0.68rem] text-gray-400 mt-0.5">Golongan Penerima</p></div>
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-center"><p class="text-[1.4rem] font-bold text-[#00aa13]">2,5%</p><p class="text-[0.68rem] text-gray-400 mt-0.5">Kadar Zakat Maal</p></div>
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-center"><p class="text-[1.4rem] font-bold text-[#00aa13]">85gr</p><p class="text-[0.68rem] text-gray-400 mt-0.5">Nisab Emas</p></div>
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-center"><p class="text-[1.4rem] font-bold text-[#00aa13]">1 thn</p><p class="text-[0.68rem] text-gray-400 mt-0.5">Haul (Kepemilikan)</p></div>
                </div>

                <h2 class="text-[0.7rem] font-bold text-gray-400 uppercase tracking-widest mb-3">8 Golongan Penerima (Asnaf)</h2>
                <div class="bg-white rounded-2xl p-4 shadow-sm mb-5">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">🤲</span><div><p class="text-[0.75rem] font-semibold">Fakir</p><p class="text-[0.62rem] text-gray-400">Tidak punya harta</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">🏚️</span><div><p class="text-[0.75rem] font-semibold">Miskin</p><p class="text-[0.62rem] text-gray-400">Harta tidak cukup</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">👷</span><div><p class="text-[0.75rem] font-semibold">Amil</p><p class="text-[0.62rem] text-gray-400">Pengelola zakat</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">💚</span><div><p class="text-[0.75rem] font-semibold">Mualaf</p><p class="text-[0.62rem] text-gray-400">Baru masuk Islam</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">⛓️</span><div><p class="text-[0.75rem] font-semibold">Riqab</p><p class="text-[0.62rem] text-gray-400">Membebaskan budak</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">💸</span><div><p class="text-[0.75rem] font-semibold">Gharimin</p><p class="text-[0.62rem] text-gray-400">Terlilit hutang</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">☪️</span><div><p class="text-[0.75rem] font-semibold">Fi Sabilillah</p><p class="text-[0.62rem] text-gray-400">Pejuang Islam</p></div></div>
                        <div class="flex items-center gap-2 p-2.5 bg-[#f0fff0] rounded-xl"><span class="text-lg">🧳</span><div><p class="text-[0.75rem] font-semibold">Ibnu Sabil</p><p class="text-[0.62rem] text-gray-400">Musafir kehabisan</p></div></div>
                    </div>
                </div>

                <h2 class="text-[0.7rem] font-bold text-gray-400 uppercase tracking-widest mb-3">FAQ Zakat</h2>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm mb-6">
                    <div class="border-b border-gray-50">
                        <button onclick="toggleAccordion(this)" class="w-full flex justify-between items-center p-4 text-left">
                            <span class="text-[0.85rem] font-semibold pr-4">Apa bedanya zakat fitrah dan zakat maal?</span>
                            <span class="accordion-arrow text-gray-400 flex-shrink-0 text-lg">⌄</span>
                        </button>
                        <div class="accordion-content px-4 text-[0.8rem] text-gray-500 leading-relaxed">
                            <p class="pb-4">Zakat <b>fitrah</b> wajib dikeluarkan setiap Muslim menjelang Idul Fitri, setara 2,5 kg beras. Sementara zakat <b>maal</b> dikeluarkan atas harta yang telah mencapai nisab dan dimiliki selama 1 tahun penuh.</p>
                        </div>
                    </div>
                    <div class="border-b border-gray-50">
                        <button onclick="toggleAccordion(this)" class="w-full flex justify-between items-center p-4 text-left">
                            <span class="text-[0.85rem] font-semibold pr-4">Kapan batas waktu bayar zakat fitrah?</span>
                            <span class="accordion-arrow text-gray-400 flex-shrink-0 text-lg">⌄</span>
                        </button>
                        <div class="accordion-content px-4 text-[0.8rem] text-gray-500 leading-relaxed">
                            <p class="pb-4">Zakat fitrah wajib ditunaikan sebelum shalat Idul Fitri. Waktu yang paling afdhal adalah malam atau pagi hari sebelum shalat Id dilaksanakan.</p>
                        </div>
                    </div>
                    <div class="border-b border-gray-50">
                        <button onclick="toggleAccordion(this)" class="w-full flex justify-between items-center p-4 text-left">
                            <span class="text-[0.85rem] font-semibold pr-4">Apakah zakat online sah secara syariat?</span>
                            <span class="accordion-arrow text-gray-400 flex-shrink-0 text-lg">⌄</span>
                        </button>
                        <div class="accordion-content px-4 text-[0.8rem] text-gray-500 leading-relaxed">
                            <p class="pb-4">Ya, zakat online sah selama disalurkan melalui lembaga amil zakat (LAZ) yang resmi dan terpercaya. MUI telah mengeluarkan fatwa yang membolehkan pembayaran zakat secara digital.</p>
                        </div>
                    </div>
                    <div>
                        <button onclick="toggleAccordion(this)" class="w-full flex justify-between items-center p-4 text-left">
                            <span class="text-[0.85rem] font-semibold pr-4">Bagaimana cara menghitung nisab zakat maal?</span>
                            <span class="accordion-arrow text-gray-400 flex-shrink-0 text-lg">⌄</span>
                        </button>
                        <div class="accordion-content px-4 text-[0.8rem] text-gray-500 leading-relaxed">
                            <p class="pb-4">Nisab zakat maal setara 85 gram emas. Jika harga emas saat ini Rp 1.050.000/gram, maka nisabnya = 85 × Rp 1.050.000 = <b>Rp 89.250.000</b>. Jika hartamu sudah melebihi angka ini dan sudah dimiliki 1 tahun, wajib zakat 2,5%.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom nav -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
            <a href="index.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors"><span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda</a>
            <a href="donasi.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors"><span class="text-[1.4rem] block mb-0.5">💰</span>Donasi</a>
            <a href="riwayat.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors"><span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat</a>
            <a href="akun.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors"><span class="text-[1.4rem] block mb-0.5">👤</span>Akun</a>
        </nav>

        <script src="zakat.js"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-HT_tSHO7ipGR_kQ8"></script>
    </body>
</html>
