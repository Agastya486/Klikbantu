@php
    $currentRoute = request()->route()->getName();
@endphp

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar bg-white border-r border-gray-100 shadow-sm flex flex-col" id="sidebar">

    <!-- Logo -->
    <div class="px-6 py-6 border-b border-gray-50">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-[#00aa13] to-[#006600] rounded-xl flex items-center justify-center shadow-md">
                <span class="text-white font-bold text-lg">K</span>
            </div>
            <div>
                <span class="text-[#006600] font-bold text-xl block">KlikBantu</span>
                <span class="text-gray-400 text-xs">Platform Donasi Online</span>
            </div>
        </a>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-4 py-6 space-y-1">
        <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-widest px-3 mb-3">Menu Utama</p>

        <a href="{{ route('home') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm transition-all
           {{ $currentRoute == 'home' ? 'bg-[#00aa13] text-white shadow-md' : 'text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]' }}">
            <span class="text-lg w-6 text-center">🏠</span>
            <span>Beranda</span>
        </a>

        <a href="{{ url('donasi') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]">
            <span class="text-lg w-6 text-center">💰</span>
            <span>Donasi</span>
        </a>

        <a href="{{ url('zakat') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]">
            <span class="text-lg w-6 text-center">🕌</span>
            <span>Zakat</span>
        </a>

        <a href="{{ url('riwayat') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]">
            <span class="text-lg w-6 text-center">📜</span>
            <span>Riwayat</span>
        </a>

        <a href="https://forms.gle/pwYoMXbzDrAjW71N6" target="_blank"
           class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]">
            <span class="text-lg w-6 text-center">📝</span>
            <span>Ajukan Campaign</span>
        </a>

        <div class="pt-4">
            <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-widest px-3 mb-3">Akun</p>

            <a href="{{ route('profile') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]">
                <span class="text-lg w-6 text-center">👤</span>
                <span>Profil Akun</span>
            </a>

            <a href="{{ route('profile.update') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl font-semibold text-sm text-gray-600 hover:bg-[#f0fff0] hover:text-[#00aa13]">
                <span class="text-lg w-6 text-center">✏️</span>
                <span>Edit Profil</span>
            </a>
        </div>
    </nav>

    <!-- User -->
    <div class="px-4 py-4 border-t border-gray-100">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 w-full text-left cursor-pointer">
                <span class="text-lg w-6 text-center">🚪</span>
                <span>Keluar</span>
            </button>
        </form>

    </div>
</aside>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
}
</script>