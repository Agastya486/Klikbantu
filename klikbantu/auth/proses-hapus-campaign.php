<?php
    session_start();
    include '../includes/koneksi.php';

    // Validasi role admin
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }

    // Validasi ID
    $id = (int) ($_GET['id'] ?? 0);

    if ($id <= 0) {
        header("Location: ../admin.php?error=invalid_id");
        exit();
    }

    // Ambil nama gambar dulu sebelum hapus
    $q = $conn->prepare("SELECT gambar FROM campaigns WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $row = $q->get_result()->fetch_assoc();

    if (!$row) {
        header("Location: ../admin.php?error=not_found");
        exit();
    }

    // Hapus dari DB (donations terhubung ON DELETE CASCADE)
    $del = $conn->prepare("DELETE FROM campaigns WHERE id = ?");
    $del->bind_param("i", $id);

    if ($del->execute()) {
        // Hapus file gambar dari server jika ada
        if (!empty($row['gambar']) && file_exists('../assets/img/campaigns/' . $row['gambar'])) {
            unlink('../assets/img/campaigns/' . $row['gambar']);
        }
        header("Location: ../admin.php?success=campaign_deleted");
    } else {
        header("Location: ../admin.php?error=db_error");
    }
    exit();