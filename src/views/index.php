<?php

include_once '../includes/auth.php';
include_once '../includes/get-courses.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>F8 - Học lập trình để đi làm</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="shortcut icon" href="../../public/images/logo.webp" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body class="relative">
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
                <a href="./learning.php?slug=<?php echo urlencode($course['slug']); ?>"
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
                  <div class="flex gap-4 items-center">
                    <div class="flex gap-1 items-center text-sm text-[#666]">
                      <i class="fa-solid fa-users"></i>
                      <?php echo htmlspecialchars($course['learner_count']); ?>
                    </div>
                    <div class="flex gap-1 items-center text-sm text-[#666]">
                      <i class="fa-solid fa-circle-play"></i>
                      <?php echo htmlspecialchars($course['lesson_count']); ?>
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

  <?php include_once '../includes/footer.php'; ?>
</body>
<script src="/f8_clone/src/assets/js/modal.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$action = $_GET['action'] ?? '';
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
$error_type = $_GET['error_type'] ?? 'default';  // thêm khai báo error_type với default
$succes_type = $_GET['succes_type'] ?? 'default';

$messages = [
  'login' => [
    'success' => [
      'default' => [
        'icon' => 'success',
        'title' => 'Đăng nhập thành công!',
        'text' => 'Chào mừng bạn quay trở lại.'
      ]
    ],
    'error' => [
      'incorrect-password' => [
        'icon' => 'error',
        'title' => 'Sai mật khẩu!',
        'text' => 'Vui lòng kiểm tra lại.'
      ],
      'not-strong-pwd' => [
        'icon' => 'error',
        'title' => 'Mật khẩu không hợp lệ!',
        'text' => 'Tài khoản chưa kích hoạt. Vui lòng kiểm tra email.'
      ],
      'default' => [
        'icon' => 'error',
        'title' => 'Lỗi đăng nhập!',
        'text' => 'Đã xảy ra lỗi khi đăng nhập hoặc Email không tồn tại. Vui lòng thử lại.'
      ]
    ]
  ]

];


if (isset($messages[$action])) {
  if ($success === '1') {
    $msg = $messages[$action]['success'];
    if (is_array($messages[$action]['success'])) {
      // Lấy lỗi theo error_type, nếu không tồn tại thì lấy default
      $msg = $messages[$action]['success'][$error_type] ?? $messages[$action]['success']['default'];
    } else {
      // Nếu chỉ là 1 lỗi chung (không phải mảng)
      $msg = $messages[$action]['success'];
    }
  } elseif ($error === '1') {
    // Kiểm tra xem error có phải là mảng (nhiều loại) hay không
    if (is_array($messages[$action]['error'])) {
      // Lấy lỗi theo error_type, nếu không tồn tại thì lấy default
      $msg = $messages[$action]['error'][$error_type] ?? $messages[$action]['error']['default'];
    } else {
      // Nếu chỉ là 1 lỗi chung (không phải mảng)
      $msg = $messages[$action]['error'];
    }
  }

  if (isset($msg)) {
    echo "<script>
      Swal.fire({
        icon: '{$msg['icon']}',
        title: '{$msg['title']}',
        text: '{$msg['text']}',
        timer: 2000,
        showConfirmButton: true
      });
    </script>";
  }
}
?>
<!-- Xóa các query sau khi hiển thị alert -->
<script>
  if (window.history.replaceState) {
    const url = new URL(window.location.href);
    url.searchParams.delete('success');
    url.searchParams.delete('error');
    url.searchParams.delete('action');
    url.searchParams.delete('error_type');  // xóa luôn error_type cho sạch
    window.history.replaceState({}, document.title, url.pathname);
  }
</script>
<script>
  const courseList = <?php echo json_encode($courseList); ?>;
  console.log(courseList);
</script>

</html>