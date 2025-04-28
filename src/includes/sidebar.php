<?php
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Lấy path không kèm query string
?>

<div class="flex-shrink-0">
  <div class="sticky top-[80px] left-0 z-1 flex items-center flex-col w-24 px-2">
    <ul class="flex flex-col gap-y-1 pl-0">
      <li>
        <a href="/f8_clone/src/views/"
          class="w-[72px] h-[72px] flex flex-col items-center justify-center rounded-2xl cursor-pointer text-black <?php echo ($current_path == '/f8_clone/src/views/index.php') ? 'bg-[#f5f5f5]' : 'hover:bg-[#f5f5f5]'; ?>">
          <div class="text-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill"
              viewBox="0 0 16 16">
              <path
                d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
              <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z" />
            </svg>
          </div>
          <span class="text-xs mt-1 font-semibold">Trang chủ</span>
        </a>
      </li>
      <li>
        <a href="/f8_clone/src/views/learning-paths.php"
          class="w-[72px] h-[72px] flex flex-col items-center justify-center rounded-2xl cursor-pointer text-black <?php echo ($current_path == '/f8_clone/src/views/learning-paths.php') ? 'bg-[#f5f5f5]' : 'hover:bg-[#f5f5f5]'; ?>">
          <div class="text-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-newspaper"
              viewBox="0 0 16 16">
              <path
                d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z" />
              <path
                d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z" />
            </svg>
          </div>
          <span class="text-xs mt-1 font-semibold">Lộ trình</span>
        </a>
      </li>
    </ul>
  </div>
</div>