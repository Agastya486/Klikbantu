function filterData(type, btn) {
  // Update Button UI
  const buttons = document.querySelectorAll('.filter-btn');
  buttons.forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // Filter Logic
  const rows = document.querySelectorAll('.transaction-row');
  rows.forEach(row => {
    if (type === 'all' || row.getAttribute('data-type') === type) {
      row.classList.remove('hidden');
    } else {
      row.classList.add('hidden');
    }
  });
}