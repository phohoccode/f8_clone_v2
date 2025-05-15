<?php
$conn = new mysqli("localhost", "root", "", "f8_clone");
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}
$result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");
?>


<main class="flex-1 p-6 min-h-screen overflow-y-auto">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-semibold">Danh sách khóa học</h1>
    <button onclick="openInsertModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
      <i class="fas fa-plus"></i> Thêm khóa học
    </button>
  </div>

  <div class="bg-white shadow-md rounded">
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Tên khóa học</th>
          <th class="border px-4 py-2">Mô tả</th>
          <th class="border px-4 py-2">Mục tiêu</th>
          <th class="border px-4 py-2">Slug</th>
          <th class="border px-4 py-2">Ảnh khóa học</th>
          <th class="border px-4 py-2">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="hover:bg-gray-100">
            <td class="border px-4 py-2"><?= $row['id'] ?></td>
            <td class="border px-4 py-2"><?= $row['title'] ?></td>
            <td class="border px-4 py-2"><?= $row['description'] ?></td>
            <td class="border px-4 py-2"><?= $row['objectives'] ?></td>
            <td class="border px-4 py-2"><?= $row['slug'] ?></td>
            <td class="border px-4 py-2"><?= $row['thumbnail_url'] ?></td>
            <td class="border px-4 py-2">
              <?php
              $id = $row['id'];
              $title = json_encode($row['title']);
              $description = json_encode($row['description']);
              $objectives = json_encode($row['objectives']);
              $slug = json_encode($row['slug']);
              $thumbnail_url = json_encode($row['thumbnail_url']);
              ?>
              <button onclick='openUpdateModal(
            "<?= $id ?>",
            <?= $title ?>,
            <?= $description ?>,
            <?= $objectives ?>,
            <?= $slug ?>,
            <?= $thumbnail_url ?>
          )' class="text-yellow-500 mr-2">
                <i class="fas fa-edit"></i>
              </button>

              <button class="text-red-600" onclick='openModalAlert("<?= $id ?>")'>
                <i class="fas fa-trash"></i></button>
            </td>

          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modal Thêm Khóa Học -->
<?php include_once '../../includes/modal-insert-course.php'; ?>
<?php include_once '../../includes/modal-update-course.php'; ?>
<?php include_once '../../includes/modal-alert.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$action = $_GET['action'] ?? '';
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

$messages = [
  'insert' => [
    'success' => ['icon' => 'success', 'title' => 'Thành công!', 'text' => 'Khóa học đã được thêm.'],
    'error' => ['icon' => 'error', 'title' => 'Thất bại!', 'text' => 'Không thể thêm khóa học. Vui lòng thử lại.']
  ],
  'update' => [
    'success' => ['icon' => 'success', 'title' => 'Thành công!', 'text' => 'Khóa học đã được cập nhật.'],
    'error' => ['icon' => 'error', 'title' => 'Thất bại!', 'text' => 'Không thể cập nhật khóa học. Vui lòng thử lại.']
  ],
  'delete' => [
    'success' => ['icon' => 'success', 'title' => 'Thành công!', 'text' => 'Khóa học đã được xóa.'],
    'error' => ['icon' => 'error', 'title' => 'Thất bại!', 'text' => 'Không thể xóa khóa học. Vui lòng thử lại.']
  ]
];

if (isset($messages[$action])) {
  if ($success === '1') {
    $msg = $messages[$action]['success'];
  } elseif ($error === '1') {
    $msg = $messages[$action]['error'];
  }

  if (isset($msg)) {
    echo "<script>
      Swal.fire({
        icon: '{$msg['icon']}',
        title: '{$msg['title']}',
        text: '{$msg['text']}',
        timer: 2000,
        showConfirmButton: true
      });
    </script>";
  }
}
?>

<!-- Xóa các query sau khi hiển thị alert -->
<script>
  if (window.history.replaceState) {
    const url = new URL(window.location.href);
    url.searchParams.delete('success');
    url.searchParams.delete('error');
    url.searchParams.delete('action');
    window.history.replaceState({}, document.title, url.pathname);
  }
</script>