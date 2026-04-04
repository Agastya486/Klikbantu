<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Daftar Akun Baru</title>
    <link href="./src/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
            body { font-family: 'Poppins', sans-serif; }
    </style>
  </head>
  
  <body class="bg-[#f5f5f5] text-[#333] leading-relaxed">
    <div class="max-w-[450px] mx-auto my-12 px-5">
      <!-- Header -->
      <header class="text-center mb-8">
        <img src="public/logo.png">
        <p class="text-[#555] text-lg">Gabung untuk menebar kebaikan.</p>
      </header>

      <div class="bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] py-10 px-8">
        <h2 class="text-[#006600] text-center mb-8 text-2xl font-semibold">Buat Akun Baru</h2>
        
        <!-- Form -->
        <form class="flex flex-col gap-5" action="auth/proses_registrasi.php" method="POST">
          <!-- LOGIKA ERROR START -->
          <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-2">
              <?php 
                if($_GET['error'] == 'used_pass_or_email') echo "Email atau password sudah digunakan.";
                if($_GET['error'] == 'something_wrong') echo "Terjadi error, mohon coba lagi.";
                if($_GET['error'] == 'password_mismatch') echo "Password tidak sama, mohon coba lagi.";
                if($_GET['error'] == 'empty_fields') echo "Semua field harus diisi.";
                if($_GET['error'] == 'short_password') echo "Password harus minimal 8 karakter.";
              ?>
            </div>
          <?php endif; ?>
          <!-- LOGIKA ERROR END -->
           
          <div class="flex flex-col gap-2">
            <label for="fullname" class="font-semibold text-[#444] text-base">Nama Lengkap</label>
            <input type="text" id="username" name="username" placeholder="Masukkan nama lengkap" required 
                class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
          </div>

          <div class="flex flex-col gap-2">
            <label for="email" class="font-semibold text-[#444] text-base">Email</label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required 
                class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
          </div>

          <div class="flex flex-col gap-2">
            <label for="password" class="font-semibold text-[#444] text-base">Password</label>
            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required 
              class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
          </div>

          <div class="flex flex-col gap-2">
            <label for="confirm-password" class="font-semibold text-[#444] text-base">Konfirmasi Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Ulangi password" required 
              class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
          </div>

          <button type="submit" name="register" class="w-full bg-[#00aa13] text-white p-4 text-lg font-bold rounded-xl cursor-pointer hover:bg-[#00960f] active:translate-y-0.5 transition-all mt-4">
            Daftar Sekarang
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
          <p class="text-[#555]">Sudah punya akun? 
            <a href="login.php  " class="text-[#00aa13] font-bold hover:underline">Masuk di sini</a>
          </p>
        </div>
      </div>
    </div>
  </body>
</html>