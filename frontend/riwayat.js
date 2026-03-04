let editingId = null;

const tbody = document.getElementById('donasi-list');
const emptyMsg = document.getElementById('empty-msg');

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

// Init
renderTable();