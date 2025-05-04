<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang chủ</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .frontend {
      border: 1x;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <div>
    <?php include_once '../includes/header.php'; ?>

    <div class="mt-20 flex min-h-full">
      <?php include_once '../includes/sidebar.php'; ?>
      <div class="flex-1 flex items-center justify-center">

        <div class="px-6 py-10 bg-white">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Lộ trình học</h2>
          <p class="text-gray-600 mb-10">
            Để bắt đầu một cách thuận lợi, bạn nên tập trung vào một lộ trình học. Ví dụ: Để đi làm với vị trí “Lập
            trình viên Front-end” bạn nên tập trung vào lộ trình “Front-end”.
          </p>

          <div class="grid md:grid-cols-2 gap-6">
            <!-- Front-end Card -->
            <div class="flex flex-col md:flex-row items-center p-6 border rounded-xl shadow hover:shadow-lg transition">
              <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Lộ trình học Front-end</h3>
                <p class="text-gray-600 mb-4">
                  Lập trình viên Front-end là người xây dựng ra giao diện websites. Trong phần này F8 sẽ chia sẻ cho bạn
                  lộ trình để trở thành lập trình viên Front-end nhé.
                </p>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                  XEM CHI TIẾT
                </button>
              </div>
              <img src="../../public/images/thu.png" alt="Front-end" class="w-24 h-24 ml-6 mt-4 md:mt-0">
            </div>

            <!-- Back-end Card -->
            <div class="flex flex-col md:flex-row items-center p-6 border rounded-xl shadow hover:shadow-lg transition">
              <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Lộ trình học Back-end</h3>
                <p class="text-gray-600 mb-4">
                  Trái với Front-end thì lập trình viên Back-end là người làm việc với dữ liệu, công việc thường nặng
                  tính logic hơn. Chúng ta sẽ cùng tìm hiểu thêm về lộ trình học Back-end nhé.
                </p>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                  XEM CHI TIẾT
                </button>
              </div>
              <img src="../../public/images/hoc backend.png" alt="Back-end" class="w-24 h-24 ml-6 mt-4 md:mt-0">

            </div>
          </div>
          <div class="bg-white py-16 px-6 md:px-20 flex justify-between  gap-10 items-center">
            <!-- Text Content -->
            <div>
              <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Tham gia cộng đồng học viên F8 trên Facebook
              </h2>
              <p class="text-gray-600 mb-6">
                Hàng nghìn người khác đang học lộ trình giống như bạn. Hãy tham gia hỏi đáp, chia sẻ và hỗ trợ nhau
                trong quá trình học nhé.
              </p>
              <a href="#"
                class="inline-block border border-black text-black px-6 py-2 rounded-full hover:bg-gray-100 transition">
                Tham gia nhóm
              </a>
            </div>

            <!-- Image -->
            <div class="flex justify-center">
              <img src="../../public/images/bala.webp" alt="Community Illustration"
                class="max-w-full md:max-w-md p-5 flex items-center justify-between mt-12 ">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>