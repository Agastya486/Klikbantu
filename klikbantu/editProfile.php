<?php 
    session_start();
    include './includes/koneksi.php';

    if(!isset($_SESSION['user_id'])) { header("Location: ../login.php"); }

    $id = $_SESSION['user_id'];
    $query = $conn->query("SELECT * FROM users WHERE id='$id'");
    $user = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KlikBantu - Edit Profil</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="./src/output.css" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
            input:focus { outline: none; border-color: #00aa13; box-shadow: 0 0 0 2px rgba(0, 170, 19, 0.1); } /* Saat input diklik, muncul ini */
        </style>
    </head>
    <body class="bg-[#f8f9fa] text-[#333]">

        <!-- Header -->
        <header class="max-w-[500px] mx-auto bg-white px-5 py-4 flex items-center sticky top-0 z-10 shadow-sm">
            <a href="akun.php" class="text-[1.2rem] mr-4 text-gray-600">←</a>
            <h1 class="text-[1.1rem] font-bold">Edit Profil</h1>
        </header>

        <!-- Main -->
        <main class="max-w-[500px] mx-auto px-5 pt-8 pb-10">
            <div class="flex flex-col items-center mb-8">
                <!-- Form Update Avatar -->
                <form action="auth/proses_update_avatar.php" method="POST" enctype="multipart/form-data" id="formAvatar">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-white shadow-md">
                            <img src="<?php echo !empty($user['avatar']) ? 'assets/img/avatars/'.$user['avatar'] : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80' ?>" 
                                alt="Profile" class="w-full h-full object-cover">
                        </div>

                        <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="document.getElementById('formAvatar').submit()">

                        <!-- Ikon Kamera -->
                        <label for="avatarInput" class="absolute bottom-0 right-0 bg-[#00aa13] text-white p-2 rounded-full shadow-lg border-2 border-white cursor-pointer hover:bg-[#008810] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                            </svg>
                        </label>
                    </div>
                </form>
                <p class="text-[0.75rem] text-gray-400 mt-3">Ketuk ikon kamera untuk ubah foto</p>
            </div>
            <!-- Form Input -->
            <form action="auth/proses_edit_akun.php" method="POST" enctype="multipart/form-data">
                <!-- LOGIKA ERROR START -->
                <?php if(isset($_GET['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-2">
                    <?php 
                        if($_GET['error'] == 'update_failed') echo "Update data gagal, silahkan coba lagi.";
                        if($_GET['error'] == 'phone_invalid') echo "Format nomor telepon salah, silahkan coba lagi.";
                        if($_GET['error'] == 'upload_failed') echo "Update avatar gagal, silahkan coba lagi.";
                    ?>
                    </div>
                <?php endif; ?>
                <!-- LOGIKA ERROR END -->

                <!-- LOGIKA SUKSES START -->
                <?php if(isset($_GET['success'])): ?>
                    <div class='bg-green-500 border-l-4 border-green-500 text-white p-3 rounded-lg text-sm mb-2'>
                    <?php 
                        if($_GET['success'] == 'update_success') echo "Update data berhasil!";
                        if($_GET['success'] == 'avatar_updated') echo "Update avatar berhasil!";
                    ?>
                    </div>
                <?php endif; ?>
                <!-- LOGIKA SUKSES END -->

                <div class="space-y-5">
                    <div>
                        <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block ml-1">Nama Lengkap</label>
                        <input type="text" name="new_name" value="<?php echo $user['nama'] ?>" class="w-full bg-white border border-gray-100 rounded-[16px] px-4 py-3 text-[0.9rem] transition-all">
                    </div>
        
                    <div>
                        <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block ml-1">Alamat Email</label>
                        <input type="email" name="new_email" value="<?php echo $user['email'] ?>" class="w-full bg-white border border-gray-100 rounded-[16px] px-4 py-3 text-[0.9rem] transition-all">
                    </div>
        
                    <div>
                        <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block ml-1">Nomor WhatsApp</label>
                        <div class="flex">
                            <span class="bg-gray-100 border border-gray-100 rounded-l-[16px] px-3 py-3 text-[0.9rem] flex items-center text-gray-500">+62</span>
                            <input type="number" value="<?php echo $user['no_telp'] ?>" name="new_phone_number" placeholder="81234567890" class="w-full bg-white border border-gray-100 rounded-r-[16px] px-4 py-3 text-[0.9rem] transition-all">
                        </div>
                    </div>
        
                    <div>
                        <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block ml-1">Bio Singkat</label>
                        <textarea rows="3" name="new_bio" class="w-full bg-white border border-gray-100 rounded-[16px] px-4 py-3 text-[0.9rem] transition-all" placeholder="Masukkan bio mu disini"><?php echo $user['bio'] ?></textarea>
                    </div>
                </div>
        
                <div class="mt-10">
                    <button type="submit" name="update" class="w-full bg-gradient-to-r from-[#00aa13] to-[#006600] text-white font-bold py-4 rounded-[16px] shadow-lg shadow-green-200 active:scale-[0.98] transition-transform cursor-pointer">
                        Simpan Perubahan
                    </button>
                    <p class="text-center text-[0.8rem] text-gray-400 mt-4">Data kamu aman dan terlindungi.</p>
                </div>
            </form>
        </main>

    </body>
</html>