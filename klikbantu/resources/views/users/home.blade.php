<!DOCTYPE html>
<html lang="id">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>KlikBantu - Beranda</title>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
      <style>body { font-family: 'Poppins', sans-serif; }</style>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-[#f8f9fa] text-[#333]">
    <div class="flex min-h-screen">
        @include('components.sidebar')
        <div class="flex-1">
          <!-- Header -->
          <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
              <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors mr-3">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
              </button>
              <div>
                  <h1 class="text-lg font-bold text-gray-800">Selamat Datang, {{ $user->nama ?? 'Donatur' }} 👋</h1>
                  <p class="text-xs text-gray-400">Mari lakukan kebaikan hari ini</p>
              </div>
              <div class="flex items-center gap-3">
                  <a href="donasi.php" class="hidden sm:flex items-center gap-2 bg-[#00aa13] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#009911] transition-colors shadow-md">
                      <span>💰</span> Donasi Sekarang
                  </a>
                  <a href="akun.php">
                    <img src="{{ $user->avatar 
                        ? asset('assets/img/avatars/'.$user->avatar) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'U').'&background=00aa13&color=fff' }}"
                        
                        class="w-10 h-10 rounded-full object-cover">                    
                  </a>
              </div>
          </header>

          <main class="p-6 lg:p-8">
              <!-- Hero Banner -->
              <div class="bg-gradient-to-br from-[#00aa13] to-[#006600] rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
                  <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full"></div>
                  <div class="absolute right-20 bottom-0 w-24 h-24 bg-white/5 rounded-full"></div>
                  <div class="relative z-10 max-w-lg">
                    <h2 class="text-2xl lg:text-3xl font-bold mb-2 leading-tight">Donasi Bikin Urusan Lancar 🌟</h2>
                    <p class="text-white/80 mb-6 text-sm">Lakukan kebaikan setiap harinya. Setiap donasi kamu membawa perubahan nyata.</p>
                    <div class="flex gap-3 flex-wrap">
                        <a href="{{ url('donasi') }}" class="bg-white text-[#006600] font-bold py-2.5 px-6 rounded-xl text-sm hover:bg-gray-50 transition-colors shadow-lg">
                            Donasi Sekarang
                        </a>
                        <a href="{{ url('zakat') }}" class="bg-white/20 text-white font-semibold py-2.5 px-6 rounded-xl text-sm hover:bg-white/30 transition-colors">
                            Hitung Zakat
                        </a>
                    </div>
                  </div>
              </div>

              <!-- Stats Cards -->
              <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                  <div class="bg-gradient-to-br from-[#00aa13] to-[#006600] text-white rounded-2xl p-5 shadow-md">
                      <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl mb-3">💰</div>
                      <p class="text-xl font-bold mb-0.5">Rp {{ number_format($statsDonasi->total ?? 0, 0, ',', '.') }}</p>
                      <p class="text-xs text-white/80">Total Donasi</p>
                  </div>
                  <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-50">
                      <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xl mb-3">🕌</div>
                      <p class="text-xl font-bold text-gray-800 mb-0.5">Rp {{ number_format($statsZakat->total ?? 0, 0, ',', '.') }}</p>
                      <p class="text-xs text-gray-400">Total Zakat</p>
                  </div>
                  <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-50">
                      <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-xl mb-3">🤝</div>
                      <p class="text-xl font-bold text-gray-800 mb-0.5">{{ $statsDonasi->cnt ?? 0 }}</p>
                      <p class="text-xs text-gray-400">Kali Berdonasi</p>
                  </div>
                  <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-50">
                      <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-xl mb-3">📊</div>
                      <p class="text-xl font-bold text-gray-800 mb-0.5">{{ $campaigns->count() }}</p>
                      <p class="text-xs text-gray-400">Campaign Aktif</p>
                  </div>
              </div>

              <!-- Quick Actions -->
              <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50 mb-8">
                <h2 class="text-base font-bold text-gray-800 mb-5">Mau berbuat kebaikan apa hari ini?</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ url('donasi') }}" class="flex flex-col items-center gap-3 p-5 bg-[#f0fff0] rounded-2xl border-2 border-[#00aa13]/20 hover:border-[#00aa13] hover:shadow-md transition-all group cursor-pointer">
                        <span class="text-3xl group-hover:scale-110 transition-transform">💰</span>
                        <span class="text-sm font-semibold text-gray-700">Donasi</span>
                    </a>
                    <a href="{{ route('zakat.index') }}" class="flex flex-col items-center gap-3 p-5 bg-blue-50 rounded-2xl border-2 border-blue-100 hover:border-blue-400 hover:shadow-md transition-all group cursor-pointer">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🕌</span>
                        <span class="text-sm font-semibold text-gray-700">Zakat</span>
                    </a>
                    <a href="{{ url('riwayat') }}" class="flex flex-col items-center gap-3 p-5 bg-amber-50 rounded-2xl border-2 border-amber-100 hover:border-amber-400 hover:shadow-md transition-all group cursor-pointer">
                        <span class="text-3xl group-hover:scale-110 transition-transform">📜</span>
                        <span class="text-sm font-semibold text-gray-700">Riwayat</span>
                    </a>
                    <a href="https://forms.gle/pwYoMXbzDrAjW71N6" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-3 p-5 bg-purple-50 rounded-2xl border-2 border-purple-100 hover:border-purple-400 hover:shadow-md transition-all group cursor-pointer">
                        <span class="text-3xl group-hover:scale-110 transition-transform">📝</span>
                        <span class="text-sm font-semibold text-gray-700">Ajukan Campaign</span>
                    </a>
                </div>
              </div>

              <!-- Campaign Section -->
              <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800">Campaign Mendesak 🔥</h2>
                <a href="{{ url('donasi') }}" class="text-sm text-[#00aa13] font-semibold hover:underline">Lihat Semua →</a>
              </div>

              <!-- Campaign Cards -->
              @if($campaigns->isEmpty())
              <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="text-5xl mb-4">🔍</div>
                <p class="text-gray-400 font-semibold">Belum ada campaign aktif saat ini.</p>
              </div>
              @else
              <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($campaigns as $c)
                    @php
                        $percent = ($c->target_dana > 0) 
                            ? min(100, ($c->dana_terkumpul / $c->target_dana) * 100) 
                            : 0;

                        $gambarSrc = !empty($c->gambar) && file_exists(public_path('assets/img/campaigns/' . $c->gambar))
                            ? asset('assets/img/campaigns/' . $c->gambar)
                            : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=400&q=80';
                    @endphp

                  <div class="campaign-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 flex flex-col">
                    <div class="relative">
                      <img src="{{ $gambarSrc }}" class="w-full h-48 object-cover" alt="{{ $c->nama }}">
                      <span class="absolute top-3 left-3 bg-[#00aa13] text-white text-[0.65rem] font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                          {{ $c->kategori }}
                      </span>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                      <h3 class="text-[0.95rem] font-bold mb-3 leading-tight flex-1">
                          {{ $c->nama }}
                      </h3>

                      <div class="bg-gray-100 h-2 rounded-full mb-2">
                        <div class="bg-[#00aa13] h-full rounded-full" style="width: {{ $percent }}%"></div>
                      </div>

                      <div class="flex justify-between text-xs text-gray-400 mb-4">
                        <span>
                            Terkumpul: 
                            <b class="text-gray-700">
                              Rp {{ number_format($c->dana_terkumpul, 0, ',', '.') }}
                            </b>
                        </span>
                        <span class="font-semibold text-[#00aa13]">
                          {{ round($percent) }}%
                        </span>
                      </div>

                        <a href="{{ url('form-donasi/'.$c->id) }}" 
                          class="block text-center bg-[#00aa13] text-white font-bold py-2.5 rounded-xl text-sm hover:bg-[#009911]">
                            Donasi Sekarang
                        </a>
                    </div>
                  </div>
                @endforeach
              </div>
              @endif
          </main>
        </div>
    </div>
  </body>
</html>
