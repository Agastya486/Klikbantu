<?php
session_start();
include '../includes/koneksi.php';

if(isset($_POST['ubah_password'])) {
    $id = $_SESSION['user_id'];

    $currentPass = $_POST['current_password'];
    $newPass = $_POST['new_password'];
    $confirmNewPass = $_POST['confirm_new_password'];

    //validasi panjang
    if(strlen($newPass) < 8){
        header("Location: ../keamanan.php?error=password_length_wrong");
        exit();
    }

    //cek komfirmasi password baru
    if($newPass !== $confirmNewPass){
        header("Location: ../keamanan.php?error=password_mismatch");
        exit();
    }

    //ambil password hash asli dari db
    $query = $conn->query("SELECT password FROM users WHERE id='$id'");
    $user = $query->fetch_assoc();

    //cek jika passwordnya benar dan bandingkan input dengan hash di db
    if ($user && password_verify($currentPass, $user['password'])) {
        
        //hash password sebelum disimpan
        $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
        
        $update = $conn->query("UPDATE users SET password='$hashedNewPass' WHERE id='$id'");
        
        if($update){
            header("Location: ../keamanan.php?success=change_password_success");
        } else {
            header("Location: ../keamanan.php?error=something_wrong");
        }
    } else {
        // Password lama salah
        header("Location: ../keamanan.php?error=password_wrong");
    }
    exit();
}