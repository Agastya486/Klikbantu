<?php
    session_start();
    include './includes/koneksi.php';

    // Cek apakah user sudah login
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $query = $conn->query("SELECT * FROM USERS WHERE id='$user_id'");
    $user = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KlikBantu - Profil Akun</title>
        <link href="./src/output.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="bg-[#f8f9fa] text-[#333] pb-24">

        <!-- Header -->
        <header class="bg-gradient-to-br from-[#00aa13] to-[#006600] text-white pt-[60px] pb-[40px] px-5 rounded-b-[32px] shadow-[0_4px_15px_rgba(0,102,0,0.2)]">
            <div class="max-w-[500px] mx-auto flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full border-4 border-white/30 overflow-hidden mb-4 shadow-lg">
                    <img src="<?php echo !empty($user['avatar']) ? 'assets/img/avatars/'.$user['avatar'] : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80' ?>" alt="Profile" class="w-full h-full object-cover">
                </div>
                <h1 class="text-[1.4rem] font-bold"><?php echo $user['nama'] ?></h1>
                <p class="text-[0.85rem] opacity-80 mb-4"><?php echo $user['email'] ?></p>
            </div>
        </header>

        <!-- Main -->
        <main class="max-w-[500px] mx-auto px-5 mt-8">
            <h2 class="text-[1rem] font-bold text-gray-400 mb-4 px-2 uppercase tracking-widest text-[0.7rem]">Pengaturan Akun</h2>
            <div class="bg-white rounded-[24px] overflow-hidden shadow-sm mb-6">
                <div class="flex items-center p-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer">
                    <span class="w-10 h-10 flex items-center justify-center bg-[#f0fff0] rounded-xl mr-4">👤</span>
                    <div class="flex-1">
                        <a href="editProfile.php" class="text-[0.9rem] font-semibold">Edit Profil</a>
                    </div>
                    <span class="text-gray-300">→</span>
                </div>
                <div class="flex items-center p-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer">
                    <span class="w-10 h-10 flex items-center justify-center bg-[#f0fff0] rounded-xl mr-4">🔒</span>
                    <div class="flex-1">
                        <a href="keamanan.php" class="text-[0.9rem] font-semibold">Keamanan & Password</a>
                    </div>
                    <span class="text-gray-300">→</span>
                </div>
            </div>

            <button onclick="location.href='auth/logout.php'" class="cursor-pointer w-full py-4 bg-red-50 text-red-500 font-bold rounded-[20px] transition-colors hover:bg-red-100">
                Keluar dari Akun
            </button>
        </main>

        <!-- Bottom nav -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[500px] bg-white flex justify-around py-3 border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
            <a href="index.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors">
                <span class="text-[1.4rem] block mb-0.5">🏠</span>Beranda
            </a>
            <a href="donasi.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors">
                <span class="text-[1.4rem] block mb-0.5">💰</span>Donasi
            </a>
            <a href="riwayat.php" class="flex-1 text-center text-[0.7rem] text-gray-400 hover:text-[#00aa13] transition-colors">
                <span class="text-[1.4rem] block mb-0.5">📜</span>Riwayat
            </a>
            <a href="#" class="flex-1 text-center text-[0.7rem] text-[#00aa13] font-bold">
                <span class="text-[1.4rem] block mb-0.5">👤</span>Akun
            </a>
        </nav>
    </body>
</html>