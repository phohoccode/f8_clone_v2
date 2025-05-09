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
$result = $conn->query("SELECT c.id, c.course_id, c.title AS chapter_title, c.order, co.title AS course_title
                        FROM chapters c
                        JOIN courses co ON c.course_id = co.id");

if (!$result) {
  die("Lỗi truy vấn: " . $conn->error);
}
?>

<main class="flex-1 p-6">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-semibold">Danh sách chương</h1>
    <button onclick="document.getElementById('modal-insert-chapter').classList.remove('hidden')"
      class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
      <i class="fas fa-plus"></i> Thêm chương
    </button>
  </div>

  <div class="bg-white shadow-md rounded p-4">
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Tên khóa học</th>
          <th class="border px-4 py-2">Tên chương</th>
          <th class="border px-4 py-2">Thứ tự</th>
          <th class="border px-4 py-2">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="hover:bg-gray-100">
            <td class="border px-4 py-2"><?= htmlspecialchars($row['id']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['course_title']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['chapter_title']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['order']) ?></td>
            <td class="border px-4 py-2">
              <?php
              $id = htmlspecialchars($row['id']);
              $course_id = json_encode($row['course_id']);
              $course_title = json_encode($row['course_title']);
              $chapter_title = json_encode($row['chapter_title']);
              $order = json_encode($row['order']);
              ?>
              <button onclick='openUpdateModalchapter(
                "<?= $id ?>",
                <?= $course_id ?>,
                <?= $course_title ?>,
                <?= $chapter_title ?>,
                <?= $order ?>
              )' class="text-yellow-500 mr-2">
                <i class="fas fa-edit"></i>
              </button>

              <!-- Nút xóa: sử dụng form POST để xử lý xóa -->
              <form action="../../includes/modal-delete-chapter.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit" class="text-red-600" onclick="return confirm('Bạn chắc chắn muốn xóa chương này?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include_once '../../includes/modal-insert-chapter.php'; ?>
<?php include_once '../../includes/modal-update-chapter.php'; ?>