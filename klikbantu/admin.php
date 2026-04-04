<?php
session_start();
include 'includes/koneksi.php'; 

// Proteksi Role
if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

// 2. QUERY JUMLAH CAMPAIGN AKTIF (Untuk Counter Kecil)
$qCount = $conn->query("SELECT COUNT(*) as total FROM campaigns WHERE status = 'active'");
$dataCount = $qCount->fetch_assoc();
$totalActive = $dataCount['total'] ?? 0;
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
            </nav>
            <div class="mt-auto pt-6 border-t border-green-700">
                <a href="auth/logout.php" class="text-sm text-green-200 hover:text-white transition-colors">⬅️ logout</a>
            </div>
        </aside>

        <main class="flex-1 md:ml-64 p-8">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-black text-gray-900">Campaign Central ⚡️</h2>
                    <p class="text-sm text-gray-500">Total Campaign Aktif: <span class="font-bold text-[#006600]"><?= $totalActive ?></span></p>
                </div>
                <button onclick="toggleModal('add')" class="bg-[#00aa13] hover:bg-[#00960f] text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg transition-all transform hover:scale-105 cursor-pointer">
                    + Program Baru
                </button>
            </header>

            <section id="campaigns">
                <h3 class="font-bold text-xl mb-6 flex items-center gap-2 text-gray-800">
                    📂 Daftar Program <span class="text-xs bg-gray-200 px-2 py-1 rounded-lg">Live Database</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php 
                    $qList = $conn->query("SELECT * FROM campaigns ORDER BY created_at DESC");
                    while($row = $qList->fetch_assoc()):
                        $percent = ($row['target_dana'] > 0) ? ($row['dana_terkumpul'] / $row['target_dana']) * 100 : 0;
                    ?>
                    <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase italic"><?= $row['kategori'] ?></span>
                            <div class="flex gap-2 text-xs">
                                <button onclick="editCampaign(<?= htmlspecialchars(json_encode($row)) ?>)" class="p-2 hover:bg-blue-50 text-blue-500 rounded-lg transition-colors">✏️ Edit</button>
                                <a href="proses-hapus-campaign.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus campaign ini?')" class="p-2 hover:bg-red-50 text-red-500 rounded-lg transition-colors">🗑️ Hapus</a>
                            </div>
                        </div>
                        <h4 class="font-bold text-lg mb-1"><?= $row['nama'] ?></h4>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2"><?= $row['deskripsi'] ?></p>
                        
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-[#00aa13] h-full transition-all duration-500" style="width: <?= $percent ?>%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <span>Target: Rp <?= number_format($row['target_dana'], 0, ',', '.') ?></span>
                            <span><?= round($percent) ?>%</span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </main>
    </div>

    <div id="campaignModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-2xl font-black">Program Baru 🚀</h3>
                    <button onclick="toggleModal()" class="text-gray-400 hover:text-black text-2xl">×</button>
                </div>

                <form action="proses-campaign.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id" id="form-id">
                    <input type="hidden" name="action" id="form-action" value="add">

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Banner Program</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-[#00aa13] outline-none">
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">*Kosongkan jika tidak ingin mengubah gambar (saat edit)</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Nama Program</label>
                        <input type="text" name="nama" id="form-nama" required placeholder="Contoh: Zakat Fitrah 2026" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Kategori</label>
                            <select name="kategori" id="form-kategori" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none">
                                <option value="pendidikan">Pendidikan</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="umkm">UMKM</option>
                                <option value="kemanusiaan">Kemanusiaan</option>
                                <option value="bencana alam">Bencana alam</option>
                                <option value="anak yatim">Anak yatim</option>
                                <option value="medis">Medis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Target Dana</label>
                            <input type="number" name="target_dana" id="form-target" required placeholder="Rp" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1 text-black">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" id="form-deskripsi" required rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 focus:ring-2 focus:ring-[#00aa13] outline-none" placeholder="Ceritakan tujuan program ini..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-[#006600] text-white font-bold py-4 rounded-2xl mt-4 hover:bg-black transition-all cursor-pointer">
                        Simpan Data Ke Database
                    </button>
                </form>
            </div>
        </div>
    </div>
  <script src="admin.js"></script>
</body>
</html>