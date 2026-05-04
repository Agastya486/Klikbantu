<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Profil Akun</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
  </head>

  <body class="bg-[#f8f9fa] text-[#333]">
    <div class="flex min-h-screen">

      <!-- Sidebar -->
      @include('components.sidebar')

      <div class="flex-1">
        <!-- Header -->
          <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-4 sticky top-0 z-30 shadow-sm">
              <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-gray-100">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                  </svg>
              </button>
              <div>
                  <h1 class="text-lg font-bold text-gray-800">Profil Akun 👤</h1>
                  <p class="text-xs text-gray-400">Kelola informasi akun kamu</p>
              </div>
          </header>

          <main class="p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">

                <!-- Profile Card -->
                <div class="bg-gradient-to-br from-[#00aa13] to-[#006600] rounded-3xl p-8 mb-6 text-white relative overflow-hidden">
                  <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>

                  <div class="relative flex items-center gap-6">
                    <div class="w-24 h-24 rounded-2xl border-4 border-white/30 overflow-hidden shadow-lg flex-shrink-0">
                      <img 
                        src="{{ Auth::user()->avatar 
                          ? asset('assets/img/avatars/'.Auth::user()->avatar) 
                          : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ffffff&color=00aa13&size=200' }}"
                        class="w-full h-full object-cover">
                    </div>

                    <div>
                      <h2 class="text-2xl font-bold">{{ Auth::user()->nama }}</h2>
                      <p class="text-white/80 mb-4">{{ Auth::user()->email }}</p>

                      <a href="{{ route('profile.update') }}"
                        class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-colors">
                          ✏️ Edit Profil
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-50 overflow-hidden mb-6">
                  <div class="px-6 py-4 border-b border-gray-50">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pengaturan Akun</p>
                  </div>

                  <div class="divide-y divide-gray-50">
                    <!-- Settings - Edit Profile -->
                    <a href="{{ route('profile.update') }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
                      <div class="w-10 h-10 bg-[#f0fff0] rounded-xl flex items-center justify-center text-lg group-hover:bg-[#00aa13]/20">👤</div>
                      <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">Edit Profil</p>
                        <p class="text-xs text-gray-400">Ubah nama, email, dan foto profil</p>
                      </div>
                      <span class="text-gray-300 group-hover:text-[#00aa13]">→</span>
                    </a>

                    <!-- Settings - Keamanan -->
                    <a href="{{ route('profile.keamanan') }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
                      <div class="w-10 h-10 bg-[#f0fff0] rounded-xl flex items-center justify-center text-lg group-hover:bg-[#00aa13]/20">🔒</div>
                      <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">Keamanan & Password</p>
                        <p class="text-xs text-gray-400">Ubah password akun kamu</p>
                      </div>
                      <span class="text-gray-300 group-hover:text-[#00aa13]">→</span>
                    </a>

                    <!-- Settings - Riwayat Transaksi -->
                    <a href="{{ url('riwayat') }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
                      <div class="w-10 h-10 bg-[#f0fff0] rounded-xl flex items-center justify-center text-lg group-hover:bg-[#00aa13]/20">📜</div>
                      <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">Riwayat Transaksi</p>
                        <p class="text-xs text-gray-400">Lihat semua riwayat donasi dan zakat</p>
                      </div>
                      <span class="text-gray-300 group-hover:text-[#00aa13]">→</span>
                    </a>
                  </div>
                </div>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit"
                    class="w-full py-4 bg-red-50 text-red-500 font-bold rounded-2xl hover:bg-red-100 transition-colors flex items-center justify-center gap-2 cursor-pointer">
                    🚪 Keluar dari Akun
                  </button>
                </form>
            </div>
          </main>
      </div>
    </div>
  </body>
</html>