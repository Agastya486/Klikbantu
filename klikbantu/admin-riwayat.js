function filterData(type, btn) {
    // Hapus kelas 'active' dari semua tombol
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Tampilkan atau sembunyikan baris berdasarkan tipe
    document.querySelectorAll('.transaction-row').forEach(row => {
        const match = type === 'all' || row.getAttribute('data-type') === type; // Periksa apakah tipe cocok atau jika 'all' dipilih
        row.style.display = match ? '' : 'none'; // Tampilkan baris jika cocok, sembunyikan jika tidak
    });
}
