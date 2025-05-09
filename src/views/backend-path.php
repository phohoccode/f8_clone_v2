<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Khóa học Front-end | F8 Clone</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f5f5f5;
    color: #333;
  }

  h1 {
    color: #0a58ca;
  }

  .course-card {
    display: flex;
    background: white;
    margin: 15px 0;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .thumbnail {
    width: 160px;
    height: 100px;
    background: linear-gradient(to right, #f05, #60f);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: bold;
    margin-right: 20px;
  }

  .info h2 {
    margin: 0;
  }

  button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  button:hover {
    background-color: #0056b3;
  }
</style>

<body>
  <h1 style="color:red;text-align:center;font-size:50px;">Lộ trình để học Backend</h1>

  <header>
    <h1>1. Tìm hiểu về ngành IT</h1>
    <p>Để theo ngành IT - Phần mềm cần rèn luyện những kỹ năng nào?</p>
    <div class="course-card">
      <div class="thumbnail">Kiến Thức Nền Tảng</div>
      <div class="info">
        <h2>Kiến Thức Nhập Môn IT</h2>
        <p>Miễn phí</p>
        <a class="bg-[#007bff] px-2 py-1 rounded-md text-white mt-2 inline-block"
          href="/f8_clone/src/views/learning.php?slug=kien-thuc-nhap-mon">XEM KHÓA HỌC</a>

      </div>
    </div>
  </header>

  <section>
    <h1>2. HTML và CSS</h1>
    <p>Để học web Front-end, chúng ta luôn bắt đầu với ngôn ngữ HTML và CSS...</p>

    <div class="course-card">
      <div class="thumbnail">HTML, CSS Pro:</div>
      <div class="info">
        <h2>HTML CSS Pro</h2>
        <p><del></del> <strong>Miễn phí</strong></p>
        <a class="bg-[#007bff] px-2 py-1 rounded-md text-white mt-2 inline-block"
          href="/f8_clone/src/views/learning.php?slug=kien-thuc-nhap-mon">XEM KHÓA HỌC</a>

      </div>
    </div>

    <div class="course-card">
      <div class="thumbnail">Responsive @web design</div>
      <div class="info">
        <h2>Responsive Với Grid System</h2>
        <p>Miễn phí</p>
        <a class="bg-[#007bff] px-2 py-1 rounded-md text-white mt-2 inline-block"
          href="/f8_clone/src/views/learning.php?slug=kien-thuc-nhap-mon">XEM KHÓA HỌC</a>

      </div>
    </div>
    <section>
      <h1>3. JavaScript</h1>
      <p>Với HTML, CSS bạn mới chỉ xây dựng được websites tĩnh... Để thêm chức năng, cần học JavaScript.</p>

      <div class="course-card">
        <div class="thumbnail" style="background: linear-gradient(to right, #ffb347, #ffcc33);">JavaScript<br>{Cơ bản}
        </div>
        <div class="info">
          <h2>Lập Trình JavaScript Cơ Bản</h2>
          <p>Miễn phí</p>
          <p>Học JavaScript cơ bản cho người mới bắt đầu. Với hơn 100 bài học.</p>
          <a class="bg-[#007bff] px-2 py-1 rounded-md text-white mt-2 inline-block"
            href="/f8_clone/src/views/learning.php?slug=kien-thuc-nhap-mon">XEM KHÓA HỌC</a>

        </div>
      </div>

      <div class="course-card">
        <div class="thumbnail" style="background: linear-gradient(to right, #ff512f, #dd2476);">JavaScript<br>{Nâng cao}
        </div>
        <div class="info">
          <h2>Lập Trình JavaScript Nâng Cao</h2>
          <p>Miễn phí</p>
          <p>Tìm hiểu về IIFE, closure, prototype, this, bind, call, apply,...</p>
          <a class="bg-[#007bff] px-2 py-1 rounded-md text-white mt-2 inline-block"
            href="/f8_clone/src/views/learning.php?slug=kien-thuc-nhap-mon">XEM KHÓA HỌC</a>

        </div>
      </div>
    </section>

    <section>
      <h1>4. Sử dụng Ubuntu/Linux</h1>
      <p>Cách làm việc với hệ điều hành Ubuntu/Linux qua Terminal & WSL.</p>

      <div class="course-card">
        <div class="thumbnail" style="background: linear-gradient(to right, #ff5f6d, #ffc371);">WSL Ubuntu</div>
        <div class="info">
          <h2>Làm việc với Terminal & Ubuntu</h2>
          <p>Miễn phí</p>
          <p>Sở hữu một Terminal hiện đại và học cách làm việc với Ubuntu cho Web Dev.</p>
          <a class="bg-[#007bff] px-2 py-1 rounded-md text-white mt-2 inline-block"
            href="/f8_clone/src/views/learning.php?slug=kien-thuc-nhap-mon">XEM KHÓA HỌC</a>

        </div>
      </div>
    </section>

  </section>
</body>

</html>