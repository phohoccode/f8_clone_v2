<?php
class Courses
{
  private $conn;
  private $table = 'courses';

  // Hàm khởi tạo kết nối CSDL
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Hàm lấy tất cả khóa học
  public function getAllCourses()
  {
    $query = "SELECT * FROM " . $this->table;
    $result = $this->conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
  }

}
?>