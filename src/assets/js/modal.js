// /assets/js/modal.js

document.addEventListener('DOMContentLoaded', function () {
  const loginBtn = document.getElementById('loginBtn');
  const loginModal = document.getElementById('loginModal');
  const closeModal = document.getElementById('closeModal');

  loginBtn.addEventListener('click', function () {
    console.log('Login button clicked');
    loginModal.classList.remove('hidden');
    loginModal.classList.add('flex')
  });

  closeModal.addEventListener('click', function () {
    loginModal.classList.add('hidden');
    loginModal.classList.remove('flex')
  });

  loginModal.addEventListener('click', function (e) {
    if (e.target === loginModal) {
      loginModal.classList.add('hidden');
      loginModal.classList.remove('flex')
    }
  });
});
