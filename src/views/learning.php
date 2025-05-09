<?php
// Nạp file cấu hình kết nối cơ sở dữ liệu
require_once __DIR__ . '/../config/database.php';

// Khởi động phiên làm việc (session) để sử dụng thông tin người dùng đã đăng nhập
session_start();

// Khởi tạo kết nối đến CSDL
$database = new Database();
$db = $database->getConnection();

// Lấy ID người dùng từ session nếu đã đăng nhập
$user_id = $_SESSION['user_id'] ?? null;

// Lấy slug khóa học từ tham số GET (ví dụ: ?slug=lap-trinh-php)
$slug = $_GET['slug'] ?? '';

// Nếu không có slug thì chuyển hướng về trang chính
if (empty($slug)) {
  header('Location: /f8_clone/src/views/');
  exit;
}

// Truy vấn SQL để lấy toàn bộ thông tin khóa học
$course_query = "SELECT id, title, slug, objectives, description, price, thumbnail_url 
                FROM courses 
                WHERE slug = ?";

// Chuẩn bị và thực thi truy vấn SQL
$stmt = $db->prepare($course_query);
$stmt->bind_param('s', $slug); // Gắn tham số slug vào truy vấn SQL
$stmt->execute();
$course_result = $stmt->get_result()->fetch_assoc(); // Lấy kết quả từ cơ sở dữ liệu dưới dạng mảng

// Kiểm tra khóa học có tồn tại không
if (!$course_result) {
  echo "Khóa học không tồn tại!";
  exit;
}

$course_id = $course_result['id']; // Lấy ID khóa học từ kết quả truy vấn
$course_json = $course_result; // Lưu thông tin khóa học vào biến
$course_json['objectives'] = json_decode($course_result['objectives'], true) ?? null; // Giải mã mục tiêu khóa học (nếu có)

// Truy vấn lấy các chương của khóa học
$chapter_query = "SELECT id, title, `order` 
                 FROM chapters 
                 WHERE course_id = ? 
                 ORDER BY `order` ASC";

// Chuẩn bị và thực thi truy vấn SQL để lấy các chương của khóa học
$chapter_stmt = $db->prepare($chapter_query);
$chapter_stmt->bind_param('s', $course_id); // Gắn tham số course_id vào truy vấn SQL
$chapter_stmt->execute();
$chapter_result = $chapter_stmt->get_result(); // Lấy kết quả từ cơ sở dữ liệu dưới dạng mảng

$chapters = []; // Mảng lưu các chương của khóa học

while ($chapter = $chapter_result->fetch_assoc()) {

  // Truy vấn lấy bài học cho từng chương
  $lessons = []; // Mảng lưu các bài học của chương

  $lesson_query = "SELECT id, title, video_url, duration, `order`
                  FROM lessons
                  WHERE chapter_id = ?  
                  ORDER BY `order` ASC";

  // Chuẩn bị và thực thi truy vấn SQL để lấy các bài học của chương
  $lesson_stmt = $db->prepare($lesson_query);
  $lesson_stmt->bind_param('s', $chapter['id']); // Gắn tham số chapter_id vào truy vấn SQL
  $lesson_stmt->execute();
  $lesson_result = $lesson_stmt->get_result(); // Lấy kết quả từ cơ sở dữ liệu dưới dạng mảng

  while ($lesson = $lesson_result->fetch_assoc()) {
    $lessons[] = $lesson;
  }

  $chapter['lessons'] = $lessons;
  $chapters[] = $chapter;

  $lesson_stmt->close();
}

// Lưu thông tin các chương vào thông tin khóa học
$course_json['chapters'] = $chapters;

// Kiểm tra khóa học có tồn tại không
if (!$course_json) {
  echo "Khóa học không tồn tại!";
  exit;
}

// Tính tổng số bài học trong khóa
$totalLessons = 0;
foreach ($chapters as $chapter) {
  $totalLessons += count($chapter['lessons'] ?? []); // Cộng dồn số bài học của mỗi chương
}

// Lấy ID bài học nếu được chỉ định
$lessonId = $_GET['id'] ?? null;
$currentData = null;

// Nếu chưa có bài học nào được chọn, chọn bài đầu tiên trong chương đầu tiên
if (empty($lessonId)) {
  foreach ($chapters as $chapter) {
    if (!empty($chapter['lessons'])) {
      $firstLesson = $chapter['lessons'][0]; // Lấy bài học đầu tiên của chương đầu tiên
      $lessonId = $firstLesson['id']; // Cập nhật ID bài học
      $currentData = [
        'lesson' => $firstLesson,
        'chapter_title' => $chapter['title'],
        'course_name' => $course_json['title']
      ];
      break; // Dừng vòng lặp khi đã chọn bài học đầu tiên
    }
  }
} else {
  // Nếu có bài học được chỉ định, tìm và lấy thông tin
  foreach ($chapters as $chapter) {
    if (empty($chapter['lessons']))
      continue;

    foreach ($chapter['lessons'] as $lesson) {
      if (isset($lesson['id']) && $lesson['id'] == $lessonId) {
        $currentData = [
          'lesson' => $lesson,
          'chapter_title' => $chapter['title'],
          'course_name' => $course_json['title']
        ];
        break 2; // Thoát khỏi cả hai vòng lặp khi tìm thấy bài học
      }
    }
  }
}

// Kiểm tra người dùng đã đăng ký khóa học chưa
$is_enrolled = false;
if ($user_id) {
  // Truy vấn kiểm tra người dùng đã đăng ký khóa học hay chưa
  $enroll_stmt = $db->prepare("SELECT 1 FROM enrollments WHERE user_id = ? AND course_id = ?");
  $enroll_stmt->bind_param("ss", $user_id, $course_id); // Gắn tham số user_id và course_id vào truy vấn
  $enroll_stmt->execute();
  $is_enrolled = $enroll_stmt->get_result()->num_rows > 0; // Nếu có kết quả trả về thì người dùng đã đăng ký
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($course_json['name'] ?? 'Khóa học') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <?php include_once '../includes/login-modal.php'; ?>

  <?php if (!$is_enrolled): ?>
    <!-- Hiển thị sidebar và header khi chưa đăng ký -->
    <div class="mt-20 flex min-h-full">
      <?php include_once '../includes/header.php'; ?>
      <?php include_once '../includes/sidebar.php'; ?>
      <div class="flex-1 px-12">
        <?php include_once '../views/learning_unenroll.php'; ?>
      </div>
    </div>
  <?php else: ?>
    <!-- Khi đã đăng ký, ẩn sidebar và header -->
    <div class="flex min-h-full">
      <div class="flex-1">
        <?php include_once '../views/learning_enroll.php'; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php include_once '../includes/footer.php'; ?>
</body>
<script src="/f8_clone/src/assets/js/modal.js"></script>

</html>