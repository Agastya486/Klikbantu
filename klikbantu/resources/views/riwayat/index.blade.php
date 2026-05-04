@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>KlikBantu - Riwayat Kebaikan</title>

        <link href="{{ asset('src/output.css') }}" rel="stylesheet">
        <link href="{{ asset('src/style.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Poppins', sans-serif; }
            .filter-btn.active {
                background-color: #00aa13;
                color: white;
            }
            .status-success { background-color: #dcfce7; color: #166534; }
            .status-pending { background-color: #fef9c3; color: #854d0e; }
            .status-failed { background-color: #fee2e2; color: #991b1b; }
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
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex-1">
                        <h1 class="text-lg font-bold text-gray-800">Riwayat Kebaikan 📜</h1>
                        <p class="text-xs text-gray-400">Semua transaksi donasi dan zakat kamu</p>
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
                    {{-- SUMMARY CARDS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                        <div class="bg-gradient-to-br from-[#00aa13] to-[#006600] text-white rounded-2xl p-6 shadow-md">
                            <div class="text-2xl mb-2">💰</div>
                            <p class="text-2xl font-bold mb-1">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</p>
                            <p class="text-white/80 text-sm">Total Donasi Berhasil</p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                            <div class="text-2xl mb-2">🕌</div>
                            <p class="text-2xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalZakat, 0, ',', '.') }}</p>
                            <p class="text-gray-400 text-sm">Total Zakat Berhasil</p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-50">
                            <div class="text-2xl mb-2">📊</div>
                            <p class="text-2xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalDonasi + $totalZakat, 0, ',', '.') }}</p>
                            <p class="text-gray-400 text-sm">Total Kebaikan</p>
                        </div>
                    </div>

                    {{-- FILTER BUTTONS --}}
                    <div class="flex gap-2 mb-6">
                        <button onclick="filterRiwayat('semua', this)" 
                            class="filter-btn active px-5 py-2 rounded-xl text-sm font-semibold bg-[#00aa13] text-white transition-all">
                            Semua
                        </button>
                        <button onclick="filterRiwayat('donasi', this)" 
                            class="filter-btn px-5 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                            💰 Donasi
                        </button>
                        <button onclick="filterRiwayat('zakat', this)" 
                            class="filter-btn px-5 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                            🕌 Zakat
                        </button>
                    </div>

                    {{-- TRANSACTION LIST --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-50 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800">Semua Transaksi</h2>
                            <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                                {{ count($allData) }} transaksi
                            </span>
                        </div>

                        <div id="riwayat-container">
                            @if(empty($allData))
                                <div class="text-center py-20">
                                    <div class="text-5xl mb-4">💫</div>
                                    <p class="text-gray-500 font-bold mb-1">Belum ada riwayat transaksi</p>
                                    <p class="text-gray-400 text-sm mb-6">Mulai berdonasi untuk melihat riwayat kamu</p>
                                    <a href="{{ url('donasi') }}" class="inline-block bg-[#00aa13] text-white font-bold px-6 py-2.5 rounded-xl text-sm hover:bg-[#009911] transition-colors">
                                        Mulai Donasi
                                    </a>
                                </div>
                            @else
                                {{-- Desktop Table --}}
                                <div class="hidden md:block overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-widest">
                                            <tr>
                                                <th class="text-left px-6 py-3 font-semibold">Program</th>
                                                <th class="text-left px-6 py-3 font-semibold">Jenis</th>
                                                <th class="text-left px-6 py-3 font-semibold">Jumlah</th>
                                                <th class="text-left px-6 py-3 font-semibold">Tanggal</th>
                                                <th class="text-left px-6 py-3 font-semibold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50" id="riwayat-table-body">
                                            @foreach($allData as $item)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg {{ $item->tipe === 'donasi' ? 'bg-green-50' : 'bg-blue-50' }}">
                                                            {{ $item->tipe === 'donasi' ? '💰' : '🕌' }}
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ htmlspecialchars($item->program) }}</p>
                                                            <p class="text-xs text-gray-400">{{ htmlspecialchars($item->nama) }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-xs font-semibold capitalize px-3 py-1 rounded-full {{ $item->tipe === 'donasi' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}">
                                                        {{ ucfirst($item->tipe) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-bold text-gray-800">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-xs text-gray-500">{{ date('d M Y, H:i', strtotime($item->tanggal)) }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $statusClass = match($item->status) {
                                                            'success' => 'bg-green-50 text-green-700',
                                                            'pending' => 'bg-yellow-50 text-yellow-700',
                                                            'failed' => 'bg-red-50 text-red-700',
                                                            default => 'bg-gray-100 text-gray-600'
                                                        };
                                                        $statusText = match($item->status) {
                                                            'success' => '✅ Berhasil',
                                                            'pending' => '⏳ Menunggu',
                                                            'failed' => '❌ Gagal',
                                                            default => '— ' . $item->status
                                                        };
                                                    @endphp
                                                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Mobile Cards --}}
                                <div class="md:hidden divide-y divide-gray-50" id="riwayat-mobile-list">
                                    @foreach($allData as $item)
                                        @php
                                            $statusClass = match($item->status) {
                                                'success' => 'bg-green-50 text-green-700',
                                                'pending' => 'bg-yellow-50 text-yellow-700',
                                                'failed' => 'bg-red-50 text-red-700',
                                                default => 'bg-gray-100 text-gray-600'
                                            };
                                            $statusText = match($item->status) {
                                                'success' => '✅ Berhasil',
                                                'pending' => '⏳ Menunggu',
                                                'failed' => '❌ Gagal',
                                                default => '—'
                                            };
                                        @endphp
                                        <div class="px-5 py-4 flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 {{ $item->tipe === 'donasi' ? 'bg-green-50' : 'bg-blue-50' }}">
                                                {{ $item->tipe === 'donasi' ? '💰' : '🕌' }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{ htmlspecialchars($item->program) }}</p>
                                                <p class="text-xs text-gray-400">{{ date('d M Y', strtotime($item->tanggal)) }}</p>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                                                <span class="text-[0.65rem] font-semibold px-2 py-0.5 rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script>
        // Filter function untuk mobile & desktop
        let currentFilter = 'semua';

        function filterRiwayat(tipe, btn) {
            currentFilter = tipe;
            
            // Update active state buttons
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active', 'bg-[#00aa13]', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-600');
            });
            btn.classList.add('active', 'bg-[#00aa13]', 'text-white');
            btn.classList.remove('bg-gray-100', 'text-gray-600');
            
            // Filter data dari server-side yang sudah ada di HTML
            const allRows = document.querySelectorAll('#riwayat-table-body tr, #riwayat-mobile-list > div');
            
            allRows.forEach(row => {
                if (tipe === 'semua') {
                    row.style.display = '';
                } else {
                    // Cek apakah row memiliki class atau data yang sesuai
                    const isDonasi = row.innerHTML.includes('bg-green-50') || row.querySelector('.bg-green-50');
                    const isZakat = row.innerHTML.includes('bg-blue-50') || row.querySelector('.bg-blue-50');
                    
                    if (tipe === 'donasi' && isDonasi) {
                        row.style.display = '';
                    } else if (tipe === 'zakat' && isZakat) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
            
            // Update counter
            let visibleCount = 0;
            if (tipe === 'semua') {
                visibleCount = allRows.length;
            } else {
                allRows.forEach(row => {
                    if (row.style.display !== 'none') visibleCount++;
                });
            }
            
            const counterSpan = document.querySelector('.bg-gray-100.px-3.py-1.rounded-full');
            if (counterSpan) {
                counterSpan.textContent = visibleCount + ' transaksi';
            }
        }

        // escapeHTML adalah fungsi untuk mencegah XSS dengan mengubah karakter khusus menjadi entitas HTML
        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        </script>

    </body>
</html>