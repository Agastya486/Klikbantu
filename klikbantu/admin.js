function toggleModal(mode = 'add') {
  const modal = document.getElementById('campaignModal');
  const title = document.getElementById('modalTitle');
  const action = document.getElementById('form-action');
  
  modal.classList.toggle('modal-active');
  
  if(mode === 'add') {
      title.innerText = "Program Baru 🚀";
      action.value = "add";
      document.getElementById('form-id').value = "";
      // Reset form fields
      document.getElementById('form-nama').value = "";
      document.getElementById('form-target').value = "";
      document.getElementById('form-deskripsi').value = "";
  }
}

function editCampaign(data) {
  toggleModal('edit');
  document.getElementById('modalTitle').innerText = "Edit Program ✏️";
  document.getElementById('form-action').value = "edit";
  
  // Isi data ke form
  document.getElementById('form-id').value = data.id;
  document.getElementById('form-nama').value = data.nama;
  document.getElementById('form-kategori').value = data.kategori;
  document.getElementById('form-target').value = data.target_dana;
  document.getElementById('form-deskripsi').value = data.deskripsi;
}
