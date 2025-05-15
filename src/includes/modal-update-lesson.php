<?php
// Kết nối cơ sở dữ liệu
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'f8_clone';

$conn = new mysqli($host, $user, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin bài học hiện tại
$lesson_id = isset($_GET['id']) ? $_GET['id'] : null;
$lesson = null;

if ($lesson_id) {
  $stmt = $conn->prepare("SELECT l.*, c.title as chapter_title 
                          FROM lessons l
                          JOIN chapters c ON l.chapter_id = c.id
                          WHERE l.id = ?");
  $stmt->bind_param("s", $lesson_id);
  $stmt->execute();
  $lesson = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$lesson) {
    die("Không tìm thấy bài học");
  }
}

// Kiểm tra nếu có form gửi lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lấy dữ liệu từ form
  $id = $_POST['id'] ?? '';
  $chapter_id = $_POST['chapter_id'] ?? '';
  $title = $_POST['title'] ?? '';
  $video_url = $_POST['video_url'] ?? '';
  $duration = $_POST['duration'] ?? 0;
  $order = $_POST['order'] ?? 0;

  // Validate input
  $errors = [];
  if (empty($id) || empty($title)) {
    $errors[] = "Thiếu thông tin bài học";
  }

  if ($duration <= 0 || $order <= 0) {
    $errors[] = "Thời gian và thứ tự phải là số nguyên dương";
  }

  if (!empty($errors)) {
    die(implode("<br>", $errors));
  }

  // Cập nhật bài học
  $sql = "UPDATE lessons SET title = ?, video_url = ?, duration = ?, `order` = ? WHERE id = ? AND chapter_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssiiis", $title, $video_url, $duration, $order, $id, $chapter_id);

  if ($stmt->execute()) {
    header("Location: ../views/dashboard/index.php?type=lesson-management&success=1&action=update"); 
    exit();
  } else {
    die("Cập nhật thất bại: " . $stmt->error);
  }
}
?>

<!-- Modal cập nhật bài học -->
<div id="modal-update-lesson"
  class="hidden overflow-y-auto fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative max-h-[80vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Cập nhật Bài Học</h2>
    <form action="../../includes/modal-update-lesson.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" id="update-lesson-id" value="<?= htmlspecialchars($lesson['id'] ?? '') ?>">
      <input type="hidden" name="chapter_id" id="update-chapter-id"
        value="<?= htmlspecialchars($lesson['chapter_id'] ?? '') ?>">

      <div>
        <label class="block font-medium mb-1">Tên bài học</label>
        <input type="text" name="title" id="update-title" value="<?= htmlspecialchars($lesson['title'] ?? '') ?>"
          required class="w-full border px-3 py-2 rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">URL Video</label>
        <input type="text" name="video_url" id="update-video-url"
          value="<?= htmlspecialchars($lesson['video_url'] ?? '') ?>" required class="w-full border px-3 py-2 rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">Thời gian (phút)</label>
        <input type="number" name="duration" id="update-duration"
          value="<?= htmlspecialchars($lesson['duration'] ?? 0) ?>" required class="w-full border px-3 py-2 rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">Thứ tự</label>
        <input type="number" name="order" id="update-order" value="<?= htmlspecialchars($lesson['order'] ?? 0) ?>"
          required class="w-full border px-3 py-2 rounded">
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeUpdateModallesson()"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
      </div>
    </form>

    <button onclick="closeUpdateModallesson()"
      class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">×</button>
  </div>
</div>