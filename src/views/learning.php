<?php
// Bao gồm các file cần thiết
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controller/HomeController.php';
require_once __DIR__ . '/../controller/LearningController.php';

// Khởi tạo kết nối CSDL
$database = new Database();
$db = $database->getConnection();

// Tăng giới hạn GROUP_CONCAT để tránh cắt ngắn dữ liệu
$db->query("SET SESSION group_concat_max_len = 1000000");

// Cải tiến truy vấn SQL với ORDER BY trong GROUP_CONCAT
$sql = "SELECT
            JSON_OBJECT(
              'name', c.title,
              'slug', c.slug,
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
$slug = $_GET['slug'] ?? '';
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

// Tính tổng số bài học
$totalLessons = 0;
if (is_array($chapters)) {
  foreach ($chapters as $chapter) {
    $totalLessons += count($chapter['lessons'] ?? []);
  }
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
  <div>
    <div class="flex h-[50px] bg-[#29303b] items-center">
      <a href="/f8_clone/src/views/"
        class="w-[60px] h-full flex items-center justify-center text-white hover:bg-[#0000001a]">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
      <a href="/f8_clone/src/views/" class="flex ml-1">
        <img src="../../public/images/logo.webp" alt="logo" class="w-8 h-8 flex-shrink-0 object-contain rounded-lg">
      </a>
      <span class="text-white text-sm ml-4 font-semibold inline-block">
        <?= htmlspecialchars($course_json['name'] ?? 'Tên khóa học') ?>
      </span>
    </div>

    <?php if ($currentData): ?>
      <div class="grid grid-cols-12">
        <div class="col-span-9">
          <div class="w-full px-[8.5%] bg-black select-none">
            <div class="relative pt-[56.25%]">
              <div class="absolute inset-0 w-full h-full">
                <iframe class="w-full h-full" src="<?= htmlspecialchars($currentData['lesson']['video_url'] ?? '') ?>"
                  frameborder="0" allowfullscreen></iframe>
              </div>
            </div>
          </div>
          <div class="px-[8.5%] mt-12">
            <h3 class="text-xl font-semibold text-black">
              <?= htmlspecialchars($currentData['lesson']['title'] ?? '') ?>
            </h3>
          </div>
        </div>
        <div class="col-span-3 h-[calc(100vh-50px)] overflow-y-auto border-l border-[#ccc] bg-white">
          <div class="p-4 border-b">
            <h1 class="text-base font-semibold text-gray-800">Nội dung khóa học</h1>
            <div class="text-sm text-gray-500 mt-1">
              <?= count($chapters) ?> chương · <?= $totalLessons ?> bài học
            </div>
          </div>

          <div class="divide-y">
            <?php if (is_array($chapters) && !empty($chapters)): ?>
              <?php foreach ($chapters as $chapterIndex => $chapter): ?>
                <?php
                // Xác định nếu chapter này chứa bài học hiện tại
                $containsCurrentLesson = false;
                if ($lessonId && isset($chapter['lessons']) && is_array($chapter['lessons'])) {
                  foreach ($chapter['lessons'] as $lesson) {
                    if (isset($lesson['id']) && $lesson['id'] == $lessonId) {
                      $containsCurrentLesson = true;
                      break;
                    }
                  }
                }
                ?>
                <details class="group" <?= $containsCurrentLesson ? 'open' : '' ?>>
                  <summary class="flex justify-between items-center px-4 py-3 cursor-pointer hover:bg-gray-100">
                    <span class="font-semibold text-gray-800 text-sm">
                      <?= ($chapterIndex + 1) . '. ' . htmlspecialchars($chapter['title'] ?? '') ?>
                    </span>
                    <i class="fa fa-chevron-down transform group-open:rotate-180 transition"></i>
                  </summary>
                  <?php if (!empty($chapter['lessons']) && is_array($chapter['lessons'])): ?>
                    <ul class="text-sm text-gray-700 bg-gray-50">
                      <?php foreach ($chapter['lessons'] as $lessonIndex => $lesson): ?>
                        <li>
                          <a href="?slug=<?= urlencode($course_json['slug']) ?>&id=<?= $lesson['id'] ?>"
                            class="block px-6 py-2 hover:bg-gray-200 <?= $lesson['id'] == $lessonId ? 'bg-gray-200 font-semibold text-black' : '' ?>">
                            <h3 class="text-sm">
                              <?= ($lessonIndex + 1) . '. ' . htmlspecialchars($lesson['title'] ?? '') ?>
                            </h3>
                            <p class="mt-1 text-xs">
                              <?= htmlspecialchars($lesson['duration'] ?? '') ?>
                            </p>
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  <?php else: ?>
                    <div class="px-6 py-2 italic text-gray-500">Chưa có bài học nào.</div>
                  <?php endif; ?>
                </details>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="p-4 text-gray-500 italic">Không có dữ liệu chương học để hiển thị.</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="p-4 text-center text-gray-500 italic">
        Không tìm thấy bài học hoặc chưa chọn bài học.
        <br>
        <a href="/f8_clone/src/views/" class="text-blue-500 hover:underline mt-2 inline-block">Quay lại trang chủ</a>
      </div>
    <?php endif; ?>
  </div>
</body>

<script>
  // Chỉ truyền dữ liệu cần thiết ra JS để giảm kích thước dữ liệu
  const courseData = <?= json_encode($course_json) ?>;
  const currentData = <?= json_encode($currentData) ?>;
</script>

</html>