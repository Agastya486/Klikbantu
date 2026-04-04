<?php
session_start();
include './includes/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$qUser   = $conn->prepare("SELECT nama FROM users WHERE id = ?");
$qUser->bind_param("i", $user_id);
$qUser->execute();
$user = $qUser->get_result()->fetch_assoc();

// Ambil campaign milik user ini (riwayat pengajuan)
$qMyCampaigns = $conn->prepare("
    SELECT id, nama, kategori, target_dana, dana_terkumpul, status, created_at
    FROM campaigns WHERE id_user = ?
    ORDER BY created_at DESC
");
$qMyCampaigns->bind_param("i", $user_id);
$qMyCampaigns->execute();
$myCampaigns = $qMyCampaigns->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Galang Dana</title>
    <link href="./src/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #00aa13; box-shadow: 0 0 0 3px rgba(0,170,19,0.12); }
    </style>
</head>
<body class="bg-[#f5f5f5] text-[#333] leading-relaxed pb-24">

    <!-- Header -->
    <header class="max-w-[500px] mx-auto bg-white px-5 py-4 flex items-center sticky top-0 z-10 shadow-sm">
        <a href="index.php" class="text-[1.2rem] mr-4 text-gray-600">←</a>
        <h1 class="text-[1rem] font-bold">Galang Dana</h1>
    </header>

    <div class="max-w-[500px] mx-auto px-5 pt-6 pb-10">

        <!-- Notifikasi -->
        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-5 text-sm font-semibold">
            ✅ <?php
                if ($_GET['success'] == 'submitted') echo 'Kampanye berhasil diajukan! Tim kami akan meninjau dan mengaktifkannya.';
            ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-5">
            <?php
                if ($_GET['error'] == 'empty_fields')   echo 'Semua field wajib diisi.';
                if ($_GET['error'] == 'min_target')     echo 'Target dana minimal Rp 100.000.';
                if ($_GET['error'] == 'upload_failed')  echo 'Gagal upload foto, coba lagi.';
                if ($_GET['error'] == 'invalid_file')   echo 'Format foto tidak didukung (gunakan jpg/png/webp).';
                if ($_GET['error'] == 'file_too_large') echo 'Ukuran foto terlalu besar (maks 5MB).';
                if ($_GET['error'] == 'db_error')       echo 'Terjadi kesalahan server.';
            ?>
        </div>
        <?php endif; ?>

        <!-- Form Pengajuan -->
        <div class="bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] py-7 px-6 mb-8">
            <h2 class="text-[#006600] font-bold text-xl mb-1">Buat Galang Dana Baru</h2>
            <p class="text-gray-400 text-sm mb-6">Kampanye akan ditinjau admin sebelum ditampilkan.</p>

            <form class="flex flex-col gap-5" method="post" action="auth/proses_galangdana.php" enctype="multipart/form-data">

                <!-- Judul -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Judul Kampanye</label>
                    <input type="text" name="judul" placeholder="Contoh: Bantuan Sembako untuk Lansia" required
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-sm transition-all">
                </div>

                <!-- Kategori -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Kategori</label>
                    <select name="kategori" required class="p-3.5 border border-[#d1d5db] rounded-xl text-sm transition-all bg-white cursor-pointer">
                        <option value="" disabled selected>Pilih kategori bantuan</option>
                        <option value="pendidikan">Pendidikan</option>
                        <option value="kesehatan">Kesehatan</option>
                        <option value="umkm">UMKM</option>
                        <option value="kemanusiaan">Kemanusiaan</option>
                        <option value="bencana alam">Bencana Alam</option>
                        <option value="anak yatim">Anak Yatim</option>
                        <option value="medis">Medis</option>
                    </select>
                </div>

                <!-- Target Dana -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Target Dana (Rp)</label>
                    <input type="number" name="target" min="100000" placeholder="Minimal 100.000" required
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-sm transition-all">
                </div>

                <!-- Foto Sampul -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Foto Sampul Kampanye</label>
                    <input type="file" name="foto" accept="image/*" id="foto-input"
                        class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0
                        file:text-sm file:font-semibold file:bg-[#f0f9f0] file:text-[#006600]
                        hover:file:bg-[#e2f5e2] cursor-pointer border border-[#d1d5db] rounded-xl p-2">
                    <img id="foto-preview" class="hidden mt-2 w-full h-36 object-cover rounded-xl">
                    <p class="text-xs text-gray-400">Opsional, namun sangat disarankan. Maks 5MB (jpg/png/webp).</p>
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Ceritakan Masalahnya</label>
                    <textarea name="deskripsi" rows="4" placeholder="Jelaskan siapa yang dibantu, kenapa butuh bantuan, dan bagaimana dana akan digunakan..." required
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-sm transition-all resize-none"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-[#00aa13] text-white p-4 text-base font-bold rounded-xl cursor-pointer hover:bg-[#00960f] active:translate-y-0.5 transition-all shadow-lg shadow-[#00aa13]/20 mt-2">
                    Ajukan Kampanye 🚀
                </button>
            </form>
        </div>

        <!-- Riwayat Kampanye Saya -->
        <?php if (!empty($myCampaigns)): ?>
        <h2 class="text-[1rem] font-bold text-[#333] mb-3">Kampanye Saya</h2>
        <div class="space-y-3">
            <?php foreach ($myCampaigns as $c):
                $percent = ($c['target_dana'] > 0) ? min(100, ($c['dana_terkumpul'] / $c['target_dana']) * 100) : 0;
                $statusBadge = $c['status'] === 'active'
                    ? '<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Aktif</span>'
                    : '<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Ditutup</span>';
            ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-bold text-[0.9rem]"><?= htmlspecialchars($c['nama']) ?></p>
                        <p class="text-[0.7rem] text-gray-400 capitalize"><?= htmlspecialchars($c['kategori']) ?> · <?= date('d M Y', strtotime($c['created_at'])) ?></p>
                    </div>
                    <?= $statusBadge ?>
                </div>
                <div class="bg-gray-100 h-1.5 rounded-full mb-1">
                    <div class="bg-[#00aa13] h-full rounded-full" style="width: <?= $percent ?>%"></div>
                </div>
                <div class="flex justify-between text-[0.7rem] text-gray-400">
                    <span>Terkumpul: <b class="text-[#333]">Rp <?= number_format($c['dana_terkumpul'], 0, ',', '.') ?></b></span>
                    <span><?= round($percent) ?>% dari Rp <?= number_format($c['target_dana'], 0, ',', '.') ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Nav -->
    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <a href="index.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda</a>
        <a href="donasi.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">💰</span>Donasi</a>
        <a href="riwayat.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat</a>
        <a href="akun.php" class="flex-1 text-center text-[0.7rem] text-gray-400"><span class="text-[1.4rem] block mb-0.5">👤</span>Akun</a>
    </nav>

    <script>
    document.getElementById('foto-input').addEventListener('change', function () {
        const preview = document.getElementById('foto-preview');
        if (this.files[0]) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });
    </script>
</body>
</html>
