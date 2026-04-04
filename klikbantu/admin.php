<?php
    session_start();
    include 'includes/koneksi.php';

    if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

    $qCount = $conn->query("SELECT COUNT(*) as total FROM campaigns WHERE status = 'active'");
    $totalActive = $qCount->fetch_assoc()['total'] ?? 0;

    // Total donasi & zakat masuk
    $qDonasi = $conn->query("SELECT COALESCE(SUM(nominal),0) as total FROM donations WHERE status_pembayaran='success'");
    $totalDonasi = $qDonasi->fetch_assoc()['total'];

    $qZakat = $conn->query("SELECT COALESCE(SUM(nominal),0) as total FROM zakats WHERE status_pembayaran='success'");
    $totalZakat = $qZakat->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard - KlikBantu</title>
        <link href="./src/output.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
            .modal-active { display: flex !important; }
        </style>
    </head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#006600] text-white hidden md:flex flex-col p-6 fixed h-full shadow-xl">
            <div class="mb-10">
                <h1 class="text-2xl font-bold italic tracking-tight">KlikBantu</h1>
                <p class="text-xs text-green-200">Admin Panel v1.0</p>
            </div>
            <nav class="flex-1 flex flex-col gap-2 mt-10">
                <a href="admin.php" class="flex items-center gap-3 p-3 rounded-xl font-semibold bg-white/20 transition-all">
                    <span>📂</span> Atur Campaign
                </a>
                <a href="admin-riwayat.php" class="flex items-center gap-3 p-3 rounded-xl font-semibold hover:bg-white/20 transition-all">
                    <span>💸</span> Riwayat Transaksi
                </a>
            </nav>
            <div class="mt-auto pt-6 border-t border-green-700">
                <a href="auth/logout.php" class="text-sm text-green-200 hover:text-white transition-colors mt-2 block">🚪 Logout</a>
            </div>
        </aside>

        <main class="flex-1 md:ml-64 p-8">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900">Campaign Central ⚡️</h2>
                    <p class="text-sm text-gray-500">Total Campaign Aktif: <span class="font-bold text-[#006600]"><?= $totalActive ?></span></p>
                </div>
                <button onclick="toggleModal('add')" class="bg-[#00aa13] hover:bg-[#00960f] text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg transition-all transform hover:scale-105 cursor-pointer">
                    + Program Baru
                </button>
            </header>

            <!-- Notifikasi -->
            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl mb-6 text-sm font-semibold">
                ✅ <?php
                    if ($_GET['success'] == 'campaign_added')   echo 'Campaign berhasil ditambahkan!';
                    if ($_GET['success'] == 'campaign_updated') echo 'Campaign berhasil diperbarui!';
                    if ($_GET['success'] == 'campaign_deleted') echo 'Campaign berhasil dihapus!';
                ?>
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-2xl mb-6 text-sm font-semibold">
                ❌ <?php
                    if ($_GET['error'] == 'empty_fields')  echo 'Semua field wajib diisi!';
                    if ($_GET['error'] == 'upload_failed') echo 'Gagal upload gambar, coba lagi.';
                    if ($_GET['error'] == 'invalid_file')  echo 'Format file tidak didukung (gunakan jpg/png/webp).';
                    if ($_GET['error'] == 'file_too_large') echo 'Ukuran file terlalu besar (maks 5MB).';
                    if ($_GET['error'] == 'db_error')      echo 'Terjadi kesalahan database.';
                ?>
            </div>
            <?php endif; ?>

            <!-- Statistik Ringkas -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Campaign Aktif</p>
                    <p class="text-2xl font-black text-[#006600]"><?= $totalActive ?></p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Total Donasi Masuk</p>
                    <p class="text-xl font-black text-[#006600]">Rp <?= number_format($totalDonasi, 0, ',', '.') ?></p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Total Zakat Masuk</p>
                    <p class="text-xl font-black text-[#006600]">Rp <?= number_format($totalZakat, 0, ',', '.') ?></p>
                </div>
            </div>

            <section id="campaigns">
                <h3 class="font-bold text-xl mb-6 flex items-center gap-2 text-gray-800">
                    📂 Daftar Program <span class="text-xs bg-gray-200 px-2 py-1 rounded-lg">Live Database</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $qList = $conn->query("SELECT * FROM campaigns ORDER BY created_at DESC");
                    while ($row = $qList->fetch_assoc()):
                        $percent = ($row['target_dana'] > 0) ? min(100, ($row['dana_terkumpul'] / $row['target_dana']) * 100) : 0;
                        $gambarSrc = !empty($row['gambar']) && file_exists('assets/img/campaigns/' . $row['gambar'])
                            ? 'assets/img/campaigns/' . $row['gambar']
                            : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=400&q=80';
                        $statusBadge = $row['status'] === 'active'
                            ? '<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Aktif</span>'
                            : '<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Ditutup</span>';
                    ?>
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <img src="<?= htmlspecialchars($gambarSrc) ?>" class="w-full h-36 object-cover">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex gap-2 items-center flex-wrap">
                                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase italic"><?= htmlspecialchars($row['kategori']) ?></span>
                                    <?= $statusBadge ?>
                                </div>
                                <div class="flex gap-1 text-xs">
                                    <button onclick="editCampaign(<?= htmlspecialchars(json_encode($row)) ?>)" class="p-2 hover:bg-blue-50 text-blue-500 rounded-lg transition-colors cursor-pointer">✏️</button>
                                    <a href="auth/proses-hapus-campaign.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus campaign ini? Semua donasi terkait juga akan terhapus.')" class="p-2 hover:bg-red-50 text-red-500 rounded-lg transition-colors">🗑️</a>
                                </div>
                            </div>
                            <h4 class="font-bold text-base mb-1"><?= htmlspecialchars($row['nama']) ?></h4>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2"><?= htmlspecialchars($row['deskripsi']) ?></p>
                            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-[#00aa13] h-full transition-all duration-500" style="width: <?= $percent ?>%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                <span>Terkumpul: Rp <?= number_format($row['dana_terkumpul'], 0, ',', '.') ?></span>
                                <span><?= round($percent) ?>% / Rp <?= number_format($row['target_dana'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Add/Edit Campaign -->
    <div id="campaignModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-2xl font-black">Program Baru 🚀</h3>
                    <button onclick="toggleModal()" class="text-gray-400 hover:text-black text-2xl cursor-pointer">×</button>
                </div>
                <form action="auth/proses-campaign.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id" id="form-id">
                    <input type="hidden" name="action" id="form-action" value="add">

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Banner Program</label>
                        <input type="file" name="gambar" id="form-gambar" accept="image/*" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 text-sm outline-none">
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">*Kosongkan jika tidak ingin mengubah gambar (saat edit). Max 5MB (jpg/png/webp)</p>
                        <img id="preview-gambar" src="" class="hidden mt-2 w-full h-32 object-cover rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Nama Program</label>
                        <input type="text" name="nama" id="form-nama" required placeholder="Contoh: Zakat Fitrah 2026" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Kategori</label>
                            <select name="kategori" id="form-kategori" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none cursor-pointer">
                                <option value="pendidikan">Pendidikan</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="umkm">UMKM</option>
                                <option value="kemanusiaan">Kemanusiaan</option>
                                <option value="bencana alam">Bencana Alam</option>
                                <option value="anak yatim">Anak Yatim</option>
                                <option value="medis">Medis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Target Dana</label>
                            <input type="number" name="target_dana" id="form-target" required placeholder="Rp" min="100000" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Status</label>
                        <select name="status" id="form-status" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none cursor-pointer">
                            <option value="active">Aktif</option>
                            <option value="closed">Ditutup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" id="form-deskripsi" required rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none" placeholder="Ceritakan tujuan program ini..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-[#006600] text-white font-bold py-4 rounded-2xl mt-4 hover:bg-[#004d00] transition-all cursor-pointer">
                        Simpan Data Ke Database
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
    <script>
    // Preview gambar sebelum upload
    document.getElementById('form-gambar').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('preview-gambar');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });
    </script>
</body>
</html>
