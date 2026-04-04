<?php
    session_start();
    include '../includes/koneksi.php';

    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    // Ambil data dari form
    $id_user   = (int) $_SESSION['user_id'];
    $judul     = trim($_POST['judul'] ?? '');
    $kategori  = $_POST['kategori'] ?? '';
    $target    = (int) ($_POST['target'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Validasi input
    if (empty($judul) || empty($kategori) || empty($deskripsi)) {
        header("Location: ../galangdana.php?error=empty_fields");
        exit();
    }
    if ($target < 100000) {
        header("Location: ../galangdana.php?error=min_target");
        exit();
    }

    // Upload foto (opsional)
    $gambar = '';
    if (!empty($_FILES['foto']['name'])) {
        $file    = $_FILES['foto'];
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); // ambil ekstensi file

        if (!in_array($ext, $allowed)) { // validasi ekstensi
            header("Location: ../galangdana.php?error=invalid_file"); exit();
        }
        if ($file['size'] > 5 * 1024 * 1024) { // validasi ukuran (5MB)
            header("Location: ../galangdana.php?error=file_too_large"); exit();
        }

        $namaFile = 'campaign_' . time() . '_' . rand(100, 999) . '.' . $ext; // buat nama file unik
        $tujuan   = '../assets/img/campaigns/' . $namaFile;

        if (!is_dir('../assets/img/campaigns')) mkdir('../assets/img/campaigns', 0755, true); // jika folder belum ada, buat folder

        // jika upload gagal, hentikan proses dan beri pesan error
        if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
            header("Location: ../galangdana.php?error=upload_failed"); exit();
        }
        $gambar = $namaFile;
    }

    // Insert ke DB
    $stmt = $conn->prepare(
        "INSERT INTO campaigns (id_user, gambar, nama, kategori, target_dana, deskripsi, status) VALUES (?, ?, ?, ?, ?, ?, 'active')"
    );
    $stmt->bind_param("isssiss", $id_user, $gambar, $judul, $kategori, $target, $deskripsi);

    if ($stmt->execute()) {
        header("Location: ../galangdana.php?success=submitted");
    } else {
        header("Location: ../galangdana.php?error=db_error");
    }
    exit();