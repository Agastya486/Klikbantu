<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBantu - Masuk</title>
    <link href="./src/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Poppins', sans-serif; }
    </style>
  </head>

  <body class="bg-[#f5f5f5] text-[#333] leading-relaxed">
    <div class="max-w-[450px] mx-auto my-16 px-5">
      <!-- Header -->
      <header class="text-center mb-10">
        <img src="public/logo.png">
        <p class="text-[#555] text-lg">Masuk untuk mulai berbagi kebaikan.</p>
      </header>

      <!-- Main -->
      <div class="bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] py-10 px-8">
        <h2 class="text-[#006600] text-center mb-8 text-2xl font-semibold">Selamat Datang Kembali</h2>
        
        <!-- Form login -->
        <form class="flex flex-col gap-6" action="auth/proses_login.php" method="POST">
          <!-- LOGIKA ERROR START -->
          <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-2">
              <?php 
                if($_GET['error'] == 'not_found') echo "Email belum terdaftar.";
                if($_GET['error'] == 'wrong_pass_or_email') echo "Email atau password salah.";
              ?>
            </div>
          <?php endif; ?>
          <!-- LOGIKA ERROR END -->

          <!-- LOGIKA SUKSES START -->
          <?php if(isset($_GET['success'])): ?>
            <div class='bg-green-500 border-l-4 border-green-500 text-white p-3 rounded-lg text-sm mb-2'>
              <?php 
                if($_GET['success'] == 'registration_success') echo "Registrasi berhasil! Silakan login.";
              ?>
            </div>
          <?php endif; ?>
          <!-- LOGIKA SUKSES END -->

          <!-- Email input -->
          <div class="flex flex-col gap-2">
            <label for="email" class="font-semibold text-[#444] text-base">Email</label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required 
                class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
          </div>

          <!-- Password input -->
          <div class="flex flex-col gap-2">
            <div class="flex justify-between items-center">
              <label for="password" class="font-semibold text-[#444] text-base">Password</label>
            </div>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required 
              class="p-3.5 border border-[#d1d5db] rounded-xl text-base transition-all focus:outline-none focus:border-[#00aa13] focus:ring-4 focus:ring-[#00aa13]/15" />
          </div>

          <!-- Login button -->
          <button type="submit" name="login" class="w-full bg-[#00aa13] text-white p-4 text-lg font-bold rounded-xl cursor-pointer hover:bg-[#00960f] active:translate-y-0.5 transition-all mt-2">
            Masuk Sekarang
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
          <p class="text-[#555]">Belum punya akun? 
            <a href="registrasi.php" class="text-[#00aa13] font-bold hover:underline">Daftar di sini</a>
          </p>
        </div>
      </div>
    </div>
  </body>
</html>