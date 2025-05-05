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
        $newName = trim($_POST["name"]);
        if (empty($newName)) {
            header("Location: ../views/setting.php?error=Tên không được để trống");
            exit;
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

    // === Cập nhật avatar ===
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file['type'], $allowedTypes)) {
            header("Location: ../views/setting.php?error=Chỉ cho phép ảnh JPG, PNG hoặc GIF");
            exit;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid("avatar_") . "." . $ext;
        $uploadDir = __DIR__ . "/../../public/uploads/";
        $uploadPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            header("Location: ../views/setting.php?error=Không thể lưu ảnh");
            exit;
        }

        $avatarUrl = "../../public/uploads/" . $newFileName;
        $stmt = $conn->prepare("UPDATE users SET avatar_url = ? WHERE id = ?");
        $stmt->bind_param("si", $avatarUrl, $userId);
        $stmt->execute();

        $_SESSION['user_picture'] = $avatarUrl;
    }

    // === Cập nhật mật khẩu ===
    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($new_password) || empty($confirm_password)) {
            header("Location: ../views/setting.php?error=Mật khẩu không được để trống");
            exit;
        }

        if ($new_password !== $confirm_password) {
            header("Location: ../views/setting.php?error=Mật khẩu không khớp");
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $userId);

        if (!$stmt->execute()) {
            header("Location: ../views/setting.php?error=Lỗi khi cập nhật mật khẩu");
            exit;
        }
    }

    // === Redirect cuối cùng sau tất cả thao tác ===
    header("Location: ../views/setting.php?success=Cập nhật thành công");
    exit;
}
?>
