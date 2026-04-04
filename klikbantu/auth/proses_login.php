<?php
    if (isset($_POST['login'])) {
        session_start();
        include '../includes/koneksi.php';

        // Ambil data dari form login
        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, nama, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah email ditemukan
        if ($result->num_rows === 0) {
            header("Location: ../login.php?error=not_found");
            exit();
        }

        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['nama'];
            $_SESSION['role']     = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: ../admin.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            header("Location: ../login.php?error=wrong_pass_or_email");
            exit();
        }
    } else {
        header("Location: ../login.php");
        exit();
    }