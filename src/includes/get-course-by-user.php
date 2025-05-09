<?php
include_once '../config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT 
          c.id,
          c.slug,
          c.title,
          COUNT(DISTINCT cu.user_id) AS learner_count,
          COUNT(DISTINCT l.id) AS lesson_count
        FROM courses c
        LEFT JOIN enrollments cu ON c.id = cu.course_id
        LEFT JOIN chapters ch ON ch.course_id = c.id
        LEFT JOIN lessons l ON l.chapter_id = ch.id
        GROUP BY c.id, c.slug, c.title, c.thumbnail_url";

$result = $conn->query($sql);

$courseList = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $courseList[] = $row;
  }
}


$conn->close();
?>