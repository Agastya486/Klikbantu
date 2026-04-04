<?php
  if(isset($_POST['register'])) {
    include '../includes/koneksi.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validasi panjang password
    if(strlen($password) < 8) {
      header("Location: ../registrasi.php?error=short_password");
      exit();
    }

    // Query untuk cek apakah username atau email sudah digunakan
    $checkQuery = "SELECT * FROM users WHERE nama='$username' OR email='$email'";
    $checkResult = $conn->query($checkQuery);

    // Cek jika username atau email sudah ada
    if ($checkResult->num_rows > 0) {
        header("Location: ../registrasi.php?error=used_pass_or_email");
    } else {
      // Cek apakah password sudah sama
      if($password ==  $confirmPassword){
        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
        // Simpan data pengguna baru ke database
        $insertQuery = "INSERT INTO users (nama, email, password) VALUES ('$username', '$email', '$hashedPassword')";
        
        // Cek apakah query insert berhasil
        if ($conn->query($insertQuery) === TRUE) {
          header("Location: ../login.php?success=registration_success");
        } else {
          header("Location: ../registrasi.php?error=something_wrong");
        }
      } else {
        header("Location: ../registrasi.php?error=password_mismatch");
      }
    }

    $conn->close();
  } else {
    header("Location: registrasi.html");
    exit();
  }
?>