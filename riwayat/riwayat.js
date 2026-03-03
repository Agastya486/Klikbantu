let editingId = null;

const tbody = document.getElementById('donasi-list');
const emptyMsg = document.getElementById('empty-msg');
const editCard = document.getElementById('edit-card');
const namaInput = document.getElementById('nama-donatur');
const jumlahInput = document.getElementById('jumlah-donasi');
const metodeSelect = document.getElementById('metode-bayar');
const btnSave = document.getElementById('btn-save');
const btnCancel = document.getElementById('btn-cancel');

// Ambil data dari localStorage
function getDonasiData() {
  return JSON.parse(localStorage.getItem('donasiList') || '[]');
}

// Simpan data ke localStorage
function saveDonasiData(data) {
  localStorage.setItem('donasiList', JSON.stringify(data));
}

// Render tabel
function renderTable() {
  const data = getDonasiData();
  tbody.innerHTML = '';

  if (data.length === 0) {
    emptyMsg.style.display = 'block';
    return;
  }

  emptyMsg.style.display = 'none';

  data.forEach(item => {
    const formattedJumlah = 'Rp ' + Number(item.jumlah).toLocaleString('id-ID');
    const row = document.createElement('tr');
    row.dataset.id = item.id;
    row.innerHTML = `
      <td>${item.nama}</td>
      <td>${formattedJumlah}</td>
      <td>${item.metode}</td>
      <td>${item.tanggal}</td>
    `;
    tbody.appendChild(row);
  });
}

// Simpan perubahan edit
btnSave.addEventListener('click', () => {
  const nama = namaInput.value.trim();
  const jumlah = jumlahInput.value.trim();
  const metodeText = metodeSelect.options[metodeSelect.selectedIndex].text;

  if (!nama || !jumlah || Number(jumlah) <= 0 || metodeSelect.value === '') {
    alert('Mohon isi semua data dengan benar!');
    return;
  }

  const sekarang = new Date();
  const tanggal = sekarang.toLocaleDateString('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric'
  });

  const data = getDonasiData();
  const idx = data.findIndex(d => d.id == editingId);
  if (idx !== -1) {
    data[idx] = { id: data[idx].id, nama, jumlah: Number(jumlah), metode: metodeText, tanggal };
    saveDonasiData(data);
  }

  alert('Data donasi berhasil diupdate!');
  cancelEdit();
  renderTable();
});

// Batal edit
btnCancel.addEventListener('click', cancelEdit);

function cancelEdit() {
  editingId = null;
  editCard.style.display = 'none';
  namaInput.value = '';
  jumlahInput.value = '';
  metodeSelect.value = '';
}

// Init
renderTable();
