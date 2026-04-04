<?php
    session_start();
    include './includes/koneksi.php';

    if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); } // Pastikan hanya admin yang bisa akses halaman ini

    // Statistik ringkas
    $qTotalDonasi = $conn->query("SELECT COALESCE(SUM(nominal),0) as t FROM donations WHERE status_pembayaran='success'");
    $totalDonasi  = $qTotalDonasi->fetch_assoc()['t'];

    $qTotalZakat = $conn->query("SELECT COALESCE(SUM(nominal),0) as t FROM zakats WHERE status_pembayaran='success'");
    $totalZakat  = $qTotalZakat->fetch_assoc()['t'];

    $qCountDon = $conn->query("SELECT COUNT(*) as t FROM donations WHERE status_pembayaran='success'");
    $countDon  = $qCountDon->fetch_assoc()['t'];

    $qCountZak = $conn->query("SELECT COUNT(*) as t FROM zakats WHERE status_pembayaran='success'");
    $countZak  = $qCountZak->fetch_assoc()['t'];

    // Ambil semua donasi dengan join user & campaign
    $donasi = $conn->query("
        SELECT d.id, d.nominal, d.metode, d.is_anonim, d.status_pembayaran, d.created_at,
            u.nama as nama_user, u.email,
            c.nama as nama_campaign, c.kategori
        FROM donations d
        JOIN users u ON d.id_user = u.id
        JOIN campaigns c ON d.id_campaign = c.id
        ORDER BY d.created_at DESC
    ")->fetch_all(MYSQLI_ASSOC);

    // Ambil semua zakat
    $zakats = $conn->query("
        SELECT z.id, z.nominal, z.jenis_zakat, z.nama_pembayar, z.no_hp, z.status_pembayaran, z.created_at,
            u.email
        FROM zakats z
        LEFT JOIN users u ON z.id_user = u.id
        ORDER BY z.created_at DESC
    ")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./src/output.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
        <title>Riwayat Transaksi - KlikBantu</title>
        <style> body { font-family: 'Poppins', sans-serif; } </style>
    </head>
    <body class="bg-gray-50 text-gray-800">
        <div class="flex min-h-screen">
            <aside class="w-64 bg-[#006600] text-white flex flex-col p-6 fixed h-screen z-20 shadow-xl">
                <div class="mb-10">
                    <h1 class="text-2xl font-bold italic tracking-tight">KlikBantu</h1>
                    <p class="text-xs text-green-200">Admin Panel v1.0</p>
                </div>
                <nav class="flex-1 flex flex-col gap-2 mt-10">
                    <a href="admin.php" class="flex items-center gap-3 p-3 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        <span>📂</span> Dashboard
                    </a>
                    <a href="admin-riwayat.php" class="flex items-center gap-3 p-3 rounded-xl font-semibold bg-white/20 transition-all">
                        <span>💸</span> Riwayat Transaksi
                    </a>
                </nav>
                <div class="mt-auto pt-6 border-t border-green-700">
                    <a href="auth/logout.php" class="text-sm text-green-200 hover:text-white transition-colors">🚪 Logout</a>
                </div>
            </aside>

            <main class="flex-1 ml-64 p-10">
                <header class="flex justify-between items-end mb-8">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Financial Records</p>
                        <h2 class="text-3xl font-black tracking-tight text-gray-900">Arus Kas Masuk 💸</h2>
                    </div>
                </header>

                <!-- Statistik -->
                <div class="grid grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1">Total Donasi</p>
                        <p class="text-xl font-black text-[#006600]">Rp <?= number_format($totalDonasi, 0, ',', '.') ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?= $countDon ?> transaksi</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1">Total Zakat</p>
                        <p class="text-xl font-black text-[#006600]">Rp <?= number_format($totalZakat, 0, ',', '.') ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?= $countZak ?> transaksi</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1">Total Keseluruhan</p>
                        <p class="text-xl font-black text-[#006600]">Rp <?= number_format($totalDonasi + $totalZakat, 0, ',', '.') ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?= $countDon + $countZak ?> transaksi</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1">Rata-rata Donasi</p>
                        <p class="text-xl font-black text-[#006600]">Rp <?= $countDon > 0 ? number_format($totalDonasi / $countDon, 0, ',', '.') : '0' ?></p>
                        <p class="text-xs text-gray-400 mt-1">per transaksi</p>
                    </div>
                </div>

                <!-- Filter -->
                <div class="flex gap-3 mb-6">
                    <button onclick="filterData('all', this)" class="filter-btn active px-5 py-2.5 rounded-xl border border-gray-200 bg-white font-semibold text-sm transition-all hover:bg-gray-50 [&.active]:bg-[#00aa13] [&.active]:text-white [&.active]:border-[#00aa13] cursor-pointer">Semua</button>
                    <button onclick="filterData('donasi', this)" class="filter-btn px-5 py-2.5 rounded-xl border border-gray-200 bg-white font-semibold text-sm transition-all hover:bg-gray-50 [&.active]:bg-[#00aa13] [&.active]:text-white [&.active]:border-[#00aa13] cursor-pointer">🎁 Donasi</button>
                    <button onclick="filterData('zakat', this)" class="filter-btn px-5 py-2.5 rounded-xl border border-gray-200 bg-white font-semibold text-sm transition-all hover:bg-gray-50 [&.active]:bg-[#00aa13] [&.active]:text-white [&.active]:border-[#00aa13] cursor-pointer">🌙 Zakat</button>
                </div>

                <!-- Tabel Histori Transaksi -->
                <div class="bg-white rounded-[24px] border border-gray-100 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-[10px] uppercase tracking-widest text-gray-400 font-black">Donatur</th>
                                <th class="px-6 py-4 text-[10px] uppercase tracking-widest text-gray-400 font-black">Tipe</th>
                                <th class="px-6 py-4 text-[10px] uppercase tracking-widest text-gray-400 font-black">Program / Kategori</th>
                                <th class="px-6 py-4 text-[10px] uppercase tracking-widest text-gray-400 font-black">Nominal</th>
                                <th class="px-6 py-4 text-[10px] uppercase tracking-widest text-gray-400 font-black">Tanggal</th>
                                <th class="px-6 py-4 text-[10px] uppercase tracking-widest text-gray-400 font-black">Status</th>
                            </tr>
                        </thead>
                        <!-- Data transaksi akan diisi di sini menggunakan PHP -->
                        <tbody id="transactionTable">
                            <!-- Data Donasi -->
                            <?php foreach ($donasi as $d):
                                $namaDisplay = $d['is_anonim'] ? 'Hamba Allah' : htmlspecialchars($d['nama_user']); //htmlspecialchars untuk mencegah XSS jika nama mengandung karakter khusus
                                $emailDisplay = $d['is_anonim'] ? 'anonymous' : htmlspecialchars($d['email']);
                                $statusColor = $d['status_pembayaran'] === 'success' ? 'bg-green-500' : ($d['status_pembayaran'] === 'pending' ? 'bg-yellow-400' : 'bg-red-400');
                                $statusLabel = ucfirst($d['status_pembayaran']); // ucfirst untuk membuat huruf pertama kapital, misal "Success", "Pending", "Failed"
                            ?>
                            <tr class="transaction-row border-t border-gray-50 hover:bg-gray-50/50 transition-colors" data-type="donasi">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600"><?= mb_substr($namaDisplay, 0, 1) ?></div>
                                        <div>
                                            <p class="font-bold text-sm"><?= $namaDisplay ?></p>
                                            <p class="text-[11px] text-gray-400"><?= $emailDisplay ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5"><span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600">Donasi</span></td>
                                <td class="px-6 py-5 text-sm text-gray-600"><?= htmlspecialchars($d['nama_campaign']) ?></td>
                                <td class="px-6 py-5 font-black text-[#006600] text-sm">Rp <?= number_format($d['nominal'], 0, ',', '.') ?></td>
                                <td class="px-6 py-5 text-sm text-gray-500"><?= date('d M Y', strtotime($d['created_at'])) ?></td>
                                <td class="px-6 py-5 text-sm">
                                    <span class="inline-block w-2 h-2 rounded-full <?= $statusColor ?> mr-1.5"></span>
                                    <span class="font-medium text-gray-700"><?= $statusLabel ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <!-- Data Zakat -->
                            <?php foreach ($zakats as $z):
                                $namaZ = htmlspecialchars($z['nama_pembayar']);
                                $emailZ = $z['email'] ? htmlspecialchars($z['email']) : $z['no_hp'];
                                $statusColorZ = $z['status_pembayaran'] === 'success' ? 'bg-green-500' : ($z['status_pembayaran'] === 'pending' ? 'bg-yellow-400' : 'bg-red-400');
                            ?>
                            <tr class="transaction-row border-t border-gray-50 hover:bg-gray-50/50 transition-colors" data-type="zakat">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-xs font-bold text-amber-600"><?= mb_substr($namaZ, 0, 1) ?></div>
                                        <div>
                                            <p class="font-bold text-sm"><?= $namaZ ?></p>
                                            <p class="text-[11px] text-gray-400"><?= $emailZ ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5"><span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-600">Zakat</span></td>
                                <td class="px-6 py-5 text-sm text-gray-600">Zakat <?= ucfirst(htmlspecialchars($z['jenis_zakat'])) ?></td>
                                <td class="px-6 py-5 font-black text-[#006600] text-sm">Rp <?= number_format($z['nominal'], 0, ',', '.') ?></td>
                                <td class="px-6 py-5 text-sm text-gray-500"><?= date('d M Y', strtotime($z['created_at'])) ?></td>
                                <td class="px-6 py-5 text-sm">
                                    <span class="inline-block w-2 h-2 rounded-full <?= $statusColorZ ?> mr-1.5"></span>
                                    <span class="font-medium text-gray-700"><?= ucfirst($z['status_pembayaran']) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <!-- Pesan jika tidak ada transaksi -->
                            <?php if (empty($donasi) && empty($zakats)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                    Belum ada transaksi masuk.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
        <script src="admin-riwayat.js"></script>
    </body>
</html>
