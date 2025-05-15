<?php
// Cấu hình kết nối cơ sở dữ liệu
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'f8_clone';

// Kết nối tới cơ sở dữ liệu
$conn = new mysqli($host, $user, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

$errors = []; // Khai báo mảng lỗi

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = uniqid(); // UUID đơn giản
  $title = trim($_POST["title"] ?? '');
  $course_id = $_POST["course_id"] ?? '';
  $order = $_POST["order"] ?? '';

  // Kiểm tra dữ liệu đầu vào
  if (empty($title)) {
    $errors[] = "Tên chương không được để trống.";
  }

  if (empty($course_id)) {
    $errors[] = "Khóa học không được chọn.";
  }

  // Lấy max_order để sử dụng cho các thao tác tiếp theo
  if (empty($errors)) {
    $stmt = $conn->prepare("SELECT MAX(`order`) as max_order FROM chapters WHERE course_id = ?");
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $max_order = (int) ($result['max_order'] ?? 0); // Nếu không có chương nào thì max_order = 0

    if (empty($order)) {
      // Nếu không nhập order, lấy max_order + 1
      $order = $max_order + 1;
    } else {
      // Kiểm tra xem order có phải là số không
      if (!is_numeric($order)) {
        $errors[] = "Thứ tự phải là một số.";
      } else {
        $order = (int) $order;
        // Nếu order lớn hơn max_order, đặt nó thành max_order + 1
        if ($order > $max_order + 1) {
          $order = $max_order + 1;
        } else {
          // Kiểm tra và xử lý trường hợp order đã tồn tại hoặc bằng/greater than existing
          $stmt = $conn->prepare("SELECT id FROM chapters WHERE course_id = ? AND `order` >= ?");
          $stmt->bind_param("si", $course_id, $order);
          $stmt->execute();
          $stmt->store_result();

          if ($stmt->num_rows > 0) {
            // Tăng tất cả các order >= $order lên 1 để chèn vào vị trí đó
            $stmt = $conn->prepare("UPDATE chapters SET `order` = `order` + 1 WHERE `order` >= ? AND course_id = ?");
            $stmt->bind_param("is", $order, $course_id);
            if (!$stmt->execute()) {
              $errors[] = "Lỗi khi cập nhật thứ tự: " . $conn->error;
            }
          }
        }
      }
    }
  }

  if (empty($errors)) {
    // Nếu không có lỗi, thực hiện insert vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO chapters (id, course_id, title, `order`, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssi", $id, $course_id, $title, $order);
    $stmt->execute();
    $stmt->close();

    $conn->close();
    header("Location: ../views/dashboard/index.php?type=chapter-management&success=1&action=insert"); 
    exit();
  }
}

?>

<!-- Modal Thêm Chương -->
<div id="modal-insert-chapter"
  class="hidden overflow-y-auto fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative max-h-[80vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Thêm Chương</h2>
    <form action="../../includes/modal-insert-chapter.php" method="POST" class="space-y-4">
      <div>
        <label class="block font-medium mb-1">Khóa học</label>
        <select name="course_id" required class="w-full border px-3 py-2 rounded">
          <?php
          // Lấy danh sách khóa học
          $courses_result = $conn->query("SELECT id, title FROM courses");
          while ($course = $courses_result->fetch_assoc()):
            ?>
            <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div>
        <label class="block font-medium mb-1">Tên chương</label>
        <input type="text" name="title" required class="w-full border px-3 py-2 rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">Thứ tự</label>
        <input type="number" name="order" class="w-full border px-3 py-2 rounded">
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="document.getElementById('modal-insert-chapter').classList.add('hidden')"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lưu</button>
      </div>
    </form>
    <!-- Nút đóng -->
    <button onclick="document.getElementById('modal-insert-chapter').classList.add('hidden')"
      class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">×</button>
  </div>
</div>