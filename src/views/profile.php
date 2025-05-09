<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header('Location: /f8_clone/src/views/index.php');
  exit();
}
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: /f8_clone/src/views/index.php');
  exit();
}

try {
  $conn = new mysqli('localhost', 'root', '', 'f8_clone');
  if ($conn->connect_error) {
    error_log("Kết nối CSDL thất bại: " . $conn->connect_error);
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
  }

  // Thông tin người dùng
  $stmt = $conn->prepare('SELECT name, email, avatar_url, created_at, bio FROM users WHERE id = ?');
  $stmt->bind_param('i', $_SESSION['user_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user)
    throw new Exception('Không tìm thấy thông tin người dùng');

  $_SESSION['user_bio'] = $user['bio'];
  $joinDate = new DateTime($user['created_at']);


  // Lấy khóa học đã tham gia
  $sql = "SELECT 
            c.title,
            c.slug,
            COUNT(DISTINCT e.user_id) AS participants,
            COUNT(DISTINCT l.id) AS lessons,
            SEC_TO_TIME(SUM(l.duration)) AS duration
          FROM courses c
          JOIN enrollments e ON e.course_id = c.id
          LEFT JOIN chapters ch ON ch.course_id = c.id
          LEFT JOIN lessons l ON l.chapter_id = ch.id
          WHERE e.user_id = ?
          GROUP BY c.id";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $_SESSION['user_id']);
  $stmt->execute();
  $result = $stmt->get_result();

  $courses = [];
  while ($row = $result->fetch_assoc()) {
    $courses[] = [
      'title' => $row['title'],
      'participants' => number_format($row['participants']),
      'lessons' => $row['lessons'],
      'duration' => $row['duration'] ? gmdate('H\g i\p', strtotime($row['duration'])) : '0g0p'
    ];
  }

  $conn->close();
} catch (Exception $e) {
  error_log($e->getMessage());
  die($e);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Trang cá nhân</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
  <?php include_once '../includes/header.php'; ?>
  <?php include_once '../includes/login-modal.php'; ?>

  <main class="max-w-6xl mx-auto p-4 mt-20">
    <div class="flex gap-4">
      <!-- Thông tin người dùng -->
      <aside class="w-1/4 bg-white rounded-lg shadow-md p-4">
        <img
          src="<?= htmlspecialchars(!empty($user['avatar_url']) ? $user['avatar_url'] : '../../public/images/avt-user.png') ?>"
          alt="Avatar" class="w-20 h-20 rounded-full mx-auto mb-4">
        <h1 class="text-xl font-bold text-gray-800 text-center"><?= htmlspecialchars($user['name'] ?? 'Người dùng') ?>
        </h1>
        <p class="text-sm text-gray-500 text-center mb-4">@<?= htmlspecialchars(strtok($user['email'], '@')) ?></p>
        <p class="text-sm text-gray-600 text-center italic mb-4">
          <?= isset($user['bio']) && $user['bio'] ? htmlspecialchars($user['bio']) : 'Chưa có mô tả cá nhân.' ?>
        </p>
        <p class="text-sm text-gray-600 text-center">Tham gia từ <?= $joinDate->format('d/m/Y') ?></p>
      </aside>

      <!-- Danh sách khóa học -->
      <section class="w-3/4">
        <header class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Khóa học đã tham gia (<?= count($courses) ?>)</h2>
        </header>
        <div class="grid grid-cols-2 gap-4">
          <?php foreach ($courses as $course): ?>
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-md p-4">
              <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($course['title']) ?></h3>
              <div class="flex gap-2 items-center text-sm">
                <span>👥 <?= $course['participants'] ?></span>
                <span>📖 <?= $course['lessons'] ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
  </main>

  <?php include_once '../includes/footer.php'; ?>
</body>

</html>