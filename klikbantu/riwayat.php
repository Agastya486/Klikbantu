<?php
session_start();
include './includes/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Ambil semua donasi user (success)
$qDonasi = $conn->prepare("
    SELECT d.id, d.nominal, d.pesan, d.is_anonim, d.status_pembayaran, d.created_at,
           c.nama as nama_campaign, c.kategori
    FROM donations d
    JOIN campaigns c ON d.id_campaign = c.id
    WHERE d.id_user = ?
    ORDER BY d.created_at DESC
");
$qDonasi->bind_param("i", $user_id);
$qDonasi->execute();
$dataDonasi = $qDonasi->get_result()->fetch_all(MYSQLI_ASSOC);

// Ambil semua zakat user
$qZakat = $conn->prepare("
    SELECT id, nominal, jenis_zakat, nama_pembayar, status_pembayaran, created_at
    FROM zakats
    WHERE id_user = ?
    ORDER BY created_at DESC
");
$qZakat->bind_param("i", $user_id);
$qZakat->execute();
$dataZakat = $qZakat->get_result()->fetch_all(MYSQLI_ASSOC);

// Hitung total (hanya yang success)
$totalDonasi = array_sum(array_column(array_filter($dataDonasi, fn($d) => $d['status_pembayaran'] === 'success'), 'nominal'));
$totalZakat  = array_sum(array_column(array_filter($dataZakat,  fn($z) => $z['status_pembayaran'] === 'success'), 'nominal'));

// Gabungkan & sort by created_at
$allData = [];
foreach ($dataDonasi as $d) {
    $allData[] = [
        'tipe'    => 'donasi',
        'nama'    => $d['is_anonim'] ? 'Hamba Allah' : '',
        'program' => $d['nama_campaign'],
        'jumlah'  => $d['nominal'],
        'tanggal' => $d['created_at'],
        'status'  => $d['status_pembayaran'],
        'kategori'=> $d['kategori'],
    ];
}
foreach ($dataZakat as $z) {
    $allData[] = [
        'tipe'    => 'zakat',
        'nama'    => $z['nama_pembayar'],
        'program' => 'Zakat ' . ucfirst($z['jenis_zakat']),
        'jumlah'  => $z['nominal'],
        'tanggal' => $z['created_at'],
        'status'  => $z['status_pembayaran'],
        'kategori'=> $z['jenis_zakat'],
    ];
}
usort($allData, fn($a, $b) => strtotime($b['tanggal']) - strtotime($a['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Riwayat</title>
    <link href="./src/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#f8f9fa] text-[#333] leading-relaxed pb-24">

    <header class="bg-gradient-to-br from-[#00aa13] to-[#006600] text-white pt-[50px] pb-[30px] px-5 rounded-b-[32px] shadow-[0_4px_15px_rgba(0,102,0,0.2)]">
        <div class="max-w-[500px] mx-auto">
            <h1 class="text-[1.4rem] font-bold mb-1">Riwayat Kebaikan 📜</h1>
            <p class="text-white/80 text-[0.82rem]">Semua transaksi donasi dan zakat kamu tercatat di sini.</p>
        </div>
    </header>

    <main class="max-w-[500px] mx-auto px-5 mt-6">

        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-4 text-sm font-semibold">
            ✅ <?= $_GET['success'] === 'donasi_sukses' ? 'Donasi berhasil! Terima kasih atas kebaikanmu.' : 'Transaksi berhasil.' ?>
        </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="flex gap-2 mb-5">
            <?php foreach (['semua' => 'Semua', 'donasi' => '💰 Donasi', 'zakat' => '🌙 Zakat'] as $key => $label): ?>
            <button onclick="filterRiwayat('<?= $key ?>', this)"
                class="filter-btn <?= $key === 'semua' ? 'active' : '' ?> px-5 py-2 rounded-xl border border-gray-200 bg-white font-semibold text-sm transition-all hover:bg-gray-50 [&.active]:bg-[#00aa13] [&.active]:text-white [&.active]:border-[#00aa13] cursor-pointer">
                <?= $label ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50">
                <p class="text-[0.7rem] text-gray-400 mb-1">Total Donasi</p>
                <p class="text-[1.1rem] font-bold text-[#006600]">Rp <?= number_format($totalDonasi, 0, ',', '.') ?></p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50">
                <p class="text-[0.7rem] text-gray-400 mb-1">Total Zakat</p>
                <p class="text-[1.1rem] font-bold text-[#006600]">Rp <?= number_format($totalZakat, 0, ',', '.') ?></p>
            </div>
        </div>

        <!-- List Transaksi -->
        <div id="riwayat-list" class="space-y-3">
        <?php if (empty($allData)): ?>
            <div id="empty-msg" class="text-center py-16">
                <div class="text-5xl mb-4">🤲</div>
                <p class="text-gray-400 font-semibold">Belum ada transaksi.</p>
                <div class="flex gap-3 justify-center mt-4">
                    <a href="donasi.php" class="text-sm bg-[#00aa13] text-white font-bold px-5 py-2.5 rounded-xl">Donasi →</a>
                    <a href="zakat.php" class="text-sm bg-white border border-[#00aa13] text-[#00aa13] font-bold px-5 py-2.5 rounded-xl">Zakat →</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($allData as $item):
                $isZakat     = $item['tipe'] === 'zakat';
                $icon        = $isZakat ? '🌙' : '💰';
                $badgeClass  = $isZakat ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600';
                $badgeLabel  = $isZakat ? '🌙 Zakat' : '💰 Donasi';
                $statusColor = $item['status'] === 'success' ? 'text-green-500' : ($item['status'] === 'pending' ? 'text-yellow-500' : 'text-red-400');
                $statusLabel = $item['status'] === 'success' ? '✓ Berhasil' : ($item['status'] === 'pending' ? '⏳ Pending' : '✗ Gagal');
                $tanggal     = date('d M Y', strtotime($item['tanggal']));
            ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50 flex items-center gap-3 riwayat-item" data-tipe="<?= $item['tipe'] ?>">
                <div class="w-11 h-11 rounded-full bg-[#f0fff0] flex items-center justify-center text-xl flex-shrink-0"><?= $icon ?></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <p class="font-bold text-[0.9rem] truncate"><?= htmlspecialchars($item['program']) ?></p>
                        <span class="text-[0.62rem] font-bold px-2 py-0.5 rounded-full flex-shrink-0 <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                    </div>
                    <p class="text-[0.7rem] text-gray-300 mt-0.5"><?= $tanggal ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-bold text-[#006600] text-[0.9rem]">Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></p>
                    <p class="text-[0.65rem] font-semibold mt-0.5 <?= $statusColor ?>"><?= $statusLabel ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </main>

    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <a href="index.php" class="flex-1 text-center text-gray-400 text-[0.7rem]"><span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda</a>
        <a href="donasi.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">💰</span>Donasi</a>
        <a href="#" class="flex-1 text-center text-[0.7rem] text-[#00aa13] font-bold"><span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat</a>
        <a href="akun.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">👤</span>Akun</a>
    </nav>

    <script>
    let filterAktif = 'semua';
    function filterRiwayat(tipe, btn) {
        filterAktif = tipe;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.riwayat-item').forEach(el => {
            el.style.display = (tipe === 'semua' || el.dataset.tipe === tipe) ? '' : 'none';
        });
    }
    </script>
</body>
</html>
