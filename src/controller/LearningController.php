<?php
require_once __DIR__ . '/../models/Courses.php';

class LearningController
{
  private $model;

  public function __construct($db)
  {
    $this->model = new Courses($db);
  }

  // Trang chi tiết khóa học
  public function index($slug)
  {
    $courseDetail = $this->model->getCourseDetailBySlug($slug);

    if (!$courseDetail) {
      echo "Khóa học không tồn tại!";
      return;
    }

    require_once __DIR__ . '/../views/learning.php';
  }
}
?>