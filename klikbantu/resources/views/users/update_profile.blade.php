<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Edit Profil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
  </head>

  <body class="bg-[#f8f9fa] text-[#333]">
    <div class="flex min-h-screen">

      <!-- Sidebar -->
      @include('components.sidebar')

      <div class="flex-1">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-4 sticky top-0 z-30 shadow-sm">
          <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>

          <!-- Kembali ke Akun -->
          <a href="{{ route('profile') }}"
            class="text-gray-400 hover:text-[#00aa13] font-semibold text-sm flex items-center gap-1.5">
              ← Kembali ke Akun
          </a>

          <div class="flex-1">
            <h1 class="text-lg font-bold text-gray-800">Edit Profil ✏️</h1>
            <p class="text-xs text-gray-400">Perbarui informasi pribadi kamu</p>
          </div>
        </header>

        <main class="p-6 lg:p-8">
          <div class="max-w-3xl mx-auto">

            <!-- Messages -->
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-sm mb-6 flex gap-3">
              ⚠️ {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl text-sm mb-6 flex gap-3">
              ✅ {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Avatar -->
              <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-sm border flex flex-col items-center">

                    <p class="text-xs font-bold text-gray-400 mb-5 self-start">Foto Profil</p>

                    <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="relative mb-4">
                            <div class="w-32 h-32 rounded-2xl overflow-hidden border-4 border-[#00aa13]/20">
                                <img id="previewImg"
                                    src="{{ Auth::user()->avatar 
                                        ? asset('assets/img/avatars/'.Auth::user()->avatar) 
                                        : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=00aa13&color=fff&size=200' }}"
                                    class="w-full h-full object-cover">
                            </div>

                            <input type="file" name="avatar" id="avatarInput" class="hidden"
                                  onchange="previewAvatar(this)">

                            <label for="avatarInput"
                                class="absolute -bottom-2 -right-2 bg-[#00aa13] text-white p-2.5 rounded-xl cursor-pointer">
                                📷
                            </label>
                        </div>

                        <button type="submit" id="btnUploadAvatar"
                            class="hidden w-full bg-[#00aa13] text-white py-2.5 rounded-xl text-sm cursor-pointer">
                            Upload Foto
                        </button>
                    </form>

                    <!-- User Info -->
                    <div class="mt-6 w-full bg-gray-50 rounded-xl p-4 text-left">
                      <p class="text-sm font-semibold">{{ Auth::user()->nama }}</p>
                      <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                      @if(Auth::user()->no_telp)
                      <p class="text-xs text-gray-400 mt-1">+62 {{ Auth::user()->no_telp }}</p>
                      @endif
                    </div>
                  </div>
              </div>

              <!-- Form -->
              <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-6 shadow-sm border">
                  <p class="text-xs font-bold text-gray-400 mb-6">Informasi Pribadi</p>

                  <form action="{{ route('profile.update.info') }}" method="POST" class="space-y-5">
                    @csrf

                    <input type="text" name="name" value="{{ Auth::user()->nama }}" class="w-full border rounded-xl px-4 py-3">

                    <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full border rounded-xl px-4 py-3">

                    <input type="tel" name="phone_number" value="{{ Auth::user()->no_telp }}" class="w-full border rounded-xl px-4 py-3">

                    <textarea name="bio" class="w-full border rounded-xl px-4 py-3">{{ Auth::user()->bio }}</textarea>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#00aa13] to-[#006600] text-white py-3.5 rounded-xl cursor-pointer">
                        💾 Simpan Perubahan
                    </button>
                  </form>
                </div>
              </div>

            </div>
          </div>
        </main>
      </div>
    </div>

    <script>
      function previewAvatar(input) {
          if (input.files && input.files[0]) {
              const reader = new FileReader();
              reader.onload = e => {
                  document.getElementById('previewImg').src = e.target.result;
                  document.getElementById('btnUploadAvatar').classList.remove('hidden');
              };
              reader.readAsDataURL(input.files[0]);
          }
      }
    </script>

  </body>
</html>