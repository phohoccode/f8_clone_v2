<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


if (isset($_SESSION['user_id'])) {
  $conn = new mysqli('localhost', 'root', '', 'f8_clone');
  $conn->set_charset('utf8mb4');
  if ($conn->connect_error) {
    error_log("Kết nối CSDL thất bại: " . $conn->connect_error);
    die("Có lỗi xảy ra. Vui lòng thử lại sau.");
  }

  $userId = $_SESSION['user_id'];
  $sql = "SELECT name, email, avatar_url FROM users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_name_from_db'] = $user['name'];
    $_SESSION['user_email_from_db'] = $user['email'];
    $_SESSION['user_avatar_url'] = $user['avatar_url'];
  }

  $stmt->close();
  $conn->close();
}

?>

<div class="fixed top-0 left-0 right-0 z-20 flex items-center px-7 gap-8 h-16 border border-[#e8ebed] bg-white text-sm">
  <div class="flex items-center whitespace-nowrap">
    <a href="/f8_clone/src/views/" class="flex">
      <img src="../../public/images/logo.webp" alt="logo" class="w-9 h-9 flex-shrink-0 object-contain rounded-lg">
    </a>
    <a href="/f8_clone/src/views/" class="ml-2 font-semibold">Học lập trình để đi làm</a>
  </div>
  <div class="flex items-center justify-center flex-1">
    <div
      class="flex items-center transition-all focus-within:border-black justify-center w-[420px] max-w-full h-10 px-4 py-2 rounded-full border-2 border-[#e8e8e8]">
      <div class="text-xl">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
          viewBox="0 0 16 16">
          <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
        </svg>
      </div>
      <input type="text" class="flex-1 w-full h-full outline-none border-none px-1 caret-black"
        placeholder="Tìm kiếm khóa học, bài viết...">
    </div>
  </div>
  <div class="flex items-center justify-end flex-shrink-0 ml-auto">
    <?php if (isset($_SESSION['user_id'])): ?>
      <!-- Hiển thị khi đã đăng nhập -->
      <a href="#" class="text-gray-600 hover:text-gray-800 mr-6">Khóa học của tui</a>
      <i class="fa-solid fa-bell text-gray-600 cursor-pointer mr-4"></i>
      <img id="user-avatar" src="<?php
      echo htmlspecialchars(
        !empty($_SESSION['user_avatar_url']) ?
        $_SESSION['user_avatar_url'] :
        ($_SESSION['user_picture'] ?? '../../public/images/avt-user.png')
      );
      ?>" alt="Avatar" class="w-10 h-10 rounded-full cursor-pointer">

    <?php else: ?>
      <!-- Hiển thị khi chưa đăng nhập -->
      <button class="border-none outline-none block text-inherit mr-6">Đăng ký</button>
      <button id="loginBtn"
        class="border-none outline-none block bg-gradient-to-r from-orange-500 to-red-500 flex-shrink-0 rounded-full px-5 py-2 font-semibold cursor-pointer text-gray-50">Đăng
        nhập</button>
    <?php endif; ?>
  </div>
</div>


<?php if (isset($_SESSION['user_id'])): ?>
  <div id="user-modal" class="absolute top-16 right-4 bg-white rounded-lg shadow-lg w-60 hidden z-50">
    <div class="p-4">
      <div class="flex items-center gap-4 mb-4">
        <img id="user-avatar" src="<?php
        echo htmlspecialchars(
          !empty($_SESSION['user_avatar_url']) ?
          $_SESSION['user_avatar_url'] :
          ($_SESSION['user_picture'] ?? '../../public/images/avt-user.png')
        );
        ?>" alt="Avatar" class="w-10 h-10 rounded-full cursor-pointer">

        <div class="text-left">
          <h3 class="text-base font-semibold text-gray-900">
            <?php echo htmlspecialchars($_SESSION['user_name_from_db'] ?? 'Người dùng'); ?>
          </h3>
          <p class="text-sm text-gray-500"><?php
          $email = $_SESSION['user_email_from_db'] ?? '';
          $username = strstr($email, '@', true) ?: $email;
          echo htmlspecialchars('@' . strtolower(str_replace(" ", "", $username)));
          ?></p>
        </div>
      </div>
      <ul class="text-sm text-gray-700">
        <li class="py-2 px-3 hover:bg-gray-100 rounded"><a href="../views/profile.php" class="block">Trang cá nhân</a>
        </li>
        <li class="py-2 px-3 hover:bg-gray-100 rounded"><a href="#" class="block">Viết blog</a></li>
        <li class="py-2 px-3 hover:bg-gray-100 rounded"><a href="#" class="block">Bài viết của tôi</a></li>
        <li class="py-2 px-3 hover:bg-gray-100 rounded"><a href="#" class="block">Bài viết đã lưu</a></li>
        <li class="py-2 px-3 hover:bg-gray-100 rounded"><a href="../views/setting.php" class="block">Cài đặt</a></li>
        <li class="py-2 px-3 hover:bg-gray-100 rounded mt-2 border-t border-gray-200"><a href="?logout=true"
            class="block text-red-600">Đăng xuất</a></li>
      </ul>
    </div>
  </div>

  <script>
    const userAvatar = document.getElementById('user-avatar');
    const userModal = document.getElementById('user-modal');

    userAvatar.addEventListener('click', (e) => {
      e.stopPropagation();
      userModal.classList.toggle('hidden');
    });

    // Đóng modal khi nhấn ra ngoài
    document.addEventListener('click', (e) => {
      if (!userModal.contains(e.target) && e.target !== userAvatar) {
        userModal.classList.add('hidden');
      }
    });
  </script>
<?php endif; ?>