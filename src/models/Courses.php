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

  // Hàm lấy chi tiết khóa học theo slug
  public function getCourseDetailBySlug($slug)
  {
    $sql = "SELECT
              JSON_OBJECT(
                'name', c.title,
                'slug', c.slug,
                'chapters', CONCAT('[', GROUP_CONCAT(
                  JSON_OBJECT(
                    'id', ch.id,
                    'title', ch.title,
                    'order', ch.`order`,
                    'lessons', (
                      SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                          'id', l.id,
                          'title', l.title,
                          'video_url', l.video_url,
                          'duration', l.duration,
                          'order', l.`order`
                        )
                      ), ']')
                      FROM lessons l
                      WHERE l.chapter_id = ch.id
                    )
                  )
                ), ']')
              ) AS course_json
            FROM courses c
            JOIN chapters ch ON ch.course_id = c.id
            WHERE c.slug = ?
            GROUP BY c.id, c.title, c.slug";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return json_decode($row['course_json'], true);
  }
}
?>