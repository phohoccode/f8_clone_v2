<?php
// Kết nối cơ sở dữ liệu
session_start();
require_once '../../config/Database.php';

// Kiểm tra kết nối cơ sở dữ liệu
$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
  die("Kết nối cơ sở dữ liệu thất bại.");
}

// Lấy tổng số học viên trong hệ thống
$user_count_sql = "SELECT COUNT(id) AS total_users FROM users";
$user_count_result = $conn->query($user_count_sql);
$user_count = $user_count_result->fetch_assoc()['total_users'];

// Lấy thống kê số lượng học viên cho mỗi khóa học và số bài học trong mỗi khóa học
$course_stats_sql = "SELECT 
                        c.id AS course_id,
                        c.title AS course_title,
                        COUNT(DISTINCT e.user_id) AS enrolled_students,
                        COUNT(l.id) AS total_lessons
                      FROM courses c
                      LEFT JOIN enrollments e ON c.id = e.course_id
                      LEFT JOIN chapters ch ON c.id = ch.course_id
                      LEFT JOIN lessons l ON ch.id = l.chapter_id
                      GROUP BY c.id";
$course_stats_result = $conn->query($course_stats_sql);

// Tính tổng số bài học
$total_lessons = 0;
while ($row = $course_stats_result->fetch_assoc()) {
  $total_lessons += $row['total_lessons'];
}
?>

<main class="flex-1 p-6">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-semibold">Thống Kê Quản Lý</h1>
  </div>

  <!-- Thống kê tổng quan -->
  <div class="bg-white shadow-md rounded p-4 mb-6">
    <h2 class="text-xl font-semibold mb-4">Tổng Quan</h2>
    <div class="grid grid-cols-3 gap-4">
      <div class="bg-blue-500 text-white p-4 rounded shadow">
        <h3 class="font-medium">Tổng số học viên</h3>
        <p class="text-2xl"><?= $user_count ?></p>
      </div>
      <div class="bg-green-500 text-white p-4 rounded shadow">
        <h3 class="font-medium">Tổng số khóa học</h3>
        <p class="text-2xl"><?= $course_stats_result->num_rows ?></p>
      </div>
      <div class="bg-yellow-500 text-white p-4 rounded shadow">
        <h3 class="font-medium">Tổng số bài học</h3>
        <p class="text-2xl"><?= $total_lessons ?></p>
      </div>
    </div>
  </div>

  <!-- Thống kê khóa học cụ thể -->
  <div class="bg-white shadow-md rounded p-4">
    <h2 class="text-xl font-semibold mb-4">Chi Tiết Thống Kê Khóa Học</h2>
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-4 py-2">Tên khóa học</th>
          <th class="border px-4 py-2">Số học viên tham gia</th>
          <th class="border px-4 py-2">Số bài học</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Quay lại chỉ số con trỏ của $course_stats_result
        $course_stats_result->data_seek(0);
        while ($row = $course_stats_result->fetch_assoc()):
          ?>
          <tr class="hover:bg-gray-100">
            <td class="border px-4 py-2"><?= htmlspecialchars($row['course_title']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['enrolled_students']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['total_lessons']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>