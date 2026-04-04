// Maksudnya modal adalah form popup untuk tambah/edit campaign.
function toggleModal(mode = 'add') {
    const modal = document.getElementById('campaignModal');
    modal.classList.toggle('modal-active');

    if (mode === 'add') {
        document.getElementById('modalTitle').innerText = "Program Baru 🚀";
        document.getElementById('form-action').value = "add";
        document.getElementById('form-id').value = "";
        document.getElementById('form-nama').value = "";
        document.getElementById('form-kategori').value = "pendidikan";
        document.getElementById('form-target').value = "";
        document.getElementById('form-deskripsi').value = "";
        document.getElementById('form-status').value = "active";
        document.getElementById('preview-gambar').classList.add('hidden');
    }
}

// Alur sederhananya : 
// User klik tombol "Edit" di salah satu campaign --> lalu fungsi editCampaign(data) dipanggil dengan data campaign tersebut sebagai parameter.
function editCampaign(data) {
    const modal = document.getElementById('campaignModal');
    modal.classList.add('modal-active');

    // data.xx maksudnya mengambil nilai dari objek data
    document.getElementById('modalTitle').innerText = "Edit Program ✏️";
    document.getElementById('form-action').value = "edit";
    document.getElementById('form-id').value = data.id;
    document.getElementById('form-nama').value = data.nama;
    document.getElementById('form-kategori').value = data.kategori;
    document.getElementById('form-target').value = data.target_dana;
    document.getElementById('form-deskripsi').value = data.deskripsi;
    document.getElementById('form-status').value = data.status;

    // Tampilkan gambar lama sebagai preview
    const preview = document.getElementById('preview-gambar');
    if (data.gambar) {
        preview.src = 'assets/img/campaigns/' + data.gambar;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}
