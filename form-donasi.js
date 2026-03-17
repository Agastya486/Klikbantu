const form = document.querySelector('form'); // Diubah agar sesuai dengan tag <form>
const btnSubmit = document.getElementById('btn-submit');
const namaInput = document.getElementById('nama-donatur');
const jumlahInput = document.getElementById('jumlah-donasi');
const metodeSelect = document.getElementById('metode-bayar');

// Element baru untuk fitur anonim
const checkboxAnonim = document.getElementById('checkbox-anonim');
const msgAnonim = document.getElementById('msg-anonim');

let namaAsli = ""; // Untuk menyimpan nama sementara

// --- LOGIC ANONIM ---
checkboxAnonim.addEventListener('change', function() {
  if (this.checked) {
    namaAsli = namaInput.value;
    namaInput.value = "Hamba Tuhan";
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

// --- CORE DATA LOGIC ---
function getDonasiData() {
  return JSON.parse(localStorage.getItem('donasiList') || '[]');
}

function saveDonasiData(data) {
  localStorage.setItem('donasiList', JSON.stringify(data));
}

// Submit donasi baru
btnSubmit.addEventListener('click', () => {
  const nama = namaInput.value.trim();
  const jumlah = jumlahInput.value.trim();
  const metodeText = metodeSelect.options[metodeSelect.selectedIndex].text;
  
  // Logic Email: Jika anonim dicentang, email jadi anonymous
  const emailUser = checkboxAnonim.checked ? "Anonymous" : "user@email.com"; // Sesuaikan jika ada input email

  if (!nama || !jumlah || Number(jumlah) <= 0 || metodeSelect.value === '') {
    alert('Mohon isi semua data dengan benar!');
    return;
  }

  const sekarang = new Date();
  const tanggal = sekarang.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });

  const newEntry = {
    id: Date.now(),
    nama,
    email: emailUser, // Tambahan field email
    tipe: "donasi",   // Biar sinkron dengan tabel riwayat admin
    program: "Donasi Umum", // Default program
    jumlah: Number(jumlah),
    metode: metodeText,
    tanggal,
    status: "Berhasil"
  };

  const data = getDonasiData();
  data.unshift(newEntry);
  saveDonasiData(data);

  alert(`Terima kasih ${nama}! Donasi berhasil dicatat.`);
  
  // Reset Form & State Anonim
  form.reset();
  if (checkboxAnonim.checked) {
    checkboxAnonim.checked = false;
    namaInput.readOnly = false;
    namaInput.classList.remove('bg-gray-50', 'text-gray-500');
    msgAnonim.classList.add('hidden');
  }
});