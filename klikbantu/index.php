<?php
session_start();
include './includes/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil 5 campaign aktif terbaru untuk bagian "Mendesak"
$qCampaigns = $conn->query("SELECT * FROM campaigns WHERE status = 'active' ORDER BY created_at DESC LIMIT 5");
$campaigns  = $qCampaigns->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berbagi Kebaikan - Beranda</title>
    <link href="./src/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f8f9fa] text-[#333] leading-relaxed pb-20">

    <header class="bg-gradient-to-br from-[#00aa13] to-[#006600] text-white pt-[60px] pb-[50px] px-5 text-center rounded-b-[32px] shadow-[0_4px_15px_rgba(0,102,0,0.2)]">
        <div class="max-w-[500px] mx-auto">
            <h1 class="text-[1.8rem] font-bold mb-3 leading-[1.2]">Donasi<br>Bikin Urusan Lancar</h1>
            <p class="text-[0.95rem] opacity-90 mb-6">Lakukan kebaikan setiap harinya.</p>
            <a href="donasi.php" class="inline-block bg-white text-[#006600] font-bold py-3 px-7 rounded-full shadow-lg transition-transform hover:scale-105">
                Donasi Sekarang
            </a>
        </div>
    </header>

    <main class="max-w-[500px] mx-auto px-5">
        <h2 class="text-[1.2rem] font-bold text-[#333] mt-[30px] mb-[15px]">Mau berbuat kebaikan apa hari ini?</h2>
        <div class="grid grid-cols-2 gap-2">
            <a href="donasi.php" class="bg-white rounded-[16px] py-4 px-1 text-center shadow-sm transition-all hover:bg-[#f0fff0] hover:-translate-y-1">
                <span class="text-[1.4rem] block mb-1">💰</span>
                <span class="text-[0.75rem] font-semibold block">Donasi</span>
            </a>
            <a href="zakat.php" class="bg-white rounded-[16px] py-4 px-1 text-center shadow-sm transition-all hover:bg-[#f0fff0] hover:-translate-y-1">
                <span class="text-[1.4rem] block mb-1">🕌</span>
                <span class="text-[0.75rem] font-semibold block">Zakat</span>
            </a>
            <a href="https://forms.gle/pwYoMXbzDrAjW71N6" target="_blank" rel="noopener noreferrer" class="bg-white rounded-[16px] py-4 px-1 text-center shadow-sm transition-all hover:bg-[#f0fff0] hover:-translate-y-1 cursor-pointer">
                <span class="text-[1.4rem] block mb-1">📝</span>
                <span class="text-[0.75rem] font-semibold block">Ajukan Campaign</span>
            </a>
        </div>

        <!-- Campaign Mendesak dari DB -->
        <h2 class="text-[1.2rem] font-bold text-[#333] mt-[30px] mb-[15px]">Mendesak</h2>

        <?php if (empty($campaigns)): ?>
        <div class="text-center py-8 text-gray-400">
            <p>Belum ada campaign aktif saat ini.</p>
        </div>
        <?php else: ?>
        <div class="flex gap-4 overflow-x-auto pb-5 no-scrollbar snap-x snap-mandatory">
            <?php foreach ($campaigns as $c):
                $percent = ($c['target_dana'] > 0) ? min(100, ($c['dana_terkumpul'] / $c['target_dana']) * 100) : 0;
                $gambarSrc = !empty($c['gambar']) && file_exists('assets/img/campaigns/' . $c['gambar'])
                    ? 'assets/img/campaigns/' . $c['gambar']
                    : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=300&q=80';
            ?>
            <div class="flex-[0_0_85%] snap-start bg-white rounded-[20px] overflow-hidden shadow-md">
                <img src="<?= htmlspecialchars($gambarSrc) ?>" class="w-full h-[150px] object-cover" alt="<?= htmlspecialchars($c['nama']) ?>">
                <div class="p-4">
                    <span class="text-[0.65rem] font-bold text-[#00aa13] uppercase tracking-wider"><?= htmlspecialchars($c['kategori']) ?></span>
                    <h3 class="text-[1rem] font-bold mt-1 mb-1 h-12 overflow-hidden leading-tight"><?= htmlspecialchars($c['nama']) ?></h3>
                    <p class="text-[0.8rem] text-gray-400">Terkumpul: <b class="text-[#333]">Rp <?= number_format($c['dana_terkumpul'], 0, ',', '.') ?></b></p>
                    <div class="bg-gray-200 h-2 rounded-full my-[8px]">
                        <div class="bg-[#00aa13] h-full rounded-full" style="width: <?= $percent ?>%"></div>
                    </div>
                    <a href="form-donasi.php?id=<?= $c['id'] ?>" class="text-[#00aa13] font-bold text-[0.9rem]">Donasi Sekarang →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <a href="#" class="flex-1 text-center text-[0.7rem] text-[#00aa13] font-bold"><span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda</a>
        <a href="donasi.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13]"><span class="text-[1.4rem] block mb-0.5">💰</span>Donasi</a>
        <a href="riwayat.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13]"><span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat</a>
        <a href="akun.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13]"><span class="text-[1.4rem] block mb-0.5">👤</span>Akun</a>
    </nav>
</body>
</html>
