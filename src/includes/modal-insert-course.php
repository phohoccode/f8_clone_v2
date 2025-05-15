<?php

// Cấu hình kết nối cơ sở dữ liệu
$host = 'localhost'; // Thay đổi nếu cần
$user = 'root'; // Thay đổi nếu cần
$password = ''; // Thay đổi nếu cần
$database = 'f8_clone'; // Thay đổi tên cơ sở dữ liệu nếu cần

// Kết nối tới cơ sở dữ liệu
$conn = new mysqli($host, $user, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = uniqid(); // UUID đơn giản
  $title = $_POST["title"];
  $description = $_POST["description"];
  $objectives = $_POST["objectives"];
  $slug = $_POST["slug"];
  $thumbnail_url = $_POST["thumbnail_url"];

  // Chuyển đổi định dạng mục tiêu thành JSON
  $lines = explode("\n", $objectives);
  // Lọc các dòng có nội dung, loại bỏ dấu gạch đầu dòng và khoảng trắng đầu dòng
  $cleaned = array_filter(array_map(function ($line) {
    $line = trim($line);
    return ltrim($line, "- \t"); // bỏ dấu gạch đầu dòng và khoảng trắng
  }, $lines));
  $objectives_json = json_encode(array_values($cleaned), JSON_UNESCAPED_UNICODE);


  // Kiểm tra slug đã tồn tại chưa
  $check_stmt = $conn->prepare("SELECT id FROM courses WHERE slug = ?");
  $check_stmt->bind_param("s", $slug);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows > 0) {
    // Slug đã tồn tại
    $check_stmt->close();
    $conn->close();

    // Hiển thị thông báo lỗi (chuyển hướng lại hoặc hiển thị HTML)
    echo "<script>window.location.href = '../views/dashboard/index.php?error=1&action=insert';</script>";
    exit();
  }

  $check_stmt->close();

  // Thêm khóa học mới
  $stmt = $conn->prepare("INSERT INTO courses (id, title, description, objectives, slug, thumbnail_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $id, $title, $description, $objectives_json, $slug, $thumbnail_url);
    $stmt->execute();
    $stmt->close();

  $conn->close(); 
  header("Location: ../views/dashboard/index.php?success=1&action=insert");
  exit();
}
?>

<div id="modal-insert-course"
  class="hidden overflow-y-auto flex items-center justify-center z-50 fixed inset-0 min-h-full w-full">
  <div onclick="closeInsertModal()" class="absolute inset-0 bg-black bg-opacity-40 z-[7]"></div>
  <div
    class="bg-white relative modal-body scale-95 transition-all duration-300 w-full max-w-md p-6 rounded shadow-lg z-10 max-h-[80vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Thêm Khóa Học</h2>
    <form action="../../includes/modal-insert-course.php" method="POST" class="space-y-4">
      <div>
        <label class="block font-medium mb-1">Tên khóa học</label>
        <input checked type="text" name="title" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium mb-1">Mô tả</label>
        <textarea name="description" required class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div>
        <label class="block font-medium">Mục tiêu</label>
        <span class="text-red-500 text-sm my-1 inline-block">*Viết theo dạng mỗi mục tiêu 1 gạch đầu dòng</span>
        <textarea name="objectives" required class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div>
        <label class="block font-medium mb-1">Slug</label>
        <input type="text" name="slug" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium mb-1">Đường dẫn ảnh khóa học</label>
        <input type="text" name="thumbnail_url" value="images/courses/" required
          class="w-full border px-3 py-2 rounded">
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="document.getElementById('modal-insert-course').classList.add('hidden')"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lưu</button>
      </div>
    </form>
    <!-- Nút đóng -->
    <button onclick="closeInsertModal()"
      class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
  </div>
</div>