<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/debug.log');

error_log("Bắt đầu thực thi, Session ID: " . session_id());

// Hàm tạo UUID v4
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

// IMPORT Guzzle và Google Client
use GuzzleHttp\Client as GuzzleClient;
use Google\Client as Google_Client;
use Google_Service_Oauth2;

error_log("Đã cấu hình Google Client");

// Tạo client Google
$client = new Google_Client();
$client->setClientId('1017825944006-qked8cif5qc9j8sfsr2fcadojk57ot5a.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/f8_clone/src/includes/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

// Tạo Guzzle client để xử lý HTTP
$guzzleClient = new GuzzleClient();
$client->setHttpClient($guzzleClient);  // Gán Guzzle client vào Google Client

if (isset($_GET['code'])) {
    error_log("Nhận được code từ Google");

    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        error_log("Đã gọi fetchAccessTokenWithAuthCode");

        if (isset($token['error'])) {
            error_log("Lỗi lấy token: " . json_encode($token));
            die("Lỗi lấy token: " . $token['error']);
        }

        if (!isset($token['access_token'])) {
            error_log("Không có access_token trong phản hồi từ Google.");
            die("Lỗi: Không có access_token.");
        }

        $client->setAccessToken($token['access_token']);
        error_log("Đã đặt access token");

        $google_oauth = new Google_Service_Oauth2($client);
        $userInfo = $google_oauth->userinfo->get();
        error_log("Thông tin từ Google: " . json_encode($userInfo));

        if ($userInfo === NULL) {
            error_log("Không nhận được thông tin người dùng.");
            die("Lỗi: Không nhận được thông tin người dùng.");
        }

        $email = $userInfo->email;
        $name = $userInfo->name;
        $avatar_url = $userInfo->picture; // Lấy URL avatar từ Google

        if (empty($email) || empty($name)) {
            error_log("Không lấy được email hoặc name: " . json_encode($userInfo));
            die("Lỗi: Không lấy được thông tin email hoặc name từ Google.");
        }

        echo "Email: $email, Name: $name, Avatar URL: $avatar_url<br>";

        $name = substr($name, 0, 255);
        $email = substr($email, 0, 255);
        $avatar_url = substr($avatar_url, 0, 255); // Giới hạn độ dài URL avatar

        // Kết nối CSDL
        $conn = new mysqli('localhost', 'root', '', 'f8_clone');
        if ($conn->connect_error) {
            error_log("Kết nối CSDL thất bại: " . $conn->connect_error);
            die("Kết nối CSDL thất bại: " . $conn->connect_error);
        }
        echo "Kết nối CSDL thành công!<br>";
        error_log("Đã kết nối CSDL");

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Lỗi chuẩn bị SELECT: " . $conn->error);
            die("Lỗi chuẩn bị câu truy vấn SELECT: " . $conn->error);
        }

        $stmt->bind_param('s', $email);
        if (!$stmt->execute()) {
            error_log("Lỗi thực thi SELECT: " . $stmt->error);
            die("Lỗi thực thi SELECT: " . $stmt->error);
        }

        $result = $stmt->get_result();
        error_log("Số hàng trả về từ SELECT: " . $result->num_rows);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            // Cập nhật avatar nếu người dùng đã tồn tại
            $sql_update = "UPDATE users SET avatar_url = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            if (!$stmt_update) {
                error_log("Lỗi chuẩn bị UPDATE: " . $conn->error);
                die("Lỗi chuẩn bị UPDATE: " . $conn->error);
            }
            $stmt_update->bind_param('ss', $avatar_url, $user['id']);
            if (!$stmt_update->execute()) {
                error_log("Lỗi thực thi UPDATE: " . $stmt_update->error);
                die("Lỗi thực thi UPDATE: " . $stmt_update->error);
            }
            $stmt_update->close();
            echo "Người dùng đã tồn tại, đăng nhập thành công!<br>";
            error_log("Người dùng đã tồn tại: ID=" . $user['id']);
        } else {
            $id = generateUUID();
            $password = bin2hex(random_bytes(8));
            $role = 'user';

            $sql = "INSERT INTO users (id, name, password, email, role, avatar_url, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Lỗi chuẩn bị INSERT: " . $conn->error);
                die("Lỗi chuẩn bị INSERT: " . $conn->error);
            }

            $stmt->bind_param('ssssss', $id, $name, $password, $email, $role, $avatar_url);
            if (!$stmt->execute()) {
                error_log("Lỗi thực thi INSERT: " . $stmt->error);
                die("Lỗi thực thi INSERT: " . $stmt->error);
            }

            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            echo "Đã thêm người dùng mới vào CSDL! ID: $id<br>";
            error_log("Thêm người dùng mới: ID=$id");
        }

        // Lưu avatar vào session
        $_SESSION['user_picture'] = $avatar_url;

        $stmt->close();
        $conn->close();
        error_log("Đã đóng kết nối CSDL");

        if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
            echo "Session lưu thành công: ID={$_SESSION['user_id']}, Name={$_SESSION['user_name']}, Avatar URL={$_SESSION['user_picture']}<br>";
            error_log("Session lưu thành công: ID={$_SESSION['user_id']}, Name={$_SESSION['user_name']}, Avatar URL={$_SESSION['user_picture']}");
            header('Location: ../views/home.php');
            exit();
        } else {
            error_log("Lỗi lưu session: " . json_encode($_SESSION));
            die("Lỗi: Không lưu được thông tin session.");
        }
    } catch (Exception $e) {
        error_log("Lỗi tổng quát: " . $e->getMessage());
        die("Lỗi: " . $e->getMessage());
    }
} else {
    $authUrl = $client->createAuthUrl();
    error_log("Chuyển hướng đến Google Auth URL: " . $authUrl);
    header('Location: ' . $authUrl);
    exit();
}
?>