<?php



// Kiểm tra quyền admin
$is_admin = isset($_SESSION['user_role_from_db']) && $_SESSION['user_role_from_db'] === 'admin';
?>


<div class="flex flex-col lg:flex-row gap-8">
  <!-- Left content -->
  <div class="w-full lg:w-2/3">
    <h1 class="text-4xl font-bold mb-2">
      <?= htmlspecialchars($course_json['name'] ?? 'Khóa học') ?>
    </h1>
    <p class="text-gray-600 mb-8">
      <?= htmlspecialchars($course_json['description'] ?? 'Mô tả') ?>
    </p>

    <!-- Bạn sẽ học được gì -->
    <?php if (!empty($course_json['objectives'])): ?>
      <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Bạn sẽ học được gì?</h2>
        <div class="grid md:grid-cols-2 gap-3">
          <?php foreach ($course_json['objectives'] as $objective): ?>
            <div class="flex items-start gap-2">
              <div class="text-orange-500 mt-1"><i class="fas fa-check"></i></div>
              <p><?= $objective ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Nút Thêm Chương -->
    <?php if ($is_admin): ?>
      <div class="mb-4 text-right">
        <a href="add_chapter.php?course_slug=<?= urlencode($course_json['slug'] ?? "") ?>"
          class="text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">
          Thêm Chương
        </a>
      </div>
    <?php endif; ?>




    <?php if (is_array($chapters) && !empty($chapters)): ?>
      <div class="flex flex-col gap-2">
        <?php foreach ($chapters as $chapterIndex => $chapter): ?>
          <details class="group">
            <summary
              class="flex justify-between items-center px-4 py-3 cursor-pointer rounded-md bg-[#f5f5f5] border border-[#ebebeb]">
              <span class="font-semibold text-gray-800 text-sm">
                <?= ($chapterIndex + 1) . '. ' . htmlspecialchars($chapter['title'] ?? '') ?>
              </span> <i class="fa fa-chevron-down transform group-open:rotate-180 transition"></i>
            </summary>
            <?php if (!empty($chapter['lessons']) && is_array($chapter['lessons'])): ?>
              <ul class="text-sm text-gray-700">
                <?php foreach ($chapter['lessons'] as $lessonIndex => $lesson): ?>
                  <li>
                    <div class="block px-6 py-2 border-b border-[#ebebeb]">
                      <h3 class="text-sm">
                        <?= ($lessonIndex + 1) . '. ' . htmlspecialchars($lesson['title'] ?? '') ?>
                      </h3>
                      <p class="mt-1 text-xs">
                        <?= htmlspecialchars($lesson['duration'] ?? '') ?>
                      </p>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <div class="px-6 py-2 italic text-gray-500">Chưa có bài học nào.</div>
            <?php endif; ?>
          </details> <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="p-4 text-gray-500 italic">Không có dữ liệu chương học để hiển thị.</div>
    <?php endif; ?>

  </div>

  <!-- Right content -->
  <div class="w-full lg:w-1/3">
    <div class="sticky top-[90px] flex flex-col gap-4 items-center">
      <div class="relative pt-[56.25%] h-0 w-full">
        <img class="absolute inset-0 w-full h-full object-cover rounded-2xl"
          src="<?php echo "../../public/" . ($course_json['thumbnail_url'] ?? 'default.jpg'); ?>"
          alt="<?php echo htmlspecialchars($course_json['slug'] ?? ""); ?>">

      </div>
      <h3 class="text-xl text-[#f05123]">Miễn phí</h3>
      <a href="../includes/enroll.php?slug=<?php echo urlencode($course_json['slug'] ?? ""); ?>"
        class="min-w-[180px] rounded-full flex items-center justify-center bg-[#0093fc] text-white text-lg px-4 py-1">Đăng
        ký
        học</a>
      <span class="text-sm text-gray-600">
        <?= htmlspecialchars($totalLessons ?? 0) . ' bài học' ?>
      </span>

    </div>
  </div>
</div>

<script>
  const chapters = <?php echo json_encode($chapters); ?>;
  console.log(chapters);
</script>