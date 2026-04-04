<?php
session_start();
include '../includes/koneksi.php';

if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0){
    $id = $_SESSION['user_id'];
    
    //ambil info file yang diupload
    $file_name = $_FILES['avatar']['name']; //nama file aslinya
    $file_tmp  = $_FILES['avatar']['tmp_name']; //lokasi file sementara
    $ekstensi  = pathinfo($file_name, PATHINFO_EXTENSION); //ambil ekstensinya (jpg/png)
    
    //buat nama unik (ubah nama filenya)
    $nama_baru = "avatar_" . $id . "_" . time() . "." . $ekstensi;
    $folder_tujuan = "../assets/img/avatars/" . $nama_baru;

    //cek foto lama di db biar bisa dihapus untuk diganti yg baru
    $query_lama = $conn->query("SELECT avatar FROM users WHERE id='$id'");
    $row_lama = $query_lama->fetch_assoc();
    $foto_lama = $row_lama['avatar'];

    //proses pindahin file baru
    if(move_uploaded_file($file_tmp, $folder_tujuan)){ 
        //hapus file lama(biar gak menuhin server)
        //cek apakah kolom avatar gak kosong & filenya beneran ada di folder?
        if(!empty($foto_lama) && file_exists("../assets/img/avatars/" . $foto_lama)){
            unlink("../assets/img/avatars/" . $foto_lama);
        }

        //update nama file baru ke database
        $sql = "UPDATE users SET avatar='$nama_baru' WHERE id='$id'";
        
        if($conn->query($sql)){
            header("Location: ../editprofile.php?success=avatar_updated");
        } else {
            header("Location: ../editprofile.php?error=db_error");
        }
    } else {
        header("Location: ../editprofile.php?error=upload_failed");
    }
} else {
    header("Location: ../editprofile.php?error=no_file");
}
exit();