<?php
require_once __DIR__ . '/../models/Courses.php';


class HomeController
{
  private $model;

  public function __construct($db)
  {
    $this->model = new Courses($db);
  }

  public function index()
  {
    // Lấy dữ liệu khóa học từ model
    $courseList = $this->model->getAllCourses();

    // Gửi dữ liệu đến View
    require_once __DIR__ . '/../views/home.php';
  }
}
?>