// Hàm mở modal cập nhật chương học
function openUpdateModalchapter(id, course_id, course_title, chapter_title, order) {
  console.log(id, course_id, course_title, chapter_title, order);

  // Gán giá trị cho các input trong modal
  const chapterIdInput = document.getElementById('update-chapter-id');
  const chapterTitleInput = document.getElementById('update-chapter-title');
  const orderInput = document.getElementById('update-order');
  const courseTitleDisplay = document.getElementById('update-course-title');

  // Kiểm tra xem các phần tử có tồn tại không
  if (!chapterIdInput || !chapterTitleInput || !orderInput || !courseTitleDisplay) {
    console.error('One or more elements not found');
    return;
  }

  chapterIdInput.value = id;
  chapterTitleInput.value = chapter_title;
  orderInput.value = order;
  courseTitleDisplay.textContent = course_title; // Hiển thị course_title

  // Mở modal cập nhật chương học
  const modal = document.getElementById('modal-update-chapter');
  if (modal) {
    modal.classList.remove('hidden');
  } else {
    console.error('Modal element not found');
  }
}

// Hàm đóng modal cập nhật chương học
function closeUpdateModalchapter() {
  const modal = document.getElementById('modal-update-chapter');
  if (modal) {
    modal.classList.add('hidden');
  }
}