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

// Kiểm tra nếu có ID chương học được gửi lên
if (isset($_POST['id'])) {
  $chapter_id = $_POST['id'];

  // Bắt đầu transaction
  $conn->begin_transaction();

  try {
    // Xóa chương học khỏi bảng 'chapters'
    $stmt = $conn->prepare("DELETE FROM chapters WHERE id = ?");
    $stmt->bind_param("s", $chapter_id);

    // Thực hiện câu lệnh xóa
    if (!$stmt->execute()) {
      throw new Exception("Xóa chương học thất bại: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();

    // Chuyển hướng về trang quản lý chương học sau khi xóa thành công
    header("Location: ../views/dashboard/index.php?type=chapter-management&success=1&action=delete"); 
    exit();
  } catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $conn->rollback();
    die($e->getMessage());
  }
} else {
  die("ID chương học không hợp lệ.");
}
?>
