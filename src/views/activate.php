<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Khởi tạo kết nối database
$database = new Database();
$conn = $database->getConnection();

if ($conn === null) {
    die("Kết nối database không thành công!");
}

// Kiểm tra token từ URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Tìm user với token tương ứng
    $stmt = $conn->prepare("SELECT id FROM users WHERE activation_token = ? AND is_active = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Kích hoạt tài khoản
        $update_stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE activation_token = ?");
        $update_stmt->bind_param("s", $token);
        if ($update_stmt->execute()) {
            echo "Tài khoản của bạn đã được kích hoạt thành công! Vui lòng đăng nhập.";
            // Chuyển hướng về trang đăng nhập sau 3 giây
            // header("Refresh: 3; url=/f8_clone/src/views/login.php");
        } else {
            echo "Có lỗi xảy ra khi kích hoạt tài khoản.";
        }
        $update_stmt->close();
    } else {
        echo "Token không hợp lệ hoặc tài khoản đã được kích hoạt.";
    }
    $stmt->close();
} else {
    echo "Không có token được cung cấp.";
}
?>