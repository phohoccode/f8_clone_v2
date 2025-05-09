<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
  $targetDir = __DIR__ . "/images/courses"; // Đường dẫn lưu ảnh
  $fileName = basename($_FILES["avatar"]["name"]);
  $targetFile = $targetDir . $fileName;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Kiểm tra có phải là ảnh không
  $check = getimagesize($_FILES["avatar"]["tmp_name"]);
  if ($check === false) {
    die("File không phải là ảnh.");
  }

  // Kiểm tra dung lượng (ví dụ tối đa 2MB)
  if ($_FILES["avatar"]["size"] > 2 * 1024 * 1024) {
    die("Ảnh quá lớn, tối đa 2MB.");
  }

  // Chỉ cho phép một số định dạng
  $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
  if (!in_array($imageFileType, $allowedTypes)) {
    die("Chỉ cho phép các định dạng JPG, JPEG, PNG, GIF, WEBP.");
  }

  // Đổi tên file để tránh trùng (nếu cần)
  $newFileName = uniqid() . '.' . $imageFileType;
  $uploadPath = $targetDir . $newFileName;

  // Upload ảnh
  if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $uploadPath)) {
    echo "Upload thành công: <a href='images/courses/$newFileName'>$newFileName</a>";
  } else {
    echo "Lỗi khi upload ảnh.";
  }
}
?>