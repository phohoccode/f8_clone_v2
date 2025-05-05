<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Bạn chưa đăng nhập.");
}

$db = new Database();
$conn = $db->getConnection();
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // === Cập nhật tên ===
    if (isset($_POST['name'])) {
        file_put_contents("debug_log.txt", "Đã nhận được name: " . $_POST['name'] . "\n", FILE_APPEND);
        $newName = trim($_POST["name"]);

        if (empty($newName)) {
            die("Tên không được để trống.");
        }

        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $userId);
        $stmt->execute();
    }

    // === Cập nhật bio ===
    if (isset($_POST['bio'])) {
        $newBio = trim($_POST['bio']);

        $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
        $stmt->bind_param("si", $newBio, $userId);
        $stmt->execute();
    }
    // === Cập nhật ảnh đại diện ===
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];

        // Kiểm tra định dạng hợp lệ
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            die("Chỉ cho phép các định dạng ảnh JPG, PNG hoặc GIF.");
        }

        // Đổi tên file
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid("avatar_") . "." . $ext;
        $uploadDir = __DIR__ . "/../../public/uploads/";
        $uploadPath = $uploadDir . $newFileName;


        // Di chuyển file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            die("Không thể lưu ảnh.");
        }

        $avatarUrl = "../../public/uploads/" . $newFileName;

        // Cập nhật DB
        $stmt = $conn->prepare("UPDATE users SET avatar_url = ? WHERE id = ?");
        $stmt->bind_param("si", $avatarUrl, $userId);
        $stmt->execute();

        // Cập nhật session
        $_SESSION['user_picture'] = $avatarUrl;
    }

    // === Chuyển hướng sau khi xử lý ===
    header("Location: ../views/setting.php");
    exit;
}
?>