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

// Lấy danh sách bài học
$result = $conn->query("SELECT l.id, l.chapter_id, l.title, l.video_url, l.duration, l.order, c.title as chapter_title 
                        FROM lessons l
                        JOIN chapters c ON l.chapter_id = c.id");

if (!$result) {
  die("Lỗi truy vấn: " . $conn->error);
}
?>

<main class="flex-1 p-6">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-semibold">Danh sách Bài Học</h1>
    <button onclick="document.getElementById('modal-insert-lesson').classList.remove('hidden')"
      class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
      <i class="fas fa-plus"></i> Thêm bài học
    </button>
  </div>

  <div class="bg-white shadow-md rounded p-4">
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Tên chương</th>
          <th class="border px-4 py-2">Tên bài học</th>
          <th class="border px-4 py-2">Thời gian (phút)</th>
          <th class="border px-4 py-2">Thứ tự</th>
          <th class="border px-4 py-2">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="hover:bg-gray-100">
            <td class="border px-4 py-2"><?= htmlspecialchars($row['id']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['chapter_title']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['title']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['duration']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['order']) ?></td>
            <td class="border px-4 py-2">
              <?php
              $id = htmlspecialchars($row['id']);
              $chapter_id = json_encode($row['chapter_id']);
              $title = json_encode($row['title']);
              $video_url = json_encode($row['video_url']);
              $duration = json_encode($row['duration']);
              $order = json_encode($row['order']);
              ?>
              <button onclick='openUpdateModallesson(
                "<?= $id ?>",
                <?= $chapter_id ?>,
                <?= $title ?>,
                <?= $video_url ?>,
                <?= $duration ?>,
                <?= $order ?>
              )' class="text-yellow-500 mr-2">
                <i class="fas fa-edit"></i>
              </button>

              <!-- Nút xóa -->
              <form action="../../includes/modal-delete-lesson.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit" class="text-red-600" onclick="return confirm('Bạn có chắc chắn xóa bài học này?')">
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

<?php include_once '../../includes/modal-insert-lesson.php'; ?>
<?php include_once '../../includes/modal-update-lesson.php'; ?>