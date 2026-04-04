<?php
    if (isset($_POST['register'])) {
        include '../includes/koneksi.php';

        // Ambil dan trim input
        $username        = trim($_POST['username']);
        $email           = trim($_POST['email']);
        $password        = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        // Validasi tidak boleh kosong
        if (empty($username) || empty($email) || empty($password)) {
            header("Location: ../registrasi.php?error=empty_fields");
            exit();
        }

        // Validasi panjang password
        if (strlen($password) < 8) {
            header("Location: ../registrasi.php?error=short_password");
            exit();
        }

        // Validasi konfirmasi password
        if ($password !== $confirmPassword) {
            header("Location: ../registrasi.php?error=password_mismatch");
            exit();
        }

        // Cek duplikat email — prepared statement
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        // Jika email sudah terdaftar
        if ($check->num_rows > 0) {
            header("Location: ../registrasi.php?error=used_pass_or_email");
            exit();
        }

        // Insert user baru — prepared statement
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: ../login.php?success=registration_success");
        } else {
            header("Location: ../registrasi.php?error=something_wrong");
        }
        exit();
    } else {
        header("Location: ../registrasi.php");
        exit();
    }