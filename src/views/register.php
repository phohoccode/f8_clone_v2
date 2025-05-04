<?php
session_start();

// Yêu cầu file database.php
require_once __DIR__ . '/../config/database.php';

// Yêu cầu PHPMailer
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateUUID()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
// Khởi tạo instance của class Database và lấy kết nối
$database = new Database();
$conn = $database->getConnection();

if ($conn === null) {
    die("Kết nối database không thành công!");
}

// Xử lý form đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    // Kiểm tra mật khẩu khớp
    if ($password !== $confirm_password) {
        die("Mật khẩu xác nhận không khớp!");
    }

    // Xác minh reCAPTCHA
    $secret_key = "6LdKLC0rAAAAADiWmQ3icJRNcI-AdbH8s8P-UlAB";
    if (empty($recaptcha_response)) {
        die("Token reCAPTCHA không được gửi!");
    }

    // Debug token
    echo "Token reCAPTCHA: " . htmlspecialchars($recaptcha_response) . "<br>";

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secret_key,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    $result = curl_exec($ch);
    if ($result === false) {
        die("Lỗi cURL: " . curl_error($ch));
    }
    curl_close($ch);

    $response = json_decode($result, true);
    if (!$response['success']) {
        die("Xác minh reCAPTCHA thất bại: " . implode(", ", $response['error-codes']));
    }

    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        die("Email đã được sử dụng!");
    }

    $id = generateUUID();
    $activation_token = bin2hex(random_bytes(32));
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    $avatar_url = null;
    $is_active = 0;

    // Lưu vào CSDL
    $stmt = $conn->prepare("INSERT INTO users (id, name, email, password, avatar_url, role, created_at, updated_at, activation_token, is_active) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)");
    $stmt->bind_param("sssssssi",$id ,$name, $email, $hashed_password, $avatar_url, $role, $activation_token, $is_active);

    if ($stmt->execute()) {
        // Gửi email kích hoạt
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'haidanglu2004@gmail.com';
            $mail->Password = 'ixah rysn gqhv hfqm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';


            $mail->setFrom('haidanglu2004@gmail.com', 'F8 Clone');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Kích hoạt tài khoản F8 Clone';
            $activation_link = "http://localhost/f8_clone/src/views/activate.php?token=" . $activation_token;
            $mail->Body = "Chào $name,<br><br>Vui lòng nhấp vào liên kết sau để kích hoạt tài khoản của bạn:<br><a href='$activation_link'>Kích hoạt tài khoản</a><br><br>Trân trọng,<br>F8 Clone";
            $mail->AltBody = "Chào $name,\n\nVui lòng truy cập liên kết sau để kích hoạt tài khoản của bạn:\n$activation_link\n\nTrân trọng,\nF8 Clone";

            $mail->send();
        } catch (Exception $e) {
            die("Không thể gửi email kích hoạt. Lỗi: {$mail->ErrorInfo}");
        }

        // Chuyển hướng về trang đăng nhập
        header("Location:../views/home.php");
        exit();
    } else {
        die("Đã xảy ra lỗi khi đăng ký!");
    }

    $stmt->close();
    $check_email->close();
} else {
    echo "Phương thức không hợp lệ!";
}
?>