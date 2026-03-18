// ============================================================
// ADMIN-RIWAYAT.JS — KlikBantu
// Menampilkan semua transaksi donasi + zakat dari localStorage
// ditambahkan ke tabel yang sudah ada (data statis + dinamis)
// ============================================================

function fmt(n) {
  return 'Rp ' + Number(n).toLocaleString('id-ID');
}

function getDonasiData() {
  return JSON.parse(localStorage.getItem('donasiList') || '[]');
}

// Render baris dari localStorage ke tabel
function renderDynamicRows() {
  const data = getDonasiData();
  const tbody = document.getElementById('transactionTable');

  data.forEach(item => {
    const isZakat = item.tipe === 'zakat';
    const badgeClass = isZakat
      ? 'bg-amber-50 text-amber-600'
      : 'bg-blue-50 text-blue-600';
    const badgeLabel = isZakat ? 'Zakat' : 'Donasi';

    const tr = document.createElement('tr');
    tr.className = 'transaction-row border-t border-gray-50 hover:bg-gray-50/50 transition-colors';
    tr.setAttribute('data-type', item.tipe);

    tr.innerHTML = `
      <td class="px-6 py-5">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs">${isZakat ? '🌙' : '💰'}</div>
          <div>
            <p class="font-bold text-sm">${item.nama}</p>
            <p class="text-[11px] text-gray-400">${item.email || '-'}</p>
          </div>
        </div>
      </td>
      <td class="px-6 py-5">
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full ${badgeClass}">${badgeLabel}</span>
      </td>
      <td class="px-6 py-5 text-sm text-gray-600">${item.program || 'Donasi Umum'}</td>
      <td class="px-6 py-5 font-black text-[#006600] text-sm">${fmt(item.jumlah)}</td>
      <td class="px-6 py-5 text-sm text-gray-500">${item.tanggal}</td>
      <td class="px-6 py-5 text-sm">
        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>
        <span class="font-medium text-gray-700">${item.status || 'Berhasil'}</span>
      </td>
    `;
    tbody.prepend(tr); // Terbaru di atas
  });
}

// Filter tombol
function filterData(type, btn) {
  const buttons = document.querySelectorAll('.filter-btn');
  buttons.forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const rows = document.querySelectorAll('.transaction-row');
  rows.forEach(row => {
    if (type === 'all' || row.getAttribute('data-type') === type) {
      row.classList.remove('hidden');
    } else {
      row.classList.add('hidden');
    }
  });
}

// Init
renderDynamicRows();