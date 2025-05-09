<?php
session_start();
require_once '../config/Database.php';

if (!isset($_SESSION['user_id'])) {
  echo "<script>
            alert('Vui lòng đăng nhập để thực hiện hành động này.');
            window.location.href = '../views/index.php';
          </script>";
  exit;
}

$user_id = $_SESSION['user_id'];
$course_slug = $_GET['slug'] ?? '';

if ($course_slug) {

  $db = new Database();
  $conn = $db->getConnection();

  // Lấy course_id từ slug
  $course_query = $conn->prepare("SELECT id FROM courses WHERE slug = ?");
  $course_query->bind_param("s", $course_slug);
  $course_query->execute();
  $course_result = $course_query->get_result();

  if ($course_result->num_rows > 0) {
    $course = $course_result->fetch_assoc();
    $course_id = $course['id'];

    // Kiểm tra đã đăng ký chưa
    $check_stmt = $conn->prepare("SELECT 1 FROM enrollments WHERE user_id = ? AND course_id = ?");
    $check_stmt->bind_param("ss", $user_id, $course_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
      $enroll_id = uniqid();
      $insert_stmt = $conn->prepare("INSERT INTO enrollments (id, user_id, course_id) VALUES (?, ?, ?)");
      $insert_stmt->bind_param("sss", $enroll_id, $user_id, $course_id);
      $insert_stmt->execute();
    }

    $check_stmt->close();
  }

  $course_query->close();
  $conn->close();
}

header("Location: ../views/learning.php?slug=" . urlencode($course_slug));
exit;

?>