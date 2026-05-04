<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Keamanan</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-[#f8f9fa] text-[#333]">
<div class="flex min-h-screen">

    @include('components.sidebar')

    <div class="main-content">
        <!-- Header -->
        <header class="bg-white border-b px-6 py-4 flex items-center gap-4 sticky top-0 z-30 shadow-sm">
            <a href="{{ route('profile') }}" class="text-gray-400 hover:text-[#00aa13] text-sm font-semibold">
                ← Kembali ke Akun
            </a>

            <div class="flex-1">
                <h1 class="text-lg font-bold">Keamanan & Password 🔒</h1>
                <p class="text-xs text-gray-400">Kelola keamanan akun</p>
            </div>
        </header>

        <main class="p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">

                {{-- ALERT --}}
                @if(session('error'))
                    <div class="bg-red-50 border text-red-700 p-4 rounded-xl text-sm mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border text-green-700 p-4 rounded-xl text-sm mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- STATUS -->
                    <div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                            <p class="text-xs font-bold text-gray-400 mb-5">Status Keamanan</p>

                            <p class="text-sm text-gray-500">Terakhir diubah:</p>
                            <p class="text-sm font-semibold text-[#00aa13]">
                                {{ \Carbon\Carbon::parse(Auth::user()->updated_at)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- FORM -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl p-6 shadow-sm">

                            <form action="{{ route('profile.update.password') }}" method="POST">
                                @csrf

                                <!-- CURRENT -->
                                <input type="password" name="current_password" placeholder="Password lama" class="w-full mb-4 p-3 border rounded-xl">

                                <!-- NEW -->
                                <input type="password" name="new_password" placeholder="Password baru" class="w-full mb-4 p-3 border rounded-xl">

                                <!-- CONFIRM -->
                                <input type="password" name="confirm_password" placeholder="Konfirmasi password" class="w-full mb-4 p-3 border rounded-xl">

                                <button class="w-full bg-[#00aa13] text-white py-3 rounded-xl cursor-pointer hover:bg-[#008a0f] transition-colors font-semibold">
                                    Update Password
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>