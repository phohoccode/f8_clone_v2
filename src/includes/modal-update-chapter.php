<?php
// Kết nối cơ sở dữ liệu
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'f8_clone';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin chương học (chỉ lấy từ bảng chapters)
$chapter_id = isset($_GET['id']) ? $_GET['id'] : null;
$chapter = null;

if ($chapter_id) {
  $stmt = $conn->prepare("SELECT id, title, `order` FROM chapters WHERE id = ?");
  $stmt->bind_param("s", $chapter_id);
  $stmt->execute();
  $chapter = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$chapter) {
    die("Không tìm thấy chương học");
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? '';
  $chapter_title = trim($_POST['chapter_title'] ?? '');
  $order = (int) ($_POST['order'] ?? 0);

  // Validate
  $errors = [];
  if (empty($id)) {
    $errors[] = "ID chương học không được để trống";
  }
  if (empty($chapter_title)) {
    $errors[] = "Tên chương học không được để trống";
  }
  if ($order <= 0) {
    $errors[] = "Thứ tự phải là số nguyên dương";
  }

  if (!empty($errors)) {
    die(implode("<br>", $errors));
  }

  // Cập nhật chương học
  $sql = "UPDATE chapters SET title = ?, `order` = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sis", $chapter_title, $order, $id);

  if ($stmt->execute()) {
    header("Location: ../views/dashboard/index.php?type=chapter-management"); // Chuyển hướng về trang quản lý chương học
    exit();
  } else {
    die("Cập nhật thất bại: " . $stmt->error);
  }
}
?>

<!-- Form HTML -->
<div id="modal-update-chapter"
  class="hidden overflow-y-auto fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative max-h-[80vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Cập nhật Chương Học</h2>
    <form action="../../includes/modal-update-chapter.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" id="update-chapter-id" value="<?= htmlspecialchars($chapter['id'] ?? '') ?>">

      <!-- Hiển thị tên khóa học (được điền bởi JavaScript) -->
      <div>
        <label class="block font-medium mb-1">Thuộc khóa học</label>
        <div id="update-course-title" class="p-2 bg-gray-100 rounded">
          <!-- JavaScript sẽ điền tên khóa học vào đây -->
        </div>
      </div>

      <div>
        <label class="block font-medium mb-1">Tên chương học</label>
        <input type="text" name="chapter_title" id="update-chapter-title"
          value="<?= htmlspecialchars($chapter['title'] ?? '') ?>" required class="w-full border px-3 py-2 rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">Thứ tự</label>
        <input type="number" name="order" id="update-order" value="<?= htmlspecialchars($chapter['order'] ?? '1') ?>"
          min="1" required class="w-full border px-3 py-2 rounded">
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeUpdateModalchapter()"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
      </div>
    </form>

    <button onclick="closeUpdateModalchapter()"
      class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">×</button>
  </div>
</div>