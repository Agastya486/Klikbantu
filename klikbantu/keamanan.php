<?php
    session_start();
    include 'includes/koneksi.php';

    $id = $_SESSION['user_id'];
    $query = $conn->query("SELECT * FROM users WHERE id='$id'");
    $user = $query->fetch_assoc();

    $updatedAt = $user['updated_at'];
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8"> <!-- Agar bisa membaca emoji dan panah back -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KlikBantu - Keamanan</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="./src/output.css" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
            input:focus { outline: none; border-color: #00aa13; }
        </style>
    </head>
    <body class="bg-[#f8f9fa] text-[#333]">
        <!-- Header -->
        <header class="max-w-[500px] mx-auto bg-white px-5 py-4 flex items-center sticky top-0 z-10 shadow-sm">
            <a href="akun.php" class="text-[1.2rem] mr-4 text-gray-600">←</a>
            <h1 class="text-[1.1rem] font-bold">Keamanan & Password</h1>
        </header>

        <!-- Main -->
        <main class="max-w-[500px] mx-auto px-5 pt-6 pb-10">
            <div class="bg-white rounded-[24px] p-5 mb-6 shadow-sm border border-green-50">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-[#f0fff0] rounded-full flex items-center justify-center mr-3">
                        <span class="text-[1.2rem]">🛡️</span>
                    </div>
                    <div>
                        <p class="text-[0.9rem] font-bold">Status password</p>
                    </div>
                </div>
                <p class="text-[0.75rem] text-gray-400">Password terakhir diganti pada <?php echo date('d F Y', strtotime($user['updated_at'])); ?></p>
            </div>

            <!-- Form input -->
            <h2 class="text-[1rem] font-bold text-gray-400 mb-4 px-2 uppercase tracking-widest text-[0.7rem]">Ganti Password</h2>
            <form action="auth/proses_keamanan.php" method="POST" class="bg-white rounded-[24px] p-5 shadow-sm space-y-5 mb-8">
                <!-- LOGIKA ERROR START -->
                <?php if(isset($_GET['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-2">
                        <?php 
                            if($_GET['error'] == 'something_wrong') echo "Update data gagal, silahkan coba lagi.";
                            if($_GET['error'] == 'password_wrong') echo "Password salah, silahkan coba lagi.";
                            if($_GET['error'] == 'password_length_wrong') echo "Panjang password tidak sesuai.";
                            if($_GET['error'] == 'password_mismatch') echo "Password baru tidak serasi, pastikan keduanya sama.";
                        ?>
                    </div>
                <?php endif; ?>
                <!-- LOGIKA ERROR END -->

                <!-- LOGIKA SUKSES START -->
                <?php if(isset($_GET['success'])): ?>
                    <div class='bg-green-500 border-l-4 border-green-500 text-white p-3 rounded-lg text-sm mb-2'>
                        <?php 
                            if($_GET['success'] == 'change_password_success') echo "Update password berhasil!";
                        ?>
                    </div>
                <?php endif; ?>
                <!-- LOGIKA SUKSES END -->
                <div>
                    <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Password Saat Ini</label>
                    <input type="password" name="current_password" placeholder="••••••••" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-[16px] px-4 py-3 text-[0.9rem]" required>
                </div>

                <div>
                    <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Password Baru</label>
                    <input type="password" name="new_password" placeholder="Minimal 8 karakter" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-[16px] px-4 py-3 text-[0.9rem]" required>
                </div>

                <div>
                    <label class="text-[0.8rem] font-semibold text-gray-500 mb-1 block">Ulangi Password Baru</label>
                    <input type="password" name="confirm_new_password" placeholder="Konfirmasi password baru" class="w-full bg-[#f8f9fa] border border-gray-100 rounded-[16px] px-4 py-3 text-[0.9rem]" required>
                </div>

                <button type="submit" name="ubah_password" class="cursor-pointer w-full bg-[#00aa13] text-white font-bold py-3 rounded-[16px] mt-2 transition-transform active:scale-95">
                    Update Password
                </button>
            </form>
        </main>
    </body>
</html>