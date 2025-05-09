<?php
$id = $_GET['id'] ?? null;

if (empty($id)) {
  header('Location: ./course-management.php?type=course-management');
  exit;
}

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "f8_clone");
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Xóa bằng prepared statement để tránh SQL Injection
$stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ../views/dashboard/index.php");

exit;
?>