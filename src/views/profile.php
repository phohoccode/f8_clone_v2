<?php
session_start();


// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /f8_clone/src/views/home.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /f8_clone/src/views/home.php'); // Giữ người dùng ở home.php sau khi đăng xuất
    exit();
}
try {
    // Kết nối cơ sở dữ liệu với MySQLi
    $conn = new mysqli('localhost', 'root', '', 'f8_clone');
    if ($conn->connect_error) {
        error_log("Kết nối CSDL thất bại: " . $conn->connect_error);
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }


    // Lấy thông tin người dùng
    $stmt = $conn->prepare('SELECT name, email, avatar_url, created_at, bio FROM users WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']); // 'i' tương ứng với kiểu int
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        throw new Exception('Không tìm thấy thông tin người dùng');
    }

    $_SESSION['user_bio'] = $user['bio'];

    // Tính thời gian tham gia
    $joinDate = new DateTime($user['created_at']);
    $now = new DateTime();
    $interval = $joinDate->diff($now);

    // Dữ liệu khóa học (có thể thay bằng truy vấn CSDL)
    $courses = [
        [
            'title' => 'HTML, CSS từ Zero đến Hero',
            'type' => 'Miễn phí',
            'participants' => '209,581',
            'lessons' => '117',
            'duration' => '29h5p'
        ],
        [
            'title' => 'Kiến Thức Nhập Môn IT',
            'type' => 'Miễn phí',
            'participants' => '134,212',
            'lessons' => '9',
            'duration' => '3h12p'
        ]
    ];

} catch (Exception $e) {
    // Ghi log lỗi (thay vì hiển thị trực tiếp trong môi trường production)
    error_log($e->getMessage());
    die('Đã có lỗi xảy ra. Vui lòng thử lại sau.');
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
    <?php include_once '../includes/header.php'; ?>
    <?php include_once '../includes/login-modal.php'; ?>

    <main class="max-w-6xl mx-auto p-4 mt-20">
        <div class="flex gap-4">
            <!-- Thông tin người dùng -->
            <aside class="w-1/4 bg-white rounded-lg shadow-md p-4">
                <img src="<?= htmlspecialchars(!empty($user['avatar_url']) ? $user['avatar_url'] : '../../public/images/avt-user.png') ?>"
                    alt="Avatar" class="w-20 h-20 rounded-full mx-auto mb-4">

                <h1 class="text-xl font-bold text-gray-800 text-center">
                    <?= htmlspecialchars($_SESSION['user_name_from_db'] ?? 'Người dùng') ?>
                </h1>
                <p class="text-sm text-gray-500 text-center mb-4">
                    <?php
                    $email = $_SESSION['user_email_from_db'] ?? '';
                    $username = strtok($email, '@') ?: $email;
                    echo htmlspecialchars('@' . strtolower(str_replace(" ", "", $username)));
                    ?>
                </p>
                <p class="text-sm text-gray-600 text-center italic mb-4">
                    <?= isset($_SESSION['user_bio']) && $_SESSION['user_bio'] ? htmlspecialchars($_SESSION['user_bio']) : 'Chưa có mô tả cá nhân.' ?>
                </p>

                <p class="text-sm text-gray-600 text-center mb-2">0 nguồn theo dõi • 0 đang theo dõi</p>
                <p class="text-sm text-gray-600 text-center">
                    Tham gia F8 từ <?= $joinDate->format('d/m/Y') ?>
                </p>
            </aside>

            <!-- Danh sách khóa học -->
            <section class="w-3/4">
                <header class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Khóa học đang ký (<?= count($courses) ?>)</h2>
                </header>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($courses as $course): ?>
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-md p-4">
                            <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($course['title']) ?></h3>
                            <p class="text-sm mb-2"><?= htmlspecialchars($course['type']) ?></p>
                            <div class="flex justify-between text-sm">
                                <span>👥 <?= htmlspecialchars($course['participants']) ?></span>
                                <span>📖 <?= htmlspecialchars($course['lessons']) ?></span>
                                <span>⏰ <?= htmlspecialchars($course['duration']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>
</body>

</html>