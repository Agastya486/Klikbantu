<?php
  if(isset($_POST['login'])) {
    include '../includes/koneksi.php';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkquery = "SELECT * FROM users WHERE email='$email'";
    $checkresult = $conn->query($checkquery);

    if($checkresult -> num_rows > 0) {
      $row = $checkresult->fetch_assoc();
      if(password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['user_id'] = $row['id'];
        if ($row['role'] === 'admin') {
          $_SESSION['role'] = 'admin';
          header("Location: ../admin.php");
          exit();
        } else {
          $_SESSION['role'] = 'user';
          header("Location: ../index.php");
          exit();
        }
      } else {
        header("Location: ../login.php?error=wrong_pass_or_email");
      }
    } else {
      header("Location: ../login.php?error=not_found");
    }
  }
?>