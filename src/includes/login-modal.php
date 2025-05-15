<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../vendor/autoload.php';

// Cấu hình Google Client
$client = new Google_Client();
$client->setClientId('1017825944006-qked8cif5qc9j8sfsr2fcadojk57ot5a.apps.googleusercontent.com'); // Thay bằng Client ID
$client->setClientSecret('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'); // Thay bằng Client Secret
$client->setRedirectUri('http://localhost/f8_clone/src/includes/google-callback.php');

$client->addScope('email');
$client->addScope('profile');

$authUrl = $client->createAuthUrl();
?>

<!-- Modal Đăng nhập -->
<div id="loginModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-xl w-100">
        <h2 class="text-center font-semibold text-xl mb-4">Đăng nhập</h2>
        <form id="loginForm" method="POST" action="login.php" class="space-y-4">
            <div>
                <label class="text-gray-700">Nhập vào Email</label>
                <input type="email" name="email" placeholder="Email của bạn"
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
            </div>
            <div>
                <label class="text-gray-700">Nhập vào mật khẩu</label>
                <input type="password" name="password" placeholder="Mật khẩu"
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
            </div>
            <button type="submit" id="loginSubmitBtn"
                class="w-full p-2 bg-blue-500 text-white rounded-3xl hover:bg-blue-600">
                Đăng nhập
            </button>
        </form>
        <button onclick="window.location.href='<?php echo $authUrl; ?>'"
            class="w-full py-2 border border-gray-300 rounded-3xl flex items-center justify-center space-x-2 hover:bg-gray-100 mt-4">
            <img src="../assets/images/user/search.png" alt="Google Icon" class="h-5">
            <span class="text-gray-700">Đăng nhập với Google</span>
        </button>
        <p class="text-center mt-4 text-sm">
            Bạn chưa có tài khoản? <a href="#" id="switchToRegister" class="text-orange-500 hover:underline">Đăng
                ký</a><br>
            <a href="#" class="text-orange-500 hover:underline">Quên mật khẩu?</a>
        </p>
        <button id="closeLoginModalBtn" class="mt-4 w-full text-gray-500 hover:text-black text-sm">
            Đóng
        </button>
    </div>
</div>

<!-- Modal Đăng ký -->
<div id="registerModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-center font-semibold text-xl mb-4">Đăng ký</h2>
        <form id="registerForm" method="POST" action="register.php" class="space-y-4">
            <div class="mb-4">
                <label class="text-gray-700">Nhập vào tên</label>
                <input type="text" id="registerName" name="name" placeholder="Tên của bạn"
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="text-gray-700">Nhập vào Email</label>
                <input type="email" id="registerEmail" name="email" placeholder="Email của bạn"
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="text-gray-700">Nhập vào mật khẩu</label>
                <input type="password" id="registerPassword" name="password" placeholder="Mật khẩu"
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="text-gray-700">Xác nhận mật khẩu</label>
                <input type="password" id="confirmPassword" name="confirm_password" placeholder="Xác nhận mật khẩu"
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <div class="g-recaptcha" data-sitekey="6LdKLC0rAAAAAKkkeXbd3GjDLeyly6SeQ80WK9WU"></div>
            </div>
            <button type="submit" id="submitRegisterBtn"
                class="w-full bg-blue-500 text-white py-2 rounded-3xl hover:bg-blue-600">Đăng ký</button>
        </form>
        <p class="text-center mt-4 text-sm">
            Bạn đã có tài khoản? <a href="#" id="switchToLogin" class="text-orange-500 hover:underline">Đăng
                nhập</a><br>
            <a href="#" class="text-orange-500 hover:underline">Quên mật khẩu?</a>
        </p>
        <button id="closeRegisterModalBtn" class="mt-4 w-full text-gray-500 hover:text-black text-sm">Đóng</button>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
