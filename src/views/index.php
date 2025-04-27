<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youtube Playlist Embed</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f0f0f0;
    }

    .container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 500px;
    }

    input,
    button {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .video-box {
      margin-top: 20px;
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid #ddd;
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 4px;
    }

    .video-item {
      background-color: #e9e9e9;
      padding: 8px;
      margin-bottom: 5px;
      border-radius: 4px;
      font-size: 14px;
    }

    .copy-button {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }

    .copy-button:active {
      background-color: #45a049;
    }

    .copy-all-button {
      background-color: #2196F3;
      color: white;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }

    .copy-all-button:active {
      background-color: #1e88e5;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Nhập ID danh sách phát</h2>
    <input type="text" id="playlistId" placeholder="VD: PL_-VfJajZj0Uo72G_6tSY4NRLpmffeXSA" />
    <button onclick="loadVideos()">Tải Video</button>

    <div class="video-box" id="videoBox"></div>

    <button class="copy-all-button" onclick="copyAllLinks()">Sao chép tất cả liên kết</button>
  </div>

  <script>
    let nextPageToken = null;
    let videoLinks = [];

    async function loadVideos() {
      const playlistId = document.getElementById('playlistId').value.trim();
      if (!playlistId) {
        alert('Vui lòng nhập ID danh sách phát');
        return;
      }

      const apiKey = 'AIzaSyAtRnOZWPF_SwCctliSo6eT6TLeZOOHNcw';
      const url = `https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=${playlistId}&key=${apiKey}&maxResults=50${nextPageToken ? `&pageToken=${nextPageToken}` : ''}`;

      try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.items) {
          const videoBox = document.getElementById('videoBox');
          data.items.forEach(item => {
            const videoId = item.snippet.resourceId.videoId;
            const videoUrl = `https://www.youtube.com/embed/${videoId}`;
            const videoElement = document.createElement('div');
            videoElement.className = 'video-item';
            videoElement.innerText = videoUrl;
            videoBox.appendChild(videoElement);

            // Lưu video URL vào mảng
            videoLinks.push(videoUrl);
          });
        }

        // Nếu có nextPageToken, cập nhật để tải thêm video
        nextPageToken = data.nextPageToken || null;
        if (nextPageToken) {
          loadVideos(); // Tải thêm video nếu có nextPageToken
        } else {
          alert('Đã tải hết video.');
        }
      } catch (error) {
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
      }
    }

    function copyAllLinks() {
      const text = videoLinks.join('\n');  // Nối tất cả các video URL lại với nhau
      const tempInput = document.createElement('textarea');
      tempInput.value = text;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand('copy');
      document.body.removeChild(tempInput);
      alert('Đã sao chép tất cả liên kết!');
    }
  </script>
</body>

</html>