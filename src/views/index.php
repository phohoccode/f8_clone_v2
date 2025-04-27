<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang chủ</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div>
    <?php include_once '../includes/header.php'; ?>
    <?php include_once '../includes/login-modal.php'; ?>

    <div class="mt-20 flex min-h-full">
      <?php include_once '../includes/sidebar.php'; ?>
      <div class="flex-1 flex items-center justify-center">
        <h1 class="text-3xl font-bold">Trang chủ</h1>
      </div>
    </div>
  </div>
</body>
<script src="/f8_clone/src/assets/js/modal.js"></script>

</html>