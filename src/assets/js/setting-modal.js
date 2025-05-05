//Modal name
function openModal() {
    document.getElementById('nameModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('nameModal').classList.add('hidden');
}
//bio
function openBioModal() {
    document.getElementById('bioModal').classList.remove('hidden');
}
function closeBioModal() {
    document.getElementById('bioModal').classList.add('hidden');
}

//avata

function openModal() {
    document.getElementById('nameModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('nameModal').classList.add('hidden');
}

function openAvatarModal() {
    document.getElementById('avatarModal').classList.remove('hidden');
}

function closeAvatarModal() {
    document.getElementById('avatarModal').classList.add('hidden');
}



//bảo mật và đăng nhập 

const tabButtons = document.querySelectorAll(".tab-btn");
const tabContents = document.querySelectorAll(".tab-content");

tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        const target = btn.getAttribute("data-tab");

        // Ẩn toàn bộ nội dung
        tabContents.forEach(content => content.classList.add("hidden"));

        // Bỏ active các nút
        tabButtons.forEach(b => b.classList.remove("bg-white", "font-semibold"));

        // Hiện nội dung tương ứng
        document.getElementById(target).classList.remove("hidden");

        // Đánh dấu nút đang active
        btn.classList.add("bg-white", "font-semibold");
    });
});
//update_password
function toggleModal(show) {
    document.getElementById('changePasswordModal').classList.toggle('hidden', !show);
}