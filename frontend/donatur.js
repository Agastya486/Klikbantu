const form = document.querySelector('.form-donasi');
const btnSubmit = document.getElementById('btn-submit');
const namaInput = document.getElementById('nama-donatur');
const jumlahInput = document.getElementById('jumlah-donasi');
const metodeSelect = document.getElementById('metode-bayar');

// Ambil data dari localStorage
function getDonasiData() {
  return JSON.parse(localStorage.getItem('donasiList') || '[]');
}

// Simpan data ke localStorage
function saveDonasiData(data) {
  localStorage.setItem('donasiList', JSON.stringify(data));
}

// Submit donasi baru
btnSubmit.addEventListener('click', () => {
  const nama = namaInput.value.trim();
  const jumlah = jumlahInput.value.trim();
  const metodeText = metodeSelect.options[metodeSelect.selectedIndex].text;

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
    jumlah: Number(jumlah),
    metode: metodeText,
    tanggal
  };

  const data = getDonasiData();
  data.unshift(newEntry); // tambah di awal
  saveDonasiData(data);

  alert(`Terima kasih ${nama}! Donasi berhasil dicatat.`);
  form.reset();
});
