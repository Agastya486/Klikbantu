<?php
    session_start();
    include '../includes/koneksi.php';

    // Cek role admin
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }

    $action = $_POST['action'] ?? '';

    // Untuk upload gambar
    function uploadGambar($file, $conn, $id = null) {
        if (empty($file['name'])) return null; // Tidak ada file baru diupload

        // Validasi file
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); // ext maksudnya ekstensi file

        // Validasi ekstensi dan ukuran
        if (!in_array($ext, $allowed)) { header("Location: ../admin.php?error=invalid_file"); exit(); }
        if ($file['size'] > 5 * 1024 * 1024) { header("Location: ../admin.php?error=file_too_large"); exit(); }

        // Generate nama file unik
        $namaFile = 'campaign_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $tujuan   = '../assets/img/campaigns/' . $namaFile;

        // Pastikan folder tujuan ada
        if (!is_dir('../assets/img/campaigns')) mkdir('../assets/img/campaigns', 0755, true);
        if (!move_uploaded_file($file['tmp_name'], $tujuan)) { header("Location: ../admin.php?error=upload_failed"); exit(); } // Jika gagal upload

        // Hapus gambar lama jika ada (hanya untuk edit)
        if ($id) {
            $q = $conn->prepare("SELECT gambar FROM campaigns WHERE id = ?");
            $q->bind_param("i", $id);
            $q->execute();
            $old = $q->get_result()->fetch_assoc();
            if (!empty($old['gambar']) && file_exists('../assets/img/campaigns/' . $old['gambar'])) {
                unlink('../assets/img/campaigns/' . $old['gambar']);
            }
        }
        return $namaFile;
    }

    // ============================================================
    // ADD
    // ============================================================
    if ($action === 'add') {
        // Ambil data dari form
        $nama      = trim($_POST['nama']);
        $kategori  = $_POST['kategori'];
        $target    = (int) $_POST['target_dana'];
        $deskripsi = trim($_POST['deskripsi']);
        $status    = $_POST['status'] ?? 'active'; // Default status aktif
        $id_user   = (int) $_SESSION['user_id'];

        // Validasi input
        if (empty($nama) || empty($kategori) || $target <= 0 || empty($deskripsi)) {
            header("Location: ../admin.php?error=empty_fields"); exit();
        }

        $gambar = uploadGambar($_FILES['gambar'], $conn) ?? ''; // Jika tidak ada gambar, simpan sebagai string kosong

        $stmt = $conn->prepare("INSERT INTO campaigns (id_user, gambar, nama, kategori, target_dana, deskripsi, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssiss", $id_user, $gambar, $nama, $kategori, $target, $deskripsi, $status); // i=integer, s=string

        if ($stmt->execute()) {
            header("Location: ../admin.php?success=campaign_added");
        } else {
            header("Location: ../admin.php?error=db_error");
        }
        exit();
    }

    // ============================================================
    // EDIT
    // ============================================================
    if ($action === 'edit') {
        // Ambil data dari form
        $id        = (int) $_POST['id'];
        $nama      = trim($_POST['nama']);
        $kategori  = $_POST['kategori'];
        $target    = (int) $_POST['target_dana'];
        $deskripsi = trim($_POST['deskripsi']);
        $status    = $_POST['status'] ?? 'active';

        // Validasi input
        if ($id <= 0 || empty($nama) || empty($kategori) || $target <= 0 || empty($deskripsi)) {
            header("Location: ../admin.php?error=empty_fields"); exit();
        }

        $gambarBaru = uploadGambar($_FILES['gambar'], $conn, $id);

        if ($gambarBaru) {
            $stmt = $conn->prepare("UPDATE campaigns SET nama=?, kategori=?, target_dana=?, deskripsi=?, status=?, gambar=? WHERE id=?");
            $stmt->bind_param("ssisssi", $nama, $kategori, $target, $deskripsi, $status, $gambarBaru, $id);
        } else {
            $stmt = $conn->prepare("UPDATE campaigns SET nama=?, kategori=?, target_dana=?, deskripsi=?, status=? WHERE id=?");
            $stmt->bind_param("ssissi", $nama, $kategori, $target, $deskripsi, $status, $id);
        }

        if ($stmt->execute()) {
            header("Location: ../admin.php?success=campaign_updated");
        } else {
            header("Location: ../admin.php?error=db_error");
        }
        exit();
    }

    header("Location: ../admin.php");
    exit();