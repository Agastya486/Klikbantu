<?php
  session_start();
  include '../includes/koneksi.php';

  if(isset($_POST['update'])){
    $id = $_SESSION['user_id'];
    
    // Ambil dari input FORM
    $name = $conn->real_escape_string($_POST['new_name']);
    $email = $conn->real_escape_string($_POST['new_email']);
    $phoneNum = $_POST['new_phone_number'];
    $bio = $conn->real_escape_string($_POST['new_bio']);

    // Validasi nomor telepon
    if (!empty($phoneNum)) {
        // 1. Bersihkan semua karakter selain angka
        $phoneNum = preg_replace('/[^0-9]/', '', $phoneNum);

        // 2. Validasi panjang angka
        if (strlen($phoneNum) < 10 || strlen($phoneNum) > 14) {
            header("Location: ../editprofile.php?error=phone_invalid");
            exit();
        }
    } else {
        // Kalau kosong, set jadi null atau string kosong biar gak error di DB
        $phoneNum = ""; 
    }

    $sql = "UPDATE users SET nama='$name', email='$email', no_telp='$phoneNum', bio='$bio' WHERE id='$id'";

    if($conn->query($sql)){
      $_SESSION['username'] = $name;
      header("Location: ../editprofile.php?success=update_success");
    } else {
      header("Location: ../editprofile.php?error=update_failed");
    }
    exit();
  }
?>