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

// Lấy thông tin bài học cần xóa
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

// Xử lý xóa bài học khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lấy ID của bài học cần xóa
  $id = $_POST['id'] ?? '';

  if (empty($id)) {
    die("ID bài học không hợp lệ");
  }

  // Câu lệnh SQL để xóa bài học
  $sql = "DELETE FROM lessons WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $id);

  if ($stmt->execute()) {
    header("Location: ../views/dashboard/index.php?type=lesson-management&success=1&action=delete"); 
    exit();
  } else {
    die("Xóa bài học thất bại: " . $stmt->error);
  }
}
?>

<!-- Modal Xóa Bài Học -->
<div id="modal-delete-lesson" class="hidden overflow-y-auto fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative max-h-[80vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Xóa Bài Học</h2>
        <form action="../../includes/modal-delete-lesson.php" method="POST" class="space-y-4">
            <!-- Input ẩn cho ID bài học -->
            <input type="hidden" name="id" id="delete-lesson-id" value="<?= htmlspecialchars($lesson['id'] ?? '') ?>">

            <div>
                <label class="block font-medium mb-1">Tên bài học</label>
                <div class="p-2 bg-gray-100 rounded">
                    <?= htmlspecialchars($lesson['title'] ?? 'Không xác định') ?>
                </div>
            </div>

            <div>
                <label class="block font-medium mb-1">Thuộc chương học</label>
                <div class="p-2 bg-gray-100 rounded">
                    <?= htmlspecialchars($lesson['chapter_title'] ?? 'Không xác định') ?>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeDeleteModallesson()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Xóa</button>
            </div>
        </form>

        <button onclick="closeDeleteModallesson()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">×</button>
    </div>
</div>

<script>
    // Hàm đóng modal xóa bài học
    function closeDeleteModallesson() {
        document.getElementById('../../includes/modal-delete-lesson').classList.add('hidden');
    }
</script>
