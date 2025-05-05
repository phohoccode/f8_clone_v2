<div id="nameModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl relative">
            <h2 class="text-xl font-semibold mb-4">Cập nhật họ và tên</h2>
            <form method="POST" action="../includes/setting-update.php">
                <label for="name" class="block mb-2">Họ và tên</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>"
                    class="w-full p-2 border rounded mb-4" required>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Lưu lại</button>
                </div>
            </form>
            <button onclick="closeModal()" class="absolute top-2 right-2 text-xl">&times;</button>
        </div>
    </div>
    <!-- MODAL CẬP NHẬT ẢNH ĐẠI DIỆN -->
    <div id="avatarModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl relative">
            <h2 class="text-xl font-semibold mb-4">Cập nhật ảnh đại diện</h2>
            <form method="POST" action="../includes/setting-update.php" enctype="multipart/form-data">
                <label for="avatar" class="block mb-2">Chọn ảnh mới</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full p-2 border rounded mb-4"
                    required>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAvatarModal()"
                        class="px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Lưu lại</button>
                </div>
            </form>
            <button onclick="closeAvatarModal()" class="absolute top-2 right-2 text-xl">&times;</button>
        </div>
    </div>
    <!-- Modal Cập nhật Bio -->
    <div id="bioModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md relative shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Cập nhật giới thiệu</h2>
            <form action="../includes/setting-update.php" method="POST">
                <textarea name="bio" rows="4" class="w-full p-2 border rounded-md mb-4"
                    placeholder="Nhập giới thiệu mới..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeBioModal()" class="bg-gray-300 px-4 py-2 rounded">Hủy</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Cập
                        nhật</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal update password-->
    <div id="changePasswordModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-xl font-semibold mb-4">Đổi mật khẩu</h2>
            <form action="../includes/setting-update.php" method="POST" class="space-y-4">
                <input type="password" name="new_password" placeholder="Mật khẩu mới" required
                    class="w-full border px-3 py-2 rounded outline-none focus:ring-2 focus:ring-blue-400" />

                <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required
                    class="w-full border px-3 py-2 rounded outline-none focus:ring-2 focus:ring-blue-400" />

                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleModal(false)"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Cập
                        nhật</button>
                </div>
            </form>
        </div>
    </div>