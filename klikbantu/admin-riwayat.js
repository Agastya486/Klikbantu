function fmt(n) {
  return 'Rp ' + Number(n).toLocaleString('id-ID');
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