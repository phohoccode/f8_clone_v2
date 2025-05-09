// Hàm mở modal cập nhật bài học
function openUpdateModallesson(id, chapter_id, title, video_url, duration, order) {
  console.log(id, chapter_id, title, video_url, duration, order);

  // Gán giá trị cho các input trong modal
  document.getElementById('update-lesson-id').value = id;
  document.getElementById('update-chapter-id').value = chapter_id;
  document.getElementById('update-title').value = title;
  document.getElementById('update-video-url').value = video_url;
  document.getElementById('update-duration').value = duration;
  document.getElementById('update-order').value = order;

  // Mở modal cập nhật bài học
  document.getElementById('modal-update-lesson').classList.remove('hidden');
}

// Hàm đóng modal cập nhật bài học
function closeUpdateModallesson() {
  document.getElementById('modal-update-lesson').classList.add('hidden');
}
// Hàm mở modal thêm bài học
function openInsertModallesson() {
  document.getElementById('modal-insert-lesson').classList.remove('hidden');
}

// Hàm đóng modal thêm bài học
function closeInsertModallesson() {
  document.getElementById('modal-insert-lesson').classList.add('hidden');
}
