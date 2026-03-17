// ============================================================
// RIWAYAT.JS — KlikBantu
// Menampilkan riwayat donasi + zakat dari localStorage
// ============================================================

let filterAktif = 'semua';

function getDonasiData() {
  return JSON.parse(localStorage.getItem('donasiList') || '[]');
}

function fmt(n) {
  return 'Rp ' + Number(n).toLocaleString('id-ID');
}

// Render ringkasan total di kartu atas
function renderSummary(data) {
  const totalDonasi = data
    .filter(d => d.tipe === 'donasi')
    .reduce((sum, d) => sum + Number(d.jumlah), 0);
  const totalZakat = data
    .filter(d => d.tipe === 'zakat')
    .reduce((sum, d) => sum + Number(d.jumlah), 0);

  document.getElementById('total-donasi').textContent = fmt(totalDonasi);
  document.getElementById('total-zakat').textContent = fmt(totalZakat);
}

// Buat satu kartu transaksi
function buatKartu(item) {
  const isZakat = item.tipe === 'zakat';
  const badgeClass = isZakat
    ? 'bg-amber-50 text-amber-600'
    : 'bg-blue-50 text-blue-600';
  const badgeLabel = isZakat ? '🌙 Zakat' : '💰 Donasi';
  const icon = isZakat ? '🌙' : '💰';

  return `
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50 flex items-center gap-3">
      <div class="w-11 h-11 rounded-full bg-[#f0fff0] flex items-center justify-center text-xl flex-shrink-0">
        ${icon}
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-0.5">
          <p class="font-bold text-[0.9rem] truncate">${item.nama}</p>
          <span class="text-[0.62rem] font-bold px-2 py-0.5 rounded-full flex-shrink-0 ${badgeClass}">${badgeLabel}</span>
        </div>
        <p class="text-[0.75rem] text-gray-400 truncate">${item.program || 'Donasi Umum'}</p>
        <p class="text-[0.7rem] text-gray-300 mt-0.5">${item.metode} · ${item.tanggal}</p>
      </div>
      <div class="text-right flex-shrink-0">
        <p class="font-bold text-[#006600] text-[0.9rem]">${fmt(item.jumlah)}</p>
        <p class="text-[0.65rem] text-green-500 font-semibold mt-0.5">✓ ${item.status || 'Berhasil'}</p>
      </div>
    </div>
  `;
}

// Render daftar transaksi sesuai filter aktif
function renderTable() {
  const allData = getDonasiData();
  renderSummary(allData);

  const filtered = filterAktif === 'semua'
    ? allData
    : allData.filter(d => d.tipe === filterAktif);

  const container = document.getElementById('riwayat-list');
  const emptyMsg = document.getElementById('empty-msg');

  if (filtered.length === 0) {
    container.innerHTML = '';
    emptyMsg.classList.remove('hidden');
    return;
  }

  emptyMsg.classList.add('hidden');
  container.innerHTML = filtered.map(buatKartu).join('');
}

// Filter button handler
function filterRiwayat(tipe, btn) {
  filterAktif = tipe;

  // Update active state button
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  renderTable();
}

// Init
renderTable();
