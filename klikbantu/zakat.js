function fmt(n) {
  return Math.round(n).toLocaleString('id-ID');
}

// ---- Tab Switching ----
function switchTab(tab) {
  ['hitung', 'bayar', 'info'].forEach(t => {
    document.getElementById('content-' + t).classList.add('hidden');
    const btn = document.getElementById('tab-' + t);
    btn.classList.remove('tab-active');
    btn.classList.add('tab-inactive');
  });
  document.getElementById('content-' + tab).classList.remove('hidden');
  const active = document.getElementById('tab-' + tab);
  active.classList.add('tab-active');
  active.classList.remove('tab-inactive');
}

// ---- Pilih Jenis Zakat (Tab Hitung) ----
function selectZakat(el, type) {
  document.querySelectorAll('.zakat-type-card').forEach(c => c.classList.remove('zakat-card-active'));
  el.classList.add('zakat-card-active');
  document.querySelectorAll('.calc-section').forEach(s => s.classList.add('hidden'));
  document.getElementById('calc-' + type).classList.remove('hidden');
}

// ---- Kalkulator Fitrah ----
let jiwa = 1;

function changeJiwa(d) {
  jiwa = Math.max(1, jiwa + d);
  document.getElementById('jumlah-jiwa').textContent = jiwa;
  hitungFitrah();
}

function hitungFitrah() {
  const h = parseFloat(document.getElementById('harga-beras').value) || 15000;
  document.getElementById('result-fitrah').textContent = 'Rp ' + fmt(jiwa * 2.5 * h);
}

// ---- Kalkulator Maal ----
function hitungMaal() {
  const harta = parseFloat(document.getElementById('total-harta').value) || 0;
  const hEmas = parseFloat(document.getElementById('harga-emas-maal').value) || 1050000;
  const nisab = 85 * hEmas;
  const rb = document.getElementById('result-maal-box');
  const tw = document.getElementById('maal-tidak-wajib');
  if (!harta) { rb.classList.add('hidden'); tw.classList.add('hidden'); return; }
  if (harta >= nisab) {
    document.getElementById('result-maal').textContent = 'Rp ' + fmt(harta * 0.025);
    rb.classList.remove('hidden'); tw.classList.add('hidden');
  } else {
    document.getElementById('maal-nisab-info').textContent =
      'Kekurangan Rp ' + fmt(nisab - harta) + ' dari nisab (Rp ' + fmt(nisab) + ')';
    tw.classList.remove('hidden'); rb.classList.add('hidden');
  }
}

// ---- Kalkulator Penghasilan ----
function hitungPenghasilan() {
  const p = parseFloat(document.getElementById('penghasilan').value) || 0;
  const rb = document.getElementById('result-penghasilan-box');
  const tw = document.getElementById('penghasilan-tidak-wajib');
  if (!p) { rb.classList.add('hidden'); tw.classList.add('hidden'); return; }
  if (p >= 7800000) {
    document.getElementById('result-penghasilan').textContent = 'Rp ' + fmt(p * 0.025);
    rb.classList.remove('hidden'); tw.classList.add('hidden');
  } else {
    tw.classList.remove('hidden'); rb.classList.add('hidden');
  }
}

// ---- Kalkulator Tabungan ----
function hitungTabungan() {
  const s = parseFloat(document.getElementById('saldo-tabungan').value) || 0;
  const rb = document.getElementById('result-tabungan-box');
  const tw = document.getElementById('tabungan-tidak-wajib');
  if (!s) { rb.classList.add('hidden'); tw.classList.add('hidden'); return; }
  if (s >= 89250000) {
    document.getElementById('result-tabungan').textContent = 'Rp ' + fmt(s * 0.025);
    rb.classList.remove('hidden'); tw.classList.add('hidden');
  } else {
    tw.classList.remove('hidden'); rb.classList.add('hidden');
  }
}

// ---- Kalkulator Emas ----
function hitungEmas() {
  const b = parseFloat(document.getElementById('berat-emas').value) || 0;
  const h = parseFloat(document.getElementById('harga-emas').value) || 1050000;
  const rb = document.getElementById('result-emas-box');
  const tw = document.getElementById('emas-tidak-wajib');
  if (!b) { rb.classList.add('hidden'); tw.classList.add('hidden'); return; }
  if (b >= 85) {
    document.getElementById('result-emas').textContent = 'Rp ' + fmt(b * h * 0.025);
    rb.classList.remove('hidden'); tw.classList.add('hidden');
  } else {
    tw.classList.remove('hidden'); rb.classList.add('hidden');
  }
}

