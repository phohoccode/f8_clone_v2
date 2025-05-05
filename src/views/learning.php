<?php
// Bao gồm các file cần thiết
require_once __DIR__ . '/../config/database.php';

// Khởi tạo kết nối CSDL
$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'] ?? null;
$slug = $_GET['slug'] ?? '';

// Cải tiến truy vấn SQL với ORDER BY trong GROUP_CONCAT
$sql = "SELECT
            JSON_OBJECT(
              'name', c.title,
              'slug', c.slug,
              'id', c.id,
              'thumbnail_url', c.thumbnail_url,
              'chapters', CONCAT('[', GROUP_CONCAT(
                JSON_OBJECT(
                  'id', ch.id,
                  'title', ch.title,
                  'order', ch.`order`,
                  'lessons', IFNULL((
                    SELECT CONCAT('[', GROUP_CONCAT(
                      JSON_OBJECT(
                        'id', l.id,
                        'title', l.title,
                        'video_url', l.video_url,
                        'duration', l.duration,
                        'order', l.`order`
                      ) ORDER BY l.`order` ASC
                    ), ']')
                    FROM lessons l
                    WHERE l.chapter_id = ch.id
                    HAVING COUNT(*) > 0
                  ), 'null')
                ) ORDER BY ch.`order` ASC
              ), ']')
            ) AS course_json
          FROM courses c
          JOIN chapters ch ON ch.course_id = c.id
          WHERE c.slug = ?
          GROUP BY c.id, c.title, c.slug";

// Đảm bảo slug tồn tại trước khi sử dụng
if (empty($slug)) {
  header('Location: /f8_clone/src/views/');
  exit;
}

$stmt = $db->prepare($sql);
$stmt->bind_param('s', $slug);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$course_json = null;
$chapters = [];
$currentData = null;
$lessonId = $_GET['id'] ?? null;

if ($result && isset($result['course_json'])) {
  $course_json = json_decode($result['course_json'], true);

  // Xử lý dữ liệu chapters
  if (isset($course_json['chapters']) && is_string($course_json['chapters'])) {
    $course_json['chapters'] = json_decode($course_json['chapters'], true);
  }

  // Đảm bảo chapters là mảng hợp lệ
  if (isset($course_json['chapters']) && is_array($course_json['chapters'])) {
    $chapters = $course_json['chapters'];

    // Xử lý dữ liệu lessons cho mỗi chapter
    foreach ($chapters as &$chapter) {
      if (isset($chapter['lessons']) && $chapter['lessons'] !== null) {
        if (is_string($chapter['lessons'])) {
          $chapter['lessons'] = json_decode($chapter['lessons'], true);
        }

        // Đảm bảo lessons là mảng hợp lệ
        if (!is_array($chapter['lessons'])) {
          $chapter['lessons'] = [];
        }
      } else {
        $chapter['lessons'] = [];
      }
    }

    // Nếu không có lesson ID được chỉ định, tìm bài học đầu tiên
    if (empty($lessonId)) {
      foreach ($chapters as $chapter) {
        if (!empty($chapter['lessons'])) {
          $firstLesson = $chapter['lessons'][0];
          $lessonId = $firstLesson['id'];
          $currentData = [
            'lesson' => $firstLesson,
            'chapter_title' => $chapter['title'],
            'course_name' => $course_json['name']
          ];
          break;
        }
      }
    } else {
      // Tìm bài học theo ID
      foreach ($chapters as $chapter) {
        if (empty($chapter['lessons']))
          continue;

        foreach ($chapter['lessons'] as $lesson) {
          if (isset($lesson['id']) && $lesson['id'] == $lessonId) {
            $currentData = [
              'lesson' => $lesson,
              'chapter_title' => $chapter['title'],
              'course_name' => $course_json['name']
            ];
            break 2;
          }
        }
      }
    }
  }
}

echo "<script>console.log(" . json_encode($course_json) . ");</script>";


// Tính tổng số bài học
$totalLessons = 0;
if (is_array($chapters)) {
  foreach ($chapters as $chapter) {
    $totalLessons += count($chapter['lessons'] ?? []);
  }
}


// Lấy thông tin khóa học
$course_stmt = $db->prepare("SELECT * FROM courses WHERE slug = ?");
$course_stmt->bind_param("s", $slug);
$course_stmt->execute();
$result = $course_stmt->get_result();

if ($result->num_rows === 0) {
  echo "Khóa học không tồn tại!";
  exit;
}
if (isset($_SESSION)&& is_array($_SESSION)&& isset($_SESSION['user_id'])){
  echo $_SESSION['user_id'];
}else {
  echo('không có id');
}
// $user_id = $_SESSION['user_id'];  // Bạn có thể thay lại bằng session hoặc từ input

// Kiểm tra đã đăng ký hay chưa
$is_enrolled = false;
if ($user_id) {
  $enroll_stmt = $db->prepare("SELECT 1 FROM enrollments WHERE user_id = ? AND course_id = ?");
  $enroll_stmt->bind_param("ii", $user_id, $course_json['id']);
  $enroll_stmt->execute();
  $is_enrolled = $enroll_stmt->get_result()->num_rows > 0;
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
</body>


</html>