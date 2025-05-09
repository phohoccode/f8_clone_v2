<?php
$type = isset($_GET['type']) ? $_GET['type'] : 'course-management';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bảng điều khiển</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="../../../public/images/logo.webp" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="flex min-h-screen bg-gray-100">

  <?php include_once '../../includes/sidebar_admin.php'; ?>

  <?php

  switch ($type) {
    case 'course-management':
      include_once __DIR__ . '/../../includes/course-management.php';
      break;
    case 'lesson-management':
      include_once __DIR__ . '/../../includes/lesson-management.php';
      break;
    case 'chapter-management':
      include_once __DIR__ . '/../../includes/chapter-management.php';
      break;
    case 'statistics-management':
      include_once __DIR__ . '/../../includes/statistics-management.php';
      break;
    default:
      echo "<p class='p-4'>Chọn một chức năng quản lý từ sidebar.</p>";
      break;
  }

  ?>
</body>
<script src="../../assets/js/course.js"></script>
<script src="../../assets/js/chapter.js"></script>
<script src="../../assets/js/lesson.js"></script>
</html>