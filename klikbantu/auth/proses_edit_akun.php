<?php
    session_start();
    include '../includes/koneksi.php';

    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    if (isset($_POST['update'])) {
        // Ambil data dari form
        $id        = (int) $_SESSION['user_id'];
        $name      = trim($_POST['new_name']);
        $email     = trim($_POST['new_email']);
        $phoneNum  = $_POST['new_phone_number'];
        $bio       = trim($_POST['new_bio']);

        // Validasi tidak boleh kosong
        if (empty($name) || empty($email)) {
            header("Location: ../editProfile.php?error=update_failed");
            exit();
        }

        // Validasi & bersihkan nomor telepon
        if (!empty($phoneNum)) {
            $phoneNum = preg_replace('/[^0-9]/', '', $phoneNum);
            if (strlen($phoneNum) < 10 || strlen($phoneNum) > 14) {
                header("Location: ../editProfile.php?error=phone_invalid");
                exit();
            }
        } else {
            $phoneNum = '';
        }

        // Cek email sudah dipakai user lain
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkEmail->bind_param("si", $email, $id);
        $checkEmail->execute();
        $checkEmail->store_result();
        if ($checkEmail->num_rows > 0) {
            header("Location: ../editProfile.php?error=email_used");
            exit();
        }

        // Update dengan prepared statement
        $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, no_telp=?, bio=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phoneNum, $bio, $id);

        if ($stmt->execute()) {
            $_SESSION['username'] = $name;
            header("Location: ../editProfile.php?success=update_success");
        } else {
            header("Location: ../editProfile.php?error=update_failed");
        }
        exit();
    }