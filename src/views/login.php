<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'f8_clone');
    if ($conn->connect_error) {
        echo "<script>alert('Kết nối thất bại!');</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Nếu tìm thấy user
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Sai mật khẩu
        if (!password_verify($password, $user['password'])) {
            // echo '<script>alert("ikhsdfoihasopdifh")</script>';
            header("Location: ../views/index.php?error=1&error_type=incorrect-password&action=login");
            exit;
        }

        // Tài khoản chưa kích hoạt
        if ((int) $user['is_active'] === 0) {
            header("Location: ../views/index.php?error=1&error_type=not-strong-pwd&action=login");
            exit;
        }

        // Đăng nhập thành công
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../views/index.php?success=1&action=login");
        exit;
    } else {
        // Không tìm thấy user
        header("Location: ../views/index.php?error=1&error_type=account-not-found&action=login");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
