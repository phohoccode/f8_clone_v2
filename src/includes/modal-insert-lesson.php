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

// Lấy danh sách các chương học từ cơ sở dữ liệu
$chapter_result = $conn->query("SELECT id, title FROM chapters");
if (!$chapter_result) {
  die("Lỗi truy vấn chương học: " . $conn->error);
}

// Kiểm tra xem form có được gửi hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lấy dữ liệu từ form
  $chapter_id = $_POST['chapter_id'] ?? '';
  $title = trim($_POST['title'] ?? '');
  $video_url = $_POST['video_url'] ?? '';
  $duration = $_POST['duration'] ?? 0;
  $order = $_POST['order'] ?? 0;

  // Validate dữ liệu
  $errors = [];
  if (empty($chapter_id) || empty($title)) {
    $errors[] = "Thông tin bài học không đầy đủ.";
  }
  if ($duration <= 0) {
    $errors[] = "Thời gian phải là số nguyên dương.";
  }
  // Kiểm tra nếu có lỗi thì dừng xử lý
  if (!empty($errors)) {
    die(implode("<br>", $errors));
  }

  // Tính toán giá trị order nếu chưa nhập
  if ($order == 0) {
    // Lấy max_order trong chapter
    $stmt = $conn->prepare("SELECT MAX(`order`) AS max_order FROM lessons WHERE chapter_id = ?");
    $stmt->bind_param("s", $chapter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $max_order = $row['max_order'] ?? 0;

    // Nếu không có bài học nào trong chương, max_order sẽ là 0, nên order sẽ là 1
    if ($max_order == 0) {
      $order = 1;
    } else {
      $order = $max_order + 1;  // Nếu có bài học, order là max_order + 1
    }
  } else {
    // Kiểm tra nếu order lớn hơn max_order + 1
    $stmt = $conn->prepare("SELECT MAX(`order`) AS max_order FROM lessons WHERE chapter_id = ?");
    $stmt->bind_param("s", $chapter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $max_order = $row['max_order'] ?? 0;

    if ($order > $max_order + 1) {
      $order = $max_order + 1; // Nếu order lớn hơn max_order + 1, gán lại giá trị order
    }
  }

  // Câu lệnh SQL để thêm bài học
  $stmt = $conn->prepare("INSERT INTO lessons (id, chapter_id, title, video_url, duration, `order`) VALUES (UUID(), ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssii", $chapter_id, $title, $video_url, $duration, $order);

  // Thực thi câu lệnh
  if ($stmt->execute()) {
    header("Location: ../views/dashboard/index.php?type=lesson-management"); // Chuyển hướng về trang quản lý bài học
    exit();
  } else {
    die("Thêm bài học thất bại: " . $stmt->error);
  }
}
?>

<!-- Modal để thêm bài học -->
<div id="modal-insert-lesson"
  class="hidden overflow-y-auto fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative max-h-[80vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Thêm Bài Học</h2>
    <form action="../../includes/modal-insert-lesson.php" method="POST" class="space-y-4">
      <!-- Chọn chương học -->
      <div>
        <label class="block font-medium mb-1">Chọn Chương Học</label>
        <select name="chapter_id" id="insert-chapter-id" required class="w-full border px-3 py-2 rounded">
          <option value="" disabled selected>Chọn chương học</option>
          <?php while ($chapter = $chapter_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($chapter['id']) ?>">
              <?= htmlspecialchars($chapter['title']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Tên bài học -->
      <div>
        <label class="block font-medium mb-1">Tên bài học</label>
        <input type="text" name="title" id="insert-title" required class="w-full border px-3 py-2 rounded">
      </div>

      <!-- URL Video -->
      <div>
        <label class="block font-medium mb-1">URL Video</label>
        <input type="text" name="video_url" id="insert-video-url" required class="w-full border px-3 py-2 rounded">
      </div>

      <!-- Thời gian -->
      <div>
        <label class="block font-medium mb-1">Thời gian (phút)</label>
        <input type="number" name="duration" id="insert-duration" required class="w-full border px-3 py-2 rounded">
      </div>

      <!-- Thứ tự bài học -->
      <div>
        <label class="block font-medium mb-1">Thứ tự</label>
        <input type="number" name="order" id="insert-order" class="w-full border px-3 py-2 rounded">
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeInsertModallesson()"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Thêm bài học</button>
      </div>
    </form>

    <!-- Nút đóng -->
    <button onclick="closeInsertModallesson()"
      class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">×</button>
  </div>
</div>

<!-- JavaScript để đóng modal -->
<script>
  function closeInsertModallesson() {
    document.getElementById('modal-insert-lesson').classList.add('hidden');
  }
</script>