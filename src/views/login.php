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

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            echo "<script>alert('Mật khẩu không đúng!'); window.history.back();</script>";
            exit();
        }

        if ((int)$user['is_active'] === 0) {
            echo "<script>alert('Tài khoản chưa kích hoạt. Vui lòng kiểm tra email.'); window.history.back();</script>";
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        echo "<script>alert('Đăng nhập thành công!'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Email không tồn tại!'); window.history.back();</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
