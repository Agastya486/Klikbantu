<?php
session_start();
include './includes/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_campaign = (int) ($_GET['id'] ?? 0);

if ($id_campaign <= 0) {
    header("Location: donasi.php");
    exit();
}

// Ambil data campaign
$stmt = $conn->prepare("SELECT * FROM campaigns WHERE id = ? AND status = 'active'");
$stmt->bind_param("i", $id_campaign);
$stmt->execute();
$campaign = $stmt->get_result()->fetch_assoc();

if (!$campaign) {
    header("Location: donasi.php?error=not_found");
    exit();
}

$percent = ($campaign['target_dana'] > 0) ? min(100, ($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100) : 0;
$gambarSrc = !empty($campaign['gambar']) && file_exists('assets/img/campaigns/' . $campaign['gambar'])
    ? 'assets/img/campaigns/' . $campaign['gambar']
    : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=400&q=80';

$user_id   = $_SESSION['user_id'];
$qUser     = $conn->prepare("SELECT nama FROM users WHERE id = ?");
$qUser->bind_param("i", $user_id);
$qUser->execute();
$user = $qUser->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Form Donasi</title>
    <link href="./src/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#f5f5f5] text-[#333] leading-relaxed pb-10">

    <!-- Header -->
    <header class="max-w-[500px] mx-auto bg-white px-5 py-4 flex items-center sticky top-0 z-10 shadow-sm">
        <a href="donasi.php" class="text-[1.2rem] mr-4 text-gray-600">←</a>
        <h1 class="text-[1rem] font-bold">Form Donasi</h1>
    </header>

    <div class="max-w-[500px] mx-auto px-5 pt-6">

        <!-- Info Campaign -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm mb-6">
            <img src="<?= htmlspecialchars($gambarSrc) ?>" class="w-full h-36 object-cover" alt="<?= htmlspecialchars($campaign['nama']) ?>">
            <div class="p-4">
                <span class="text-[0.65rem] font-bold text-[#00aa13] uppercase tracking-wider"><?= htmlspecialchars($campaign['kategori']) ?></span>
                <h2 class="font-bold text-[1rem] mt-1 mb-2"><?= htmlspecialchars($campaign['nama']) ?></h2>
                <div class="bg-gray-100 h-1.5 rounded-full mb-2">
                    <div class="bg-[#00aa13] h-full rounded-full" style="width: <?= $percent ?>%"></div>
                </div>
                <div class="flex justify-between text-[0.7rem] text-gray-400">
                    <span>Terkumpul: <b class="text-[#333]">Rp <?= number_format($campaign['dana_terkumpul'], 0, ',', '.') ?></b></span>
                    <span><?= round($percent) ?>% dari Rp <?= number_format($campaign['target_dana'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Form Donasi -->
        <div class="bg-white rounded-2xl shadow-sm py-6 px-5 mb-6">
            <h3 class="text-[#006600] font-bold text-lg mb-5">Isi Detail Donasimu</h3>

            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-4">
                <?php
                    if ($_GET['error'] == 'empty_fields') echo 'Semua field wajib diisi.';
                    if ($_GET['error'] == 'min_amount')   echo 'Minimal donasi Rp 10.000.';
                    if ($_GET['error'] == 'db_error')     echo 'Gagal memproses donasi, coba lagi.';
                ?>
            </div>
            <?php endif; ?>

            <form id="form-donasi" class="flex flex-col gap-5" method="post">
                <input type="hidden" name="id_campaign" value="<?= $id_campaign ?>">

                <!-- Nama -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Nama Donatur</label>
                    <input type="text" id="nama-donatur" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-sm focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-[#666] hover:text-[#00aa13]">
                        <input type="checkbox" id="checkbox-anonim" name="is_anonim" value="1" class="w-4 h-4 accent-[#00aa13] cursor-pointer">
                        Donasi sebagai Anonim
                    </label>
                    <p id="msg-anonim" class="hidden text-[11px] text-[#00aa13] font-medium ml-6 italic">✨ Namamu akan dirahasiakan dari publik.</p>
                </div>

                <!-- Nominal Cepat -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Pilih Nominal</label>
                    <div class="grid grid-cols-3 gap-2">
                        <?php foreach ([10000, 25000, 50000, 100000, 250000, 500000] as $nom): ?>
                        <button type="button" onclick="setNominal(<?= $nom ?>)"
                            class="nominal-btn bg-[#f0fff0] text-[#006600] text-[0.75rem] font-semibold py-2 rounded-xl border border-green-100 active:bg-green-100 transition-all cursor-pointer">
                            Rp <?= number_format($nom, 0, ',', '.') ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Jumlah -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Jumlah Donasi (Rp)</label>
                    <input type="number" id="jumlah-donasi" name="nominal" min="10000" step="1000" placeholder="Minimal 10.000" required
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-sm focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15">
                </div>

                <!-- Pesan -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-[#444] text-sm">Pesan (opsional)</label>
                    <textarea name="pesan" rows="2" placeholder="Semangat, kami doakan kebaikanmu..."
                        class="p-3.5 border border-[#d1d5db] rounded-xl text-sm focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15 resize-none"></textarea>
                </div>

                <button type="button" id="btn-submit" class="w-full bg-[#00aa13] text-white p-4 text-base font-bold rounded-xl cursor-pointer hover:bg-[#00960f] active:translate-y-0.5 transition-all">
                    Lanjut Pembayaran 💳
                </button>
            </form>
        </div>
    </div>

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-HT_tSHO7ipGR_kQ8"></script>
    <script>
    // Fitur Anonim
    const checkboxAnonim = document.getElementById('checkbox-anonim');
    const msgAnonim      = document.getElementById('msg-anonim');
    const namaInput      = document.getElementById('nama-donatur');
    let namaAsli = '';

    checkboxAnonim.addEventListener('change', function () {
        // Simpan nama asli saat pertama kali dicentang
        if (this.checked) {
            namaAsli = namaInput.value;
            namaInput.value = 'Hamba Allah';
            namaInput.readOnly = true;
            namaInput.classList.add('bg-gray-50', 'text-gray-500');
            msgAnonim.classList.remove('hidden');
        } else {
            namaInput.value = namaAsli;
            namaInput.readOnly = false;
            namaInput.classList.remove('bg-gray-50', 'text-gray-500');
            msgAnonim.classList.add('hidden');
        }
    });

    function setNominal(val) {
        document.getElementById('jumlah-donasi').value = val;
        document.querySelectorAll('.nominal-btn').forEach(b => b.classList.remove('ring-2', 'ring-[#00aa13]', 'bg-[#00aa13]', 'text-white')); // Hapus kelas aktif dari semua tombol
        event.target.classList.add('ring-2', 'ring-[#00aa13]', 'bg-[#00aa13]', 'text-white');
    }

    document.getElementById('btn-submit').addEventListener('click', async () => {
        const nama    = namaInput.value.trim(); // Ambil nama dari input
        const nominal = document.getElementById('jumlah-donasi').value;
        const isAnonim = checkboxAnonim.checked ? 1 : 0;

        if (!nama || !nominal || Number(nominal) < 10000) {
            alert('Mohon isi nama dan jumlah donasi minimal Rp 10.000!');
            return;
        }

        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        const form = document.getElementById('form-donasi');
        const formData = new FormData(form);
        formData.set('nama', nama);
        formData.set('is_anonim', isAnonim);

        try {
            const res  = await fetch('auth/proses_bayar_donasi.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.token) {
                // Panggil Midtrans Snap dengan token yang diterima
                window.snap.pay(data.token, {
                    onSuccess: function (result) {
                        window.location.href = 'riwayat.php?success=donasi_sukses';
                    },
                    onPending: function (result) {
                        window.location.href = 'riwayat.php?info=menunggu_pembayaran';
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                        btn.disabled = false;
                        btn.textContent = 'Lanjut Pembayaran 💳';
                    },
                    onClose: function () {
                        btn.disabled = false;
                        btn.textContent = 'Lanjut Pembayaran 💳';
                    }
                });
            } else {
                alert('Error: ' + (data.error || 'Gagal mendapatkan token pembayaran.'));
                btn.disabled = false;
                btn.textContent = 'Lanjut Pembayaran 💳';
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi. Periksa konsol browser.');
            console.error(err);
            btn.disabled = false;
            btn.textContent = 'Lanjut Pembayaran 💳';
        }
    });
    </script>
</body>
</html>
