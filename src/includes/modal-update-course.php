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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $objectives = $_POST['objectives'];
  $slug = $_POST['slug'];
  $thumbnail_url = $_POST['thumbnail_url'];

  $sql = "UPDATE courses SET title = ?, description = ?, objectives = ?, slug = ?, thumbnail_url = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $title, $description, $objectives, $slug, $thumbnail_url, $id);

  if ($stmt->execute()) {
    header("Location: ../views/dashboard/index.php");
    exit();
  } else {
    echo "Cập nhật thất bại.";
  }
}
?>


<div id="modal-update-course"
  class="hidden overflow-y-auto flex items-center justify-center z-50 fixed inset-0 min-h-full w-full">
  <div onclick="closeUpdateModal()" class="absolute inset-0 bg-black bg-opacity-40 z-[7]"></div>
  <div class="bg-white relative modal-body scale-95 transition-all duration-300 w-full max-w-md p-6 rounded shadow-lg z-10 max-h-[80vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Cập nhật Khóa Học</h2>
    <form action="../../includes/modal-update-course.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" id="update-course-id">

      <div>
        <label class="block font-medium mb-1">Tên khóa học</label>
        <input type="text" name="title" id="update-title" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium mb-1">Mô tả</label>
        <textarea name="description" id="update-description" required
          class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div>
        <label class="block font-medium">Mục tiêu</label>
        <span class="text-red-500 text-sm my-1 inline-block">*Viết theo dạng mỗi mục tiêu 1 gạch đầu dòng</span>
        <textarea name="objectives" id="update-objectives" required class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div>
        <label class="block font-medium mb-1">Slug</label>
        <input type="text" name="slug" id="update-slug" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium mb-1">Đường dẫn ảnh khóa học</label>
        <input type="text" name="thumbnail_url" id="update-thumbnail_url" required
          class="w-full border px-3 py-2 rounded">
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeUpdateModal()"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
      </div>
    </form>
    <!-- Nút đóng -->
    <button onclick="closeUpdateModal()"
      class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
  </div>
</div>