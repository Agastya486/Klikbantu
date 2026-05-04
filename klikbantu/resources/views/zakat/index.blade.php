@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KlikBantu - Zakat</title>

        <link href="{{ asset('src/output.css') }}" rel="stylesheet">
        <link href="{{ asset('src/style.css') }}" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Poppins', sans-serif; }
            .tab-active { background-color: #00aa13; color: white; }
            .tab-inactive { background-color: transparent; color: rgba(255,255,255,0.7); }
            .tab-active-desktop { background-color: #00aa13; color: white; border-color: #00aa13; }
            .zakat-card-active { border-color: #00aa13 !important; background-color: #f0fff0 !important; }
            .accordion-content { display: none; }
            .accordion-content.open { display: block; }
            .accordion-arrow.open { transform: rotate(180deg); }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-[#f8f9fa] text-[#333]">

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        @include('components.sidebar')

        <div class="main-content">

            {{-- TOPBAR --}}
            <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-4 sticky top-0 z-30 shadow-sm">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div class="flex-1">
                    <h1 class="text-lg font-bold text-gray-800">Zakat 🕌</h1>
                    <p class="text-xs text-gray-400">Tunaikan kewajiban zakatmu dengan mudah dan tepat sasaran</p>
                </div>

                <a href="{{ url('akun') }}">
                    <img 
                        src="{{ $user && $user->avatar 
                            ? asset('assets/img/avatars/'.$user->avatar) 
                            : 'https://ui-avatars.com/api/?name='.urlencode($user->nama ?? 'U').'&background=00aa13&color=fff' }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-[#00aa13]/30">
                </a>
            </header>

            <main class="p-6 lg:p-8">

                {{-- TAB NAV --}}
                <div class="flex gap-2 mb-8 bg-white border border-gray-100 rounded-2xl p-1.5 shadow-sm w-fit">
                    <button onclick="switchTab('hitung')" id="tab-hitung"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all tab-active-desktop cursor-pointer">
                        🧮 Hitung Zakat
                    </button>
                    <button onclick="switchTab('bayar')" id="tab-bayar"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all text-gray-500 hover:bg-gray-100 cursor-pointer">
                        💳 Bayar Zakat
                    </button>
                    <button onclick="switchTab('info')" id="tab-info"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all text-gray-500 hover:bg-gray-100 cursor-pointer">
                        📖 Info Zakat
                    </button>
                </div>

                {{-- ================= TAB HITUNG ================= --}}
                <div id="content-hitung">
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <!-- Left: Pilih Jenis Zakat -->
                        <div class="xl:col-span-1">
                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-50">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Pilih Jenis Zakat</p>
                                <div class="grid grid-cols-2 xl:grid-cols-1 gap-2">
                                    @php
                                    $zakatTypes = [
                                        ['id'=>'fitrah','emoji'=>'🌙','label'=>'Zakat Fitrah','desc'=>'2,5 kg beras per jiwa'],
                                        ['id'=>'maal','emoji'=>'💰','label'=>'Zakat Maal','desc'=>'2,5% dari total harta'],
                                        ['id'=>'penghasilan','emoji'=>'💼','label'=>'Zakat Penghasilan','desc'=>'2,5% dari penghasilan'],
                                        ['id'=>'tabungan','emoji'=>'🏦','label'=>'Zakat Tabungan','desc'=>'2,5% dari saldo'],
                                        ['id'=>'emas','emoji'=>'🥇','label'=>'Zakat Emas','desc'=>'2,5% dari nilai emas'],
                                        ['id'=>'perusahaan','emoji'=>'🏢','label'=>'Zakat Perusahaan','desc'=>'2,5% dari aset bersih'],
                                    ];
                                    @endphp
                                    @foreach($zakatTypes as $i => $zt)
                                    <button onclick="selectZakat(this,'{{ $zt['id'] }}')"
                                        class="zakat-type-card flex items-center gap-3 bg-gray-50 border-2 border-transparent rounded-xl p-3 transition-all hover:bg-[#f0fff0] cursor-pointer text-left {{ $i===0 ? 'zakat-card-active' : '' }}">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm text-xl">{{ $zt['emoji'] }}</div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $zt['label'] }}</p>
                                            <p class="text-xs text-gray-400">{{ $zt['desc'] }}</p>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Right: Kalkulator -->
                        <div class="xl:col-span-2">

                            <!-- Kalkulator Fitrah -->
                            <div id="calc-fitrah" class="calc-section bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Kalkulator Zakat Fitrah 🌙</h3>
                                <p class="text-sm text-gray-400 mb-6">Setara 2,5 kg beras per jiwa</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Jumlah Jiwa</label>
                                        <div class="flex items-center gap-4">
                                            <button type="button" onclick="changeJiwa(-1)" class="w-12 h-12 rounded-xl bg-gray-100 text-2xl font-bold flex items-center justify-center hover:bg-gray-200 transition-colors cursor-pointer">−</button>
                                            <span id="jumlah-jiwa" class="text-3xl font-bold text-[#00aa13] w-10 text-center">1</span>
                                            <button type="button" onclick="changeJiwa(1)" class="w-12 h-12 rounded-xl bg-gray-100 text-2xl font-bold flex items-center justify-center hover:bg-gray-200 transition-colors cursor-pointer">+</button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Harga Beras per Kg</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" id="harga-beras" value="15000" oninput="hitungFitrah()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 bg-gradient-to-r from-[#f0fff0] to-[#e6ffe6] rounded-xl p-5 flex justify-between items-center border border-green-100">
                                    <div>
                                        <p class="text-sm text-gray-500">Total Zakat Fitrah</p>
                                        <p id="result-fitrah" class="text-3xl font-bold text-[#00aa13]">Rp 37.500</p>
                                    </div>
                                    <button type="button" onclick="bukaBayarDariKalkulator('fitrah', document.getElementById('result-fitrah').textContent)" class="bg-[#00aa13] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#009911] transition-colors cursor-pointer shadow-md">Bayar Sekarang →</button>
                                </div>
                            </div>

                            <!-- Kalkulator Maal -->
                            <div id="calc-maal" class="calc-section hidden bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Kalkulator Zakat Maal 💰</h3>
                                <p class="text-sm text-gray-400 mb-6">Zakat 2,5% jika harta ≥ nisab (85gr emas)</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Total Harta (Rp)</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" id="total-harta" placeholder="0" oninput="hitungMaal()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Harga Emas per Gram</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" id="harga-emas-maal" value="1050000" oninput="hitungMaal()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                        </div>
                                    </div>
                                </div>
                                <div id="result-maal-box" class="hidden mt-6 bg-gradient-to-r from-[#f0fff0] to-[#e6ffe6] rounded-xl p-5 flex justify-between items-center border border-green-100">
                                    <div>
                                        <p class="text-sm text-gray-500">Zakat Maal (2,5%)</p>
                                        <p id="result-maal" class="text-3xl font-bold text-[#00aa13]">Rp 0</p>
                                    </div>
                                    <button type="button" onclick="bukaBayarDariKalkulator('maal', document.getElementById('result-maal').textContent)" class="bg-[#00aa13] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#009911] transition-colors cursor-pointer shadow-md">Bayar Sekarang →</button>
                                </div>
                                <div id="maal-tidak-wajib" class="hidden mt-6 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                    <p class="text-sm text-yellow-700 font-semibold">⚠️ Harta belum mencapai nisab</p>
                                    <p id="maal-nisab-info" class="text-xs text-yellow-600 mt-1"></p>
                                </div>
                            </div>

                            <!-- Kalkulator Penghasilan -->
                            <div id="calc-penghasilan" class="calc-section hidden bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Kalkulator Zakat Penghasilan 💼</h3>
                                <p class="text-sm text-gray-400 mb-6">2,5% dari penghasilan jika ≥ nisab (520kg beras)</p>
                                <div>
                                    <label class="text-sm font-semibold text-gray-500 mb-3 block">Penghasilan per Bulan</label>
                                    <div class="relative max-w-sm">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="number" id="penghasilan" placeholder="0" oninput="hitungPenghasilan()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                    </div>
                                </div>
                                <div id="result-penghasilan-box" class="hidden mt-6 bg-gradient-to-r from-[#f0fff0] to-[#e6ffe6] rounded-xl p-5 flex justify-between items-center border border-green-100">
                                    <div>
                                        <p class="text-sm text-gray-500">Zakat per Bulan</p>
                                        <p id="result-penghasilan" class="text-3xl font-bold text-[#00aa13]">Rp 0</p>
                                    </div>
                                    <button type="button" onclick="bukaBayarDariKalkulator('penghasilan', document.getElementById('result-penghasilan').textContent)" class="bg-[#00aa13] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#009911] transition-colors cursor-pointer shadow-md">Bayar Sekarang →</button>
                                </div>
                                <div id="penghasilan-tidak-wajib" class="hidden mt-6 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                    <p class="text-sm text-yellow-700 font-semibold">⚠️ Penghasilan belum mencapai nisab</p>
                                    <p class="text-xs text-yellow-600 mt-1">Nisab zakat penghasilan ≈ Rp 7.800.000/bulan</p>
                                </div>
                            </div>

                            <!-- Kalkulator Tabungan -->
                            <div id="calc-tabungan" class="calc-section hidden bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Kalkulator Zakat Tabungan 🏦</h3>
                                <p class="text-sm text-gray-400 mb-6">2,5% dari saldo jika ≥ nisab & sudah 1 tahun (haul)</p>
                                <div>
                                    <label class="text-sm font-semibold text-gray-500 mb-3 block">Saldo Tabungan</label>
                                    <div class="relative max-w-sm">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="number" id="saldo-tabungan" placeholder="0" oninput="hitungTabungan()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                    </div>
                                </div>
                                <div id="result-tabungan-box" class="hidden mt-6 bg-gradient-to-r from-[#f0fff0] to-[#e6ffe6] rounded-xl p-5 flex justify-between items-center border border-green-100">
                                    <div>
                                        <p class="text-sm text-gray-500">Zakat Tabungan (2,5%)</p>
                                        <p id="result-tabungan" class="text-3xl font-bold text-[#00aa13]">Rp 0</p>
                                    </div>
                                    <button type="button" onclick="bukaBayarDariKalkulator('tabungan', document.getElementById('result-tabungan').textContent)" class="bg-[#00aa13] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#009911] transition-colors cursor-pointer shadow-md">Bayar Sekarang →</button>
                                </div>
                                <div id="tabungan-tidak-wajib" class="hidden mt-6 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                    <p class="text-sm text-yellow-700 font-semibold">⚠️ Tabungan belum mencapai nisab</p>
                                    <p class="text-xs text-yellow-600 mt-1">Nisab zakat tabungan ≈ Rp 89.250.000</p>
                                </div>
                            </div>

                            <!-- Kalkulator Emas -->
                            <div id="calc-emas" class="calc-section hidden bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Kalkulator Zakat Emas 🥇</h3>
                                <p class="text-sm text-gray-400 mb-6">2,5% dari nilai emas jika ≥ 85 gram & sudah 1 tahun</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Berat Emas (gram)</label>
                                        <input type="number" id="berat-emas" placeholder="0" oninput="hitungEmas()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Harga Emas per Gram</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" id="harga-emas" value="1050000" oninput="hitungEmas()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                        </div>
                                    </div>
                                </div>
                                <div id="result-emas-box" class="hidden mt-6 bg-gradient-to-r from-[#f0fff0] to-[#e6ffe6] rounded-xl p-5 flex justify-between items-center border border-green-100">
                                    <div>
                                        <p class="text-sm text-gray-500">Zakat Emas (2,5%)</p>
                                        <p id="result-emas" class="text-3xl font-bold text-[#00aa13]">Rp 0</p>
                                    </div>
                                    <button type="button" onclick="bukaBayarDariKalkulator('emas', document.getElementById('result-emas').textContent)" class="bg-[#00aa13] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#009911] transition-colors cursor-pointer shadow-md">Bayar Sekarang →</button>
                                </div>
                                <div id="emas-tidak-wajib" class="hidden mt-6 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                    <p class="text-sm text-yellow-700 font-semibold">⚠️ Emas belum mencapai nisab (85 gram)</p>
                                </div>
                            </div>

                            <!-- Kalkulator Perusahaan -->
                            <div id="calc-perusahaan" class="calc-section hidden bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Kalkulator Zakat Perusahaan 🏢</h3>
                                <p class="text-sm text-gray-400 mb-6">2,5% dari (aset lancar − kewajiban jangka pendek)</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Aset Lancar</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" id="aset-lancar" placeholder="0" oninput="hitungPerusahaan()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-500 mb-3 block">Kewajiban Jangka Pendek</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" id="kewajiban" placeholder="0" oninput="hitungPerusahaan()" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15">
                                        </div>
                                    </div>
                                </div>
                                <div id="result-perusahaan-box" class="hidden mt-6 bg-gradient-to-r from-[#f0fff0] to-[#e6ffe6] rounded-xl p-5 flex justify-between items-center border border-green-100">
                                    <div>
                                        <p class="text-sm text-gray-500">Zakat Perusahaan (2,5%)</p>
                                        <p id="result-perusahaan" class="text-3xl font-bold text-[#00aa13]">Rp 0</p>
                                    </div>
                                    <button type="button" onclick="bukaBayarDariKalkulator('perusahaan', document.getElementById('result-perusahaan').textContent)" class="bg-[#00aa13] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#009911] transition-colors cursor-pointer shadow-md">Bayar Sekarang →</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ================= TAB BAYAR ================= --}}
                <div id="content-bayar" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <!-- Form Bayar -->
                        <div class="lg:col-span-3">
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <h2 class="font-bold text-lg text-gray-800 mb-6">Form Pembayaran Zakat 🤲</h2>
                                <form id="form-pembayaran-zakat" onsubmit="event.preventDefault(); prosesZakat();" class="space-y-5">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600 mb-2 block">Nama Muzakki</label>
                                        <input type="text" name="nama_muzakki" id="nama-muzakki" placeholder="Nama lengkap kamu" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15 transition-all" required>
                                    </div>
                                    <div>
                                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 hover:text-[#00aa13] transition-colors">
                                            <input type="checkbox" name="anonim" id="checkbox-anonim-zakat" class="w-4 h-4 accent-[#00aa13] cursor-pointer">
                                            Bayar sebagai Anonim
                                        </label>
                                        <p id="msg-anonim-zakat" class="hidden text-xs text-[#00aa13] font-medium mt-1 ml-6 italic">✨ Nama kamu akan dirahasiakan dari publik.</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600 mb-2 block">Jenis Zakat</label>
                                        <select name="jenis_zakat" id="jenis-zakat-bayar" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] cursor-pointer">
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
                                        <label class="text-sm font-semibold text-gray-600 mb-2 block">Jumlah Zakat</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" name="jumlah_zakat" id="jumlah-zakat-bayar" placeholder="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#00aa13] focus:ring-2 focus:ring-[#00aa13]/15 transition-all" required>
                                        </div>
                                    </div>
                                    <!-- Nominal cepat -->
                                    <div>
                                        <p class="text-sm font-semibold text-gray-500 mb-2">Nominal Cepat</p>
                                        <div class="grid grid-cols-3 gap-2">
                                            @php $nominals = [50000,100000,200000,500000,1000000,2500000]; @endphp
                                            @foreach($nominals as $nom)
                                            <button type="button" onclick="setNominal({{ $nom }})" class="bg-[#f0fff0] text-[#006600] text-xs font-semibold py-2.5 rounded-xl border-2 border-green-100 hover:border-[#00aa13] transition-all cursor-pointer">
                                                Rp {{ $nom >= 1000000 ? number_format($nom/1000000,1).'jt' : number_format($nom/1000).'rb' }}
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-[#00aa13] text-white font-bold py-4 rounded-xl text-sm hover:bg-[#009911] transition-colors shadow-md cursor-pointer mt-2">
                                        Bayar Zakat Sekarang 🤲
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Disalurkan melalui -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Disalurkan Melalui</p>
                                <div class="space-y-3">
                                    @php
                                    $lembaga = [
                                        ['emoji'=>'🕌','name'=>'Baznas','desc'=>'Badan Amil Zakat Nasional'],
                                        ['emoji'=>'🌿','name'=>'Dompet Dhuafa','desc'=>'LAZ Terpercaya Nasional'],
                                        ['emoji'=>'💚','name'=>'LAZ Muhammadiyah','desc'=>'Lembaga Amil Zakat'],
                                        ['emoji'=>'☪️','name'=>'Yatim Mandiri','desc'=>'Peduli Anak Yatim'],
                                    ];
                                    @endphp
                                    @foreach($lembaga as $l)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                        <span class="text-2xl">{{ $l['emoji'] }}</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $l['name'] }}</p>
                                            <p class="text-xs text-gray-400">{{ $l['desc'] }}</p>
                                        </div>
                                        <span class="ml-auto text-xs bg-green-50 text-green-700 font-semibold px-2 py-1 rounded-full">Resmi</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= TAB INFO ================= --}}
                <div id="content-info" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left -->
                        <div class="lg:col-span-2 space-y-5">
                            <!-- Ayat -->
                            <div class="bg-gradient-to-r from-[#00aa13] to-[#006600] rounded-2xl p-6 text-white">
                                <p class="text-xs opacity-70 mb-2">Firman Allah SWT</p>
                                <p class="text-base font-semibold leading-relaxed mb-2">"Ambillah zakat dari sebagian harta mereka... dan doakanlah mereka."</p>
                                <p class="text-xs opacity-70">— QS. At-Taubah: 103</p>
                            </div>

                            <!-- 8 Asnaf -->
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">8 Golongan Penerima (Asnaf)</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @php
                                    $asnaf = [
                                        ['🤲','Fakir','Tidak punya harta'],['🏚️','Miskin','Harta tidak cukup'],
                                        ['👷','Amil','Pengelola zakat'],['💚','Mualaf','Baru masuk Islam'],
                                        ['⛓️','Riqab','Membebaskan budak'],['💸','Gharimin','Terlilit hutang'],
                                        ['☪️','Fi Sabilillah','Pejuang Islam'],['🧳','Ibnu Sabil','Musafir kehabisan'],
                                    ];
                                    @endphp
                                    @foreach($asnaf as $a)
                                    <div class="bg-[#f0fff0] rounded-xl p-3 text-center">
                                        <span class="text-2xl block mb-1">{{ $a[0] }}</span>
                                        <p class="text-xs font-bold text-gray-800">{{ $a[1] }}</p>
                                        <p class="text-[0.65rem] text-gray-400 mt-0.5">{{ $a[2] }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- FAQ -->
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50">
                                <div class="px-6 py-4 border-b border-gray-50">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">FAQ Zakat</p>
                                </div>
                                @php
                                $faqs = [
                                    ['Apa bedanya zakat fitrah dan zakat maal?','Zakat <b>fitrah</b> wajib dikeluarkan setiap Muslim menjelang Idul Fitri, setara 2,5 kg beras. Sementara zakat <b>maal</b> dikeluarkan atas harta yang telah mencapai nisab dan dimiliki selama 1 tahun penuh.'],
                                    ['Kapan batas waktu bayar zakat fitrah?','Zakat fitrah wajib ditunaikan sebelum shalat Idul Fitri. Waktu yang paling afdhal adalah malam atau pagi hari sebelum shalat Id dilaksanakan.'],
                                    ['Apakah zakat online sah secara syariat?','Ya, zakat online sah selama disalurkan melalui lembaga amil zakat (LAZ) yang resmi dan terpercaya. MUI telah mengeluarkan fatwa yang membolehkan pembayaran zakat secara digital.'],
                                    ['Bagaimana cara menghitung nisab zakat maal?','Nisab zakat maal setara 85 gram emas. Jika harga emas saat ini Rp 1.050.000/gram, maka nisabnya = 85 × Rp 1.050.000 = <b>Rp 89.250.000</b>. Jika hartamu sudah melebihi angka ini dan sudah dimiliki 1 tahun, wajib zakat 2,5%.'],
                                ];
                                @endphp
                                @foreach($faqs as $faq)
                                <div class="border-b border-gray-50 last:border-0">
                                    <button type="button" onclick="toggleAccordion(this)" class="w-full flex justify-between items-center px-6 py-4 text-left hover:bg-gray-50 transition-colors">
                                        <span class="text-sm font-semibold text-gray-800 pr-4">{{ $faq[0] }}</span>
                                        <span class="accordion-arrow text-gray-400 flex-shrink-0 text-lg">⌄</span>
                                    </button>
                                    <div class="accordion-content">
                                        <p class="px-6 pb-4 text-sm text-gray-500 leading-relaxed">{!! $faq[1] !!}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Right: Stats -->
                        <div class="space-y-4">
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Fakta Zakat</p>
                                <div class="space-y-4">
                                    <div class="text-center bg-[#f0fff0] rounded-xl p-4">
                                        <p class="text-3xl font-bold text-[#00aa13]">8</p>
                                        <p class="text-xs text-gray-500 mt-1">Golongan Penerima (Asnaf)</p>
                                    </div>
                                    <div class="text-center bg-gray-50 rounded-xl p-4">
                                        <p class="text-3xl font-bold text-gray-800">2,5%</p>
                                        <p class="text-xs text-gray-500 mt-1">Kadar Zakat Maal</p>
                                    </div>
                                    <div class="text-center bg-gray-50 rounded-xl p-4">
                                        <p class="text-3xl font-bold text-gray-800">85gr</p>
                                        <p class="text-xs text-gray-500 mt-1">Nisab Emas</p>
                                    </div>
                                    <div class="text-center bg-gray-50 rounded-xl p-4">
                                        <p class="text-3xl font-bold text-gray-800">1 Thn</p>
                                        <p class="text-xs text-gray-500 mt-1">Haul (Masa Kepemilikan)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="{{ asset('zakat.js') }}"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
    function prosesZakat() {
        const data = {
            nama_muzakki: document.getElementById('nama-muzakki').value,
            jenis_zakat: document.getElementById('jenis-zakat-bayar').value,
            jumlah_zakat: parseInt(document.getElementById('jumlah-zakat-bayar').value),
            is_anonim: document.getElementById('checkbox-anonim-zakat')?.checked ? 1 : 0
        };
        
        if (!data.jenis_zakat) {
            alert('Pilih jenis zakat terlebih dahulu!');
            return;
        }
        
        if (!data.jumlah_zakat || data.jumlah_zakat < 10000) {
            alert('Minimal pembayaran zakat Rp 10.000');
            return;
        }
        
        const submitBtn = document.querySelector('#form-pembayaran-zakat button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Memproses...';
        submitBtn.disabled = true;
        
        // gunanya untuk memastikan snap.js sudah terload sebelum memanggil snap.pay()
        const url = '/zakat/create-transaction';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Pastikan snap.js sudah terload sebelum memanggil snap.pay()
                if (typeof snap === 'undefined') {
                    alert('Midtrans Snap tidak terload. Silakan refresh halaman.');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }
                
                snap.pay(result.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);
                        window.location.href = '{{ route("zakat.history") }}';
                    },
                    onPending: function(result) {
                        console.log('Payment Pending:', result);
                        alert('Pembayaran pending, silakan selesaikan pembayaran');
                        window.location.href = '{{ route("zakat.history") }}';
                    },
                    onError: function(result) {
                        console.error('Payment Error:', result);
                        alert('Pembayaran gagal: ' + JSON.stringify(result));
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            } else {
                alert('Error: ' + result.error);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
    // Tab switching
    function switchTab(tab) {
        document.getElementById('content-hitung').classList.add('hidden');
        document.getElementById('content-bayar').classList.add('hidden');
        document.getElementById('content-info').classList.add('hidden');
        
        document.getElementById('tab-hitung').classList.remove('tab-active-desktop', 'bg-[#00aa13]', 'text-white');
        document.getElementById('tab-bayar').classList.remove('tab-active-desktop', 'bg-[#00aa13]', 'text-white');
        document.getElementById('tab-info').classList.remove('tab-active-desktop', 'bg-[#00aa13]', 'text-white');
        
        document.getElementById('tab-hitung').classList.add('text-gray-500');
        document.getElementById('tab-bayar').classList.add('text-gray-500');
        document.getElementById('tab-info').classList.add('text-gray-500');
        
        document.getElementById('content-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.classList.add('tab-active-desktop');
        activeBtn.classList.remove('text-gray-500');
    }

    // Select Zakat Type
    function selectZakat(btn, type) {
        document.querySelectorAll('.zakat-type-card').forEach(c => c.classList.remove('zakat-card-active'));
        btn.classList.add('zakat-card-active');
        
        document.querySelectorAll('.calc-section').forEach(s => s.classList.add('hidden'));
        const calc = document.getElementById('calc-' + type);
        if (calc) calc.classList.remove('hidden');
        
        // Trigger initial calculation for the selected type
        if (type === 'fitrah') hitungFitrah();
        else if (type === 'maal') hitungMaal();
        else if (type === 'penghasilan') hitungPenghasilan();
        else if (type === 'tabungan') hitungTabungan();
        else if (type === 'emas') hitungEmas();
        else if (type === 'perusahaan') hitungPerusahaan();
    }

    // Fitrah Calculator
    function hitungFitrah() {
        let jiwa = parseInt(document.getElementById('jumlah-jiwa').innerText) || 1;
        let hargaBeras = parseInt(document.getElementById('harga-beras').value) || 0;
        let total = jiwa * 2.5 * hargaBeras;
        document.getElementById('result-fitrah').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function changeJiwa(change) {
        let jiwaSpan = document.getElementById('jumlah-jiwa');
        let jiwa = parseInt(jiwaSpan.innerText) || 1;
        let newJiwa = jiwa + change;
        if (newJiwa >= 1) {
            jiwaSpan.innerText = newJiwa;
            hitungFitrah();
        }
    }

    // Maal Calculator
    function hitungMaal() {
        let totalHarta = parseInt(document.getElementById('total-harta').value) || 0;
        let hargaEmas = parseInt(document.getElementById('harga-emas-maal').value) || 1050000;
        let nisab = 85 * hargaEmas;
        let zakat = totalHarta * 0.025;
        
        if (totalHarta >= nisab) {
            document.getElementById('result-maal-box').classList.remove('hidden');
            document.getElementById('maal-tidak-wajib').classList.add('hidden');
            document.getElementById('result-maal').innerText = 'Rp ' + zakat.toLocaleString('id-ID');
        } else {
            document.getElementById('result-maal-box').classList.add('hidden');
            document.getElementById('maal-tidak-wajib').classList.remove('hidden');
            document.getElementById('maal-nisab-info').innerHTML = 'Nisab zakat maal = 85 gram × Rp ' + hargaEmas.toLocaleString('id-ID') + ' = <b>Rp ' + nisab.toLocaleString('id-ID') + '</b>';
        }
    }

    // Penghasilan Calculator
    function hitungPenghasilan() {
        let penghasilan = parseInt(document.getElementById('penghasilan').value) || 0;
        let nisab = 7800000;
        let zakat = penghasilan * 0.025;
        
        if (penghasilan >= nisab) {
            document.getElementById('result-penghasilan-box').classList.remove('hidden');
            document.getElementById('penghasilan-tidak-wajib').classList.add('hidden');
            document.getElementById('result-penghasilan').innerText = 'Rp ' + zakat.toLocaleString('id-ID');
        } else {
            document.getElementById('result-penghasilan-box').classList.add('hidden');
            document.getElementById('penghasilan-tidak-wajib').classList.remove('hidden');
        }
    }

    // Tabungan Calculator
    function hitungTabungan() {
        let saldo = parseInt(document.getElementById('saldo-tabungan').value) || 0;
        let nisab = 89250000;
        let zakat = saldo * 0.025;
        
        if (saldo >= nisab) {
            document.getElementById('result-tabungan-box').classList.remove('hidden');
            document.getElementById('tabungan-tidak-wajib').classList.add('hidden');
            document.getElementById('result-tabungan').innerText = 'Rp ' + zakat.toLocaleString('id-ID');
        } else {
            document.getElementById('result-tabungan-box').classList.add('hidden');
            document.getElementById('tabungan-tidak-wajib').classList.remove('hidden');
        }
    }

    // Emas Calculator
    function hitungEmas() {
        let berat = parseInt(document.getElementById('berat-emas').value) || 0;
        let hargaEmas = parseInt(document.getElementById('harga-emas').value) || 1050000;
        let nisab = 85;
        let nilaiEmas = berat * hargaEmas;
        let zakat = nilaiEmas * 0.025;
        
        if (berat >= nisab) {
            document.getElementById('result-emas-box').classList.remove('hidden');
            document.getElementById('emas-tidak-wajib').classList.add('hidden');
            document.getElementById('result-emas').innerText = 'Rp ' + zakat.toLocaleString('id-ID');
        } else {
            document.getElementById('result-emas-box').classList.add('hidden');
            document.getElementById('emas-tidak-wajib').classList.remove('hidden');
        }
    }

    // Perusahaan Calculator
    function hitungPerusahaan() {
        let aset = parseInt(document.getElementById('aset-lancar').value) || 0;
        let kewajiban = parseInt(document.getElementById('kewajiban').value) || 0;
        let asetBersih = aset - kewajiban;
        let zakat = asetBersih * 0.025;
        
        if (asetBersih > 0) {
            document.getElementById('result-perusahaan-box').classList.remove('hidden');
            document.getElementById('result-perusahaan').innerText = 'Rp ' + zakat.toLocaleString('id-ID');
        } else {
            document.getElementById('result-perusahaan-box').classList.add('hidden');
        }
    }

    // Accordion
    function toggleAccordion(btn) {
        const content = btn.nextElementSibling;
        const arrow = btn.querySelector('.accordion-arrow');
        content.classList.toggle('open');
        if (arrow) arrow.classList.toggle('open');
    }

    // Anonim zakat
    const cbAnonim = document.getElementById('checkbox-anonim-zakat');
    if (cbAnonim) {
        cbAnonim.addEventListener('change', function() {
            const msgEl = document.getElementById('msg-anonim-zakat');
            const namaEl = document.getElementById('nama-muzakki');
            msgEl.classList.toggle('hidden', !this.checked);
            if (this.checked) {
                namaEl._asli = namaEl.value;
                namaEl.value = 'Hamba Allah';
                namaEl.readOnly = true;
            } else {
                namaEl.value = namaEl._asli || '';
                namaEl.readOnly = false;
            }
        });
    }

    function setNominal(val) {
        const el = document.getElementById('jumlah-zakat-bayar');
        if (el) el.value = val;
    }

    function bukaBayarDariKalkulator(jenis, nominalText) {
        switchTab('bayar');
        const nominalAngka = nominalText.replace(/[^0-9]/g, '');
        const jenisEl = document.getElementById('jenis-zakat-bayar');
        const nominalEl = document.getElementById('jumlah-zakat-bayar');
        if (jenisEl) jenisEl.value = jenis;
        if (nominalEl) nominalEl.value = nominalAngka;
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        hitungFitrah();
    });

    const url = '/zakat/create-transaction';
    console.log('Fetching URL:', url);
    console.log('Current origin:', window.location.origin);
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch(url, {
        // ...
    })
    .catch(error => {
        console.error('Full error:', error);
        alert('Error: ' + error.message + '\nURL: ' + url + '\nOrigin: ' + window.location.origin);
    });
    </script>

    </body>
</html>