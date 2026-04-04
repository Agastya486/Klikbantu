<?php
session_start();
include './includes/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil semua campaign aktif, bisa difilter per kategori
$kategoriFilter = $_GET['kategori'] ?? 'semua';
$keyword        = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM campaigns WHERE status = 'active'";
$params = [];
$types  = '';

if ($kategoriFilter !== 'semua') {
    $sql    .= " AND kategori = ?";
    $types  .= 's';
    $params[] = $kategoriFilter;
}
if (!empty($keyword)) {
    $sql    .= " AND (nama LIKE ? OR deskripsi LIKE ?)";
    $types  .= 'ss';
    $kw = '%' . $keyword . '%';
    $params[] = $kw;
    $params[] = $kw;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$campaigns = $stmt->get_result();

$kategoriList = ['semua', 'pendidikan', 'kesehatan', 'umkm', 'kemanusiaan', 'bencana alam', 'anak yatim', 'medis'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Pilih Donasi</title>
    <link href="./src/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f8f9fa] text-[#333] pb-24">

    <!-- Search Header -->
    <header class="sticky top-0 z-50 bg-white px-5 py-4 shadow-sm">
        <div class="max-w-[500px] mx-auto">
            <form method="GET" action="donasi.php">
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategoriFilter) ?>">
                <div class="relative">
                    <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari bantuan..."
                        class="w-full bg-gray-100 border-none rounded-full py-3 px-11 text-sm focus:ring-2 focus:ring-[#00aa13] outline-none">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-40">🔍</span>
                </div>
            </form>
        </div>
    </header>

    <main class="max-w-[500px] mx-auto px-5">

        <!-- Filter Kategori -->
        <div class="flex gap-2 overflow-x-auto py-4 no-scrollbar">
            <?php foreach ($kategoriList as $kat): ?>
            <a href="donasi.php?kategori=<?= urlencode($kat) ?><?= $keyword ? '&q='.urlencode($keyword) : '' ?>"
                class="<?= $kategoriFilter === $kat ? 'bg-[#00aa13] text-white' : 'bg-white border border-gray-100 text-gray-600 hover:bg-[#f0fff0]' ?> px-5 py-2 rounded-full text-xs font-semibold whitespace-nowrap shadow-sm transition-all cursor-pointer capitalize">
                <?= ucfirst($kat) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <h2 class="text-[1.1rem] font-bold mb-4">
            <?= $campaigns->num_rows ?> Campaign Ditemukan
        </h2>

        <div class="grid grid-cols-1 gap-4">
        <?php if ($campaigns->num_rows === 0): ?>
            <div class="text-center py-16">
                <div class="text-5xl mb-4">🔍</div>
                <p class="text-gray-400 font-semibold">Belum ada campaign untuk kategori ini.</p>
            </div>
        <?php else: ?>
            <?php while ($c = $campaigns->fetch_assoc()):
                $percent = ($c['target_dana'] > 0) ? min(100, ($c['dana_terkumpul'] / $c['target_dana']) * 100) : 0;
                $gambarSrc = !empty($c['gambar']) && file_exists('assets/img/campaigns/' . $c['gambar'])
                    ? 'assets/img/campaigns/' . $c['gambar']
                    : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=400&q=80';
            ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 flex flex-col">
                <img src="<?= htmlspecialchars($gambarSrc) ?>" class="w-full h-40 object-cover" alt="<?= htmlspecialchars($c['nama']) ?>">
                <div class="p-4">
                    <span class="text-[0.65rem] font-bold text-[#00aa13] uppercase tracking-wider"><?= htmlspecialchars($c['kategori']) ?></span>
                    <h3 class="text-[0.95rem] font-bold mt-1 mb-3 leading-tight"><?= htmlspecialchars($c['nama']) ?></h3>
                    <div class="bg-gray-100 h-1.5 rounded-full mb-2">
                        <div class="bg-[#00aa13] h-full rounded-full" style="width: <?= $percent ?>%"></div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-[0.7rem] text-gray-400">Terkumpul<br>
                            <span class="text-[#333] font-bold text-[0.85rem]">Rp <?= number_format($c['dana_terkumpul'], 0, ',', '.') ?></span>
                        </p>
                        <p class="text-[0.7rem] text-gray-400 text-right">Target<br>
                            <span class="text-[#333] font-bold text-[0.85rem]">Rp <?= number_format($c['target_dana'], 0, ',', '.') ?></span>
                        </p>
                    </div>
                    <a href="form-donasi.php?id=<?= $c['id'] ?>" class="block text-center bg-[#00aa13] text-white font-bold py-2.5 rounded-xl text-sm active:scale-95 transition-all">
                        Donasi Sekarang
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
        </div>
    </main>

    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <a href="index.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda</a>
        <a href="#" class="flex-1 text-center text-[0.7rem] text-[#00aa13] font-bold"><span class="text-[1.4rem] block mb-0.5">💰</span>Donasi</a>
        <a href="riwayat.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat</a>
        <a href="akun.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">👤</span>Akun</a>
    </nav>
</body>
</html>
