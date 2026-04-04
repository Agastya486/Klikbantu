<?php
    session_start();
    include '../includes/koneksi.php';

    // Cek apakah user sudah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    if (isset($_POST['ubah_password'])) {
        // Ambil data dari form
        $id             = (int) $_SESSION['user_id'];
        $currentPass    = $_POST['current_password'];
        $newPass        = $_POST['new_password'];
        $confirmNewPass = $_POST['confirm_new_password'];

        // Validasi panjang
        if (strlen($newPass) < 8) {
            header("Location: ../keamanan.php?error=password_length_wrong");
            exit();
        }

        // Validasi konfirmasi
        if ($newPass !== $confirmNewPass) {
            header("Location: ../keamanan.php?error=password_mismatch");
            exit();
        }

        // Ambil hash dari DB — prepared statement
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        // Verifikasi password lama
        if (!$user || !password_verify($currentPass, $user['password'])) {
            header("Location: ../keamanan.php?error=password_wrong");
            exit();
        }

        // Update password baru — prepared statement
        $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
        $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $upd->bind_param("si", $hashedNewPass, $id);

        if ($upd->execute()) {
            header("Location: ../keamanan.php?success=change_password_success");
        } else {
            header("Location: ../keamanan.php?error=something_wrong");
        }
        exit();
    }