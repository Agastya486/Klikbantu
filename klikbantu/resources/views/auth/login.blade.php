<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KlikBantu - Masuk</title>
  <link href="./src/output.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;}
    body{font-family:'Plus Jakarta Sans',sans-serif;margin:0;background:#f3f4f6;min-height:100vh;display:flex;align-items:center;justify-content:center;}
    .login-wrap{display:grid;grid-template-columns:1fr 440px;min-height:100vh;width:100%;max-width:1100px;margin:0 auto;}
    .login-hero{background:linear-gradient(135deg,#00aa13 0%,#005500 100%);display:flex;flex-direction:column;justify-content:center;padding:60px 56px;color:white;position:relative;overflow:hidden;}
    .login-hero::before{content:'';position:absolute;right:-80px;top:-80px;width:360px;height:360px;background:rgba(255,255,255,.06);border-radius:50%;}
    .login-hero::after{content:'';position:absolute;left:-40px;bottom:-60px;width:240px;height:240px;background:rgba(255,255,255,.04);border-radius:50%;}
    .hero-logo{height:48px;width:auto;object-fit:contain;margin-bottom:32px;position:relative;z-index:1;}
    .hero-title{font-size:2.2rem;font-weight:800;line-height:1.2;margin:0 0 16px;position:relative;z-index:1;}
    .hero-desc{font-size:.95rem;opacity:.85;line-height:1.7;position:relative;z-index:1;}
    .hero-stats{display:flex;gap:24px;margin-top:36px;position:relative;z-index:1;flex-wrap:wrap;}
    .hs-item{background:rgba(255,255,255,.12);padding:14px 20px;border-radius:14px;backdrop-filter:blur(8px);}
    .hs-val{font-size:1.3rem;font-weight:800;}
    .hs-lbl{font-size:.72rem;opacity:.8;margin-top:2px;}
    .login-form-wrap{background:white;display:flex;align-items:center;justify-content:center;padding:40px 48px;}
    .form-box{width:100%;max-width:360px;}
    .form-box h2{font-size:1.6rem;font-weight:800;color:#111827;margin:0 0 8px;}
    .form-box .sub{font-size:.88rem;color:#9ca3af;margin-bottom:32px;}
    .f-label{display:block;font-weight:700;font-size:.83rem;color:#374151;margin-bottom:8px;}
    .f-input{width:100%;padding:13px 16px;border:1.5px solid #e5e7eb;border-radius:12px;font-size:.9rem;font-family:inherit;outline:none;transition:all .2s;background:#fafafa;}
    .f-input:focus{border-color:#00aa13;box-shadow:0 0 0 4px rgba(0,170,19,.1);background:white;}
    .submit-btn{width:100%;background:#00aa13;color:white;padding:14px;font-size:1rem;font-weight:800;border:none;border-radius:12px;cursor:pointer;font-family:inherit;transition:all .2s;margin-top:8px;}
    .submit-btn:hover{background:#009611;transform:translateY(-1px);box-shadow:0 6px 20px rgba(0,170,19,.3);}
    @media(max-width:768px){
      body{background:white;}
      .login-wrap{grid-template-columns:1fr;min-height:auto;}
      .login-hero{padding:40px 28px;min-height:auto;}
      .hero-title{font-size:1.5rem;}
      .login-form-wrap{padding:32px 24px;}
    }
  </style>
</head>
<body>
<div class="login-wrap">
  <!-- Hero Left -->
  <div class="login-hero">
    <img src="{{ asset('images/logo.png') }}" alt="KlikBantu" class="hero-logo" onerror="this.style.display='none'">
    <h1 class="hero-title">Bersama Kita<br>Bisa Memberi 🤲</h1>
    <p class="hero-desc">Platform donasi online terpercaya untuk membantu sesama. Bergabung dengan ribuan donatur yang telah memberi manfaat nyata.</p>
    <div class="hero-stats">
      <div class="hs-item"><div class="hs-val">1000+</div><div class="hs-lbl">Donatur Aktif</div></div>
      <div class="hs-item"><div class="hs-val">50+</div><div class="hs-lbl">Campaign Berhasil</div></div>
    </div>
  </div>

  <!-- Form Right -->
  <div class="login-form-wrap">
    <div class="form-box">
      <h2>Selamat Datang 👋</h2>
      <p class="sub">Masuk untuk mulai berbagi kebaikan.</p>

      <?php if(isset($_GET['error'])): ?>
      <div style="background:#fef2f2;border-left:4px solid #ef4444;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:.83rem;margin-bottom:20px;">
        <?php
          if($_GET['error']=='not_found') echo "Email belum terdaftar.";
          if($_GET['error']=='wrong_pass_or_email') echo "Email atau password salah.";
        ?>
      </div>
      <?php endif; ?>

      <?php if(isset($_GET['success'])): ?>
      <div style="background:#ecfdf5;border-left:4px solid #10b981;color:#065f46;padding:12px 16px;border-radius:10px;font-size:.83rem;margin-bottom:20px;">
        <?php if($_GET['success']=='registration_success') echo "Registrasi berhasil! Silakan login."; ?>
      </div>
      <?php endif; ?>

      <form action="/login" method="POST" style="display:flex;flex-direction:column;gap:18px;">
        @csrf
        <x-alert />
        <div>
          <label class="f-label" for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="nama@email.com" required class="f-input">
        </div>
        <div>
          <label class="f-label" for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password" required class="f-input">
        </div>
        <button type="submit" name="login" class="submit-btn">Masuk Sekarang →</button>
      </form>

      <div style="margin-top:28px;padding-top:24px;border-top:1px solid #f3f4f6;text-align:center;font-size:.88rem;color:#6b7280;">
        Belum punya akun?
        <a href="/register" style="color:#00aa13;font-weight:700;text-decoration:none;"> Daftar di sini</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>