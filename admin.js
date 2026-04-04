function toggleModal(mode) {
  const modal = document.getElementById('campaignModal');
  const title = document.getElementById('modalTitle');

  if (mode === 'add') {
    document.getElementById('selectedImage').classList.add('hidden');
    document.getElementById('previewPlaceholder').classList.remove('hidden');
    document.getElementById('fileInput').value = "";
  }

  if (mode === 'edit') {
    title.innerText = "Edit Program ✏️";
  } else {
    title.innerText = "Program Baru 🚀";
  }

  modal.classList.toggle('modal-active');
}

function previewFile() {
  const preview = document.getElementById('selectedImage');
  const placeholder = document.getElementById('previewPlaceholder');
  const file = document.getElementById('fileInput').files[0];
  const reader = new FileReader();

  reader.onloadend = function() {
    preview.src = reader.result;
    preview.classList.remove('hidden');
    placeholder.classList.add('hidden');
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
  }
}

function confirmDelete() {
  if (confirm('Apakah anda yakin?')) {
    alert('Dihapus!');
}
}