function openUpdateModal(id, title, description, objectives, slug, thumbnail_url, price) {
  console.log(id, title, description, objectives, slug, thumbnail_url, price);

  document.getElementById('update-course-id').value = id;
  document.getElementById('update-title').value = title;
  document.getElementById('update-description').value = description;

  // Nếu objectives là mảng, chuyển thành chuỗi để hiện đúng trong ô input/textarea
  if (Array.isArray(objectives)) {
    document.getElementById('update-objectives').value = objectives.join('\n');
  } else {
    document.getElementById('update-objectives').value = objectives;
  }

  document.getElementById('update-slug').value = slug;
  document.getElementById('update-thumbnail_url').value = thumbnail_url;
  document.getElementById('update-price').value = price;

  document.getElementById('modal-update-course').classList.remove('hidden');
}

function closeUpdateModal() {
  document.getElementById('modal-update-course').classList.add('hidden');
}
