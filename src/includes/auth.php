<?php
session_start();

// Đăng xuất nếu có ?logout
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: /f8_clone/src/views/index.php');
  exit();
}

// Nếu đã đăng nhập và chưa có thông tin người dùng trong session
if (
  isset($_SESSION['user_id']) &&
  (!isset($_SESSION['user_name_from_db']) || !isset($_SESSION['user_email_from_db']))
) {

  include_once '../config/Database.php'; // hoặc điều chỉnh đường dẫn nếu cần

  $db = new Database();
  $conn = $db->getConnection();
  $conn->set_charset('utf8mb4');

  $userId = $_SESSION['user_id'];
  $sql = "SELECT name, email FROM users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_name_from_db'] = $user['name'];
    $_SESSION['user_email_from_db'] = $user['email'];
  } else {
    error_log("Không tìm thấy người dùng với ID: " . $userId);
    die("Không tìm thấy thông tin người dùng.");
  }

  $stmt->close();
  $conn->close();
}

// Khởi tạo các biến có thể dùng trong view
$userName = $_SESSION['user_name_from_db'] ?? null;
$userEmail = $_SESSION['user_email_from_db'] ?? null;
$usernameX = $userName ? "@" . strtolower(str_replace(" ", "", $userName)) : null;
$userPicture = $_SESSION['user_picture'] ?? 'https://via.placeholder.com/50';
?>