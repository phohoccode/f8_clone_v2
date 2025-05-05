<?php
session_start();
require_once "../config/database.php";

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Chưa đăng nhập. Vui lòng đăng nhập trước.");
}

$userId = $_SESSION['user_id'];

$sql = "SELECT name, email, avatar_url, bio FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Lỗi prepare: " . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cài đặt tài khoản</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-pink-50 to-blue-50 min-h-screen flex items-center justify-center">
    <!-- Nút đóng -->
    <button onclick="window.history.back()" class="absolute top-4 right-4 text-gray-600 hover:text-red-500 text-2xl">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="w-full max-w-5xl bg-white shadow-xl rounded-xl flex overflow-hidden">

        <!-- Sidebar -->
        <div class="w-1/3 bg-gradient-to-b from-orange-100 to-orange-200 p-6">
            <div class="text-orange-600 font-bold text-2xl mb-6">F8</div>
            <h2 class="text-xl font-semibold mb-4">Cài đặt tài khoản</h2>
            <p class="text-gray-700 mb-6">Quản lý cài đặt tài khoản của bạn như thông tin cá nhân, bảo mật, v.v.</p>

            <div>
                <button data-tab="tab-personal"
                    class="tab-btn flex items-center w-full p-3 text-gray-800 hover:bg-gray-100 rounded-lg bg-white font-semibold">
                    <i class="fa-solid fa-circle-user fa-2x"></i>
                    <span class="ml-5">Thông tin cá nhân</span>
                </button>
                <button data-tab="tab-security"
                    class="tab-btn flex items-center w-full p-3 text-gray-800 hover:bg-gray-100 rounded-lg">
                    <i class="fa-solid fa-shield fa-2x"></i>
                    <span class="ml-5">Mật khẩu và bảo mật</span>
                </button>
            </div>
        </div>

        <!-- Form setting -->
        <div class="w-2/3 bg-white p-6">
            <div id="tab-personal" class="tab-content">
                <h2 class="text-xl font-semibold mb-4">Thông tin cá nhân</h2>
                <p class="text-gray-600 mb-6">Quản lý thông tin cơ bản như tên hiển thị, tên người dùng, bio và avatar.
                </p>

                <div class="space-y-4">
                    <div onclick="openModal()"
                        class="flex justify-between items-center p-4 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200">
                        <div>
                            <p class="text-sm text-gray-500">Họ và tên</p>
                            <p class="font-medium"><?= htmlspecialchars($user['name']) ?></p>
                        </div>
                        <span class="text-blue-500">›</span>
                    </div>


                    <div onclick="openBioModal()"
                        class="flex justify-between items-center p-4 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                        <div>
                            <p class="text-sm text-gray-500">Giới thiệu</p>
                            <p class="font-medium">
                                <?= isset($user['bio']) && $user['bio'] ? htmlspecialchars($user['bio']) : 'Chưa cập nhật' ?>
                            </p>
                        </div>
                        <span class="text-blue-500">›</span>
                    </div>



                    <div class="flex justify-between items-center p-4 bg-gray-100 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500">Ảnh đại diện</p>
                            <img id="user-avatar" onclick="openAvatarModal()"
                                src="<?= htmlspecialchars($user['avatar_url'] ?? '../../public/images/avt-user.png') ?>"
                                alt="Avatar" class="w-10 h-10 rounded-full cursor-pointer hover:opacity-80 transition">


                        </div>
                        <a href="#" class="text-blue-500 hover:underline">›</a>
                    </div>
                </div>
            </div>

            <!-- Nội dung: Mật khẩu và bảo mật -->
            <div id="tab-security" class="tab-content hidden">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">🔐 Mật khẩu & Bảo mật</h2>
                <p class="text-gray-600 mb-6">Quản lý mật khẩu đăng nhập, xác minh 2 bước và các tính năng bảo mật khác.
                </p>

                <div class="space-y-6">
                    <!-- Đổi mật khẩu -->
                    <div
                        class="flex items-center justify-between p-5 bg-white rounded-xl shadow hover:shadow-md transition duration-200 border border-gray-200">
                        <div class="flex items-center gap-4">
                            <i class="fa-solid fa-key text-orange-500 text-2xl"></i>
                            <div>
                                <p class="text-sm text-gray-500">Mật khẩu</p>
                                <p class="text-gray-800 font-semibold">Đổi mật khẩu của bạn</p>
                            </div>
                        </div>
                        <button onclick="toggleModal(true)"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Thay đổi mật khẩu
                        </button>
                    </div>

                    <!-- Xác minh 2 bước -->
                    <!-- <div
                        class="flex items-center justify-between p-5 bg-white rounded-xl shadow hover:shadow-md transition duration-200 border border-gray-200">
                        <div class="flex items-center gap-4">
                            <i class="fa-solid fa-shield-halved text-green-500 text-2xl"></i>
                            <div>
                                <p class="text-sm text-gray-500">Xác minh 2 bước</p>
                                <p class="text-gray-800 font-semibold">Chưa bật</p>
                            </div>
                        </div>
                        <a href="#" class="text-blue-600 hover:underline font-medium">Bật ngay</a>
                    </div> -->
                </div>
            </div>

        </div>
    </div>
    <?php include_once '../includes/setting-modal.php'; ?>
   


</body>
<script src="/f8_clone/src/assets/js/setting-modal.js"></script>
</html>