<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>F8 - Học lập trình để đi làm</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="../../public/images/logo.webp" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div>
    <?php include_once '../includes/header.php'; ?>
    <?php include_once '../includes/login-modal.php'; ?>

    <div class="mt-20 flex min-h-full">
      <?php include_once '../includes/sidebar.php'; ?>
      <div class="flex-1 px-12">

        <h2 class="text-2xl font-bold mb-6">Khóa học miễn phí</h2>

        <?php if (!empty($courseList)): ?>
          <div class="grid lg:grid-cols-4 grid-cols-2 gap-x-6 gap-y-8">
            <?php foreach ($courseList as $course): ?>
              <div
                class="flex flex-col h-full rounded-2xl transition-all hover:-translate-y-1 hover:shadow-[0_4px_8px_#0000001a] overflow-hidden">
                <a href="./learning.php?slug=<?php echo urlencode($course['slug']); ?>&id=<?php echo urlencode($course['id']); ?>"
                  class="relative block w-full pt-[56.25%] object-cover">
                  <img class="absolute inset-0 w-full h-full object-cover"
                    src="<?php echo "../../public/" . $course['thumbnail_url']; ?>" alt="<?php echo $course['slug']; ?>">
                </a>
                <div class="flex-1 flex flex-col gap-3 px-4 py-5 bg-[#f7f7f7]">
                  <h3 class="text-sm font-semibold">
                    <?php echo htmlspecialchars($course['title']); ?>
                  </h3>
                  <div class="flex items-center gap-2 mb-2">
                    <span class="text-sm font-semibold text-[#f05123]">
                      Miễn phí
                    </span>
                  </div>
                  <div class="flex justify-between">
                    <div class="flex gap-1 items-center text-sm text-[#666]">
                      <i class="fa-solid fa-users"></i>
                      123
                    </div>
                    <div class="flex gap-1 items-center text-sm text-[#666]">
                      <i class="fa-solid fa-circle-play"></i>
                      4
                    </div>
                    <div class="flex gap-1 items-center text-sm text-[#666]">
                      <i class="fa-solid fa-clock"></i>
                      3h12p
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-red-500">Không có khóa học nào.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
<script src="/f8_clone/src/assets/js/modal.js"></script>
<script>
  // Chuyển dữ liệu từ PHP sang JavaScript
  const courseList = <?php echo json_encode($courseList); ?>;

  console.log(courseList);
</script>

</html>