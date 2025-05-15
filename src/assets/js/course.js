function openInsertModal() {
  const modal = document.getElementById('modal-insert-course');
  const modalBody = modal.querySelector('.modal-body');
  modal.classList.remove('hidden');

  setTimeout(() => {
    modalBody.classList.remove("opacity-0", "scale-95");
    modalBody.classList.add("opacity-100", "scale-100");
  }, 10);
}

function closeInsertModal() {
  const modal = document.getElementById("modal-insert-course");
  const modalBody = modal.querySelector(".modal-body");
  modalBody.classList.remove("opacity-100", "scale-100");
  modalBody.classList.add("opacity-0", "scale-95");

  // Chờ animation xong mới ẩn modal
  setTimeout(() => {
    modal.classList.add("hidden");
  }, 100);
}

function openUpdateModal(id, title, description, objectives, slug, thumbnail_url) {

  const modal = document.getElementById('modal-update-course');
  const modalBody = modal.querySelector('.modal-body');

  // Hiện modal
  modal.classList.remove('hidden');

  // Thêm animation

  setTimeout(() => {
    modalBody.classList.remove("opacity-0", "scale-95");
    modalBody.classList.add("opacity-100", "scale-100");
  }, 10);

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

  document.getElementById('modal-update-course').classList.remove('hidden');
}

function closeUpdateModal() {
  const modal = document.getElementById("modal-update-course");
  const modalBody = modal.querySelector(".modal-body");
  modalBody.classList.remove("opacity-100", "scale-100");
  modalBody.classList.add("opacity-0", "scale-95");

  // Chờ animation xong mới ẩn modal
  setTimeout(() => {
    modal.classList.add("hidden");
  }, 100);
}


function openModalAlert(id) {
  const modalAlert = document.getElementById('modal-alert');
  const courseId = document.getElementById('course-id');
  const modalTitle = document.getElementById('modal-title');
  const modalBody = document.getElementById('modal-body');
  const modalCloseButton = document.getElementById('modal-close-btn');
  const modalSubmitButton = document.getElementById('modal-submit-btn');

  modalTitle.textContent = 'Xóa khóa học';
  modalBody.textContent = 'Bạn có chắc chắn muốn xóa khóa học này không?';
  modalCloseButton.textContent = 'Hủy';
  modalSubmitButton.textContent = 'Xác nhận';

  courseId.value = id;
  // Hiện modal
  modalAlert.classList.remove('hidden');
}

function closeModalAlert() {
  const modalAlert = document.getElementById('modal-alert');
  // Ẩn modal
  modalAlert.classList.add('hidden');
}

function submitModalAlert() {
  const courseId = document.getElementById('course-id').value;

  window.location.href = `/f8_clone/src/includes/delete-course.php?id=${courseId}`;
}