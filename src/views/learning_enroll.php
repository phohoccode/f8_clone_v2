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