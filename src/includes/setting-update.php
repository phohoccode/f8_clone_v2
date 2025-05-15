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
            header("Location: ../views/setting.php?error=1&action=update-username");
            exit;
        }
        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $userId);
        $stmt->execute();
        // === Redirect cuối cùng sau tất cả thao tác ===
        header("Location: ../views/setting.php?success=1&action=update-username");
        exit;
    }

    // === Cập nhật bio ===
    if (isset($_POST['bio'])) {
        $newBio = trim($_POST['bio']);
        $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
        $stmt->bind_param("si", $newBio, $userId);
        $stmt->execute();
          header("Location: ../views/setting.php?success=1&action=update-bio");
        exit;
    }

    // === Cập nhật avatar ===
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file['type'], $allowedTypes)) {
            header("Location: ../views/setting.php?error=1&error_type=wrong-format&action=update-avatar");
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
          header("Location: ../views/setting.php?success=1&action=update-avatar");
        exit;
    }

    // === Cập nhật mật khẩu ===
    if (
        isset($_POST['current_password']) &&
        isset($_POST['new_password']) &&
        isset($_POST['confirm_password'])
    ) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Lấy mật khẩu hiện tại từ DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($current_hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($current_password, $current_hashed_password)) {
            header("Location: ../views/setting.php?error=1&error_type=incorrect-password&action=update-password");
            exit;
        }
        //Kiểm tra độ mạnh
        if (
            strlen($new_password) < 8 ||
            !preg_match('/[A-Z]/', $new_password) ||
            !preg_match('/[a-z]/', $new_password) ||
            !preg_match('/[0-9]/', $new_password) ||
            !preg_match('/[\W]/', $new_password)
        ) {
            header("Location: ../views/setting.php?error=1&error_type=not-strong-pwd&action=update-password");
            exit;
        }


        if ($new_password !== $confirm_password) {
            header("Location: ../views/setting.php?error=1&error_type=mismatch&action=update-password");
            exit;
        }

        // Cập nhật mật khẩu mới
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $userId);

        if (!$stmt->execute()) {
            header("Location: ../views/setting.php?error=1&error_type=default&action=update-password");
            exit;
        }

            // === Redirect cuối cùng sau tất cả thao tác ===
    header("Location: ../views/setting.php?success=1&action=update-password");
    exit;
    }



}
?>