<?php
// Bao gồm tất cả các file cần thiết
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controller/HomeController.php';
require_once __DIR__ . '/../controller/LearningController.php'; // Bao gồm LearningController nếu chưa

// Khởi tạo kết nối CSDL
$database = new Database();
$db = $database->getConnection();

$request_uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($request_uri, PHP_URL_PATH); // Lấy phần đường dẫn
echo "<script>console.log('$request_uri')</script>";


// Kiểm tra yêu cầu của URL
switch ($uri) {
  case '/f8_clone/src/views/':
    $homeController = new HomeController($db);
    $homeController->index();
    break;

  case '/f8_clone/src/views/learning':
    $learningController = new LearningController($db);
    $learningController->index();
    break;

  default:
    http_response_code(404);
    echo "404 Not Found";
    break;
}

?>