// ---- Kalkulator Perusahaan ----
function hitungPerusahaan() {
  const a = parseFloat(document.getElementById('aset-lancar').value) || 0;
  const k = parseFloat(document.getElementById('kewajiban').value) || 0;
  const base = Math.max(0, a - k);
  const rb = document.getElementById('result-perusahaan-box');
  if (base > 0) {
    document.getElementById('result-perusahaan').textContent = 'Rp ' + fmt(base * 0.025);
    rb.classList.remove('hidden');
  } else {
    rb.classList.add('hidden');
  }
}

// ---- Nominal Cepat (Tab Bayar) ----
function setNominal(v) {
  document.getElementById('jumlah-zakat-bayar').value = v;
}

// ---- Tombol "Bayar →" dari Kalkulator ----
// Pindah ke tab Bayar dan isi otomatis jenis + jumlah
function bukaBayarDariKalkulator(jenis, jumlahText) {
  // Ambil angka dari teks "Rp 37.500"
  const angka = jumlahText.replace(/[^0-9]/g, '');
  switchTab('bayar');

  // Set jenis zakat di select
  const selectJenis = document.getElementById('jenis-zakat-bayar');
  for (let i = 0; i < selectJenis.options.length; i++) {
    if (selectJenis.options[i].value === jenis) {
      selectJenis.selectedIndex = i;
      break;
    }
  }

  // Set jumlah
  document.getElementById('jumlah-zakat-bayar').value = angka;

  // Scroll ke form
  document.getElementById('content-bayar').scrollIntoView({ behavior: 'smooth' });
}

async function konfirmasiZakat() {
  const pending = window._pendingZakat;
  if (!pending) return;


  const formData = new FormData();
  formData.append('nama_muzakki', pending.nama);
  formData.append('jenis_zakat', pending.jenis);
  formData.append('jumlah_zakat', pending.jumlah);
  formData.append('is_anonim', pending.is_anonim);

  try {
    const response = await fetch('auth/proses_bayar_zakat.php', {
      method: 'POST',
      body: formData
    });
   
    // Pastikan PHP lu balikin json_encode(['token' => $snapToken]);
    const result = await response.json();


    if (result.token) {
      window.snap.pay(result.token, {
        onSuccess: function(result) {
          document.getElementById('toast-sukses').classList.remove('hidden');
          setTimeout(() => location.reload(), 3000);
        },
        onError: function(result) {
          alert("Pembayaran Gagal!");
          console.log(result);
        }
      });
    }
  } catch (err) {
    console.error("Error:", err);
  }
}

// ---- Proses Pembayaran dari Tab Bayar ----
function prosesZakat() {
  const nama = document.getElementById('nama-muzakki').value.trim();
  const jenis = document.getElementById('jenis-zakat-bayar').value;
  const jumlah = document.getElementById('jumlah-zakat-bayar').value;

  if (!nama || !jenis || !jumlah || Number(jumlah) <= 0) {
    alert('Mohon lengkapi semua data terlebih dahulu.');
    return;
  }

  // Simpan data ke variabel global sementara untuk konfirmasi
  window._pendingZakat = {
    nama: nama,
    jenis: jenis,
    jumlah: jumlah
  };
  // Langsung panggil konfirmasiZakat atau tampilin modal dulu
  // Karena lu mau langsung, kita panggil konfirmasiZakat()
  konfirmasiZakat();
}

// ---- Logic Anonim ----
const checkboxAnonim = document.getElementById('checkbox-anonim-zakat');
const msgAnonim = document.getElementById('msg-anonim-zakat');
const namaInput = document.getElementById('nama-muzakki');
let namaAsli = '';

if (checkboxAnonim) {
  checkboxAnonim.addEventListener('change', function () {
    if (this.checked) {
      namaAsli = namaInput.value;
      namaInput.value = 'Hamba Allah';
      namaInput.readOnly = true;
      namaInput.classList.add('bg-gray-50', 'text-gray-500');
      msgAnonim.classList.remove('hidden');
    } else {
      namaInput.value = namaAsli;
      namaInput.readOnly = false;
      namaInput.classList.remove('bg-gray-50', 'text-gray-500');
      msgAnonim.classList.add('hidden');
    }
  });
}

// ---- Init ----
hitungFitrah();