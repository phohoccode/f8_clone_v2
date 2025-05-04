document.addEventListener('DOMContentLoaded', function () {
  const loginBtn = document.getElementById('loginBtn');
  const registerBtn = document.getElementById('registerBtn');
  const loginModal = document.getElementById('loginModal');
  const registerModal = document.getElementById('registerModal');
  const closeLoginModalBtn = document.getElementById('closeLoginModalBtn');
  const closeRegisterModalBtn = document.getElementById('closeRegisterModalBtn');
  const switchToRegister = document.getElementById('switchToRegister');
  const switchToLogin = document.getElementById('switchToLogin');
  const submitRegisterBtn = document.getElementById('submitRegisterBtn');
  const registerEmail = document.getElementById('registerEmail');
  const registerPassword = document.getElementById('registerPassword');
  const confirmPassword = document.getElementById('confirmPassword');

  // // Log elements to debug
  // console.log('loginBtn:', loginBtn);
  // console.log('closeLoginModalBtn:', closeLoginModalBtn);

  if (loginBtn) {
    loginBtn.addEventListener('click', function () {
      console.log('Login button clicked');
      if (loginModal) {
        loginModal.classList.remove('hidden');
        loginModal.classList.add('flex');
      }
      if (registerModal) {
        registerModal.classList.add('hidden');
        registerModal.classList.remove('flex');
      }
    });
  }

 
  if (registerBtn) {
    registerBtn.addEventListener('click', function () {
      console.log('Register button clicked');
      if (registerModal) {
        registerModal.classList.remove('hidden');
        registerModal.classList.add('flex');
      }
      if (loginModal) {
        loginModal.classList.add('hidden');
        loginModal.classList.remove('flex');
      }
    });
  }

  if (switchToRegister) {
    switchToRegister.addEventListener('click', function (e) {
      e.preventDefault();
      console.log('Switch to register clicked');
      if (loginModal) {
        loginModal.classList.add('hidden');
        loginModal.classList.remove('flex');
      }
      if (registerModal) {
        registerModal.classList.remove('hidden');
        registerModal.classList.add('flex');
      }
    });
  }
  if(switchToLogin){
    switchToLogin.addEventListener('click',function(e){
     e.preventDefault();
     console.log('Switch to login clicked');
     if(registerModal){
      registerModal.classList.add('hidden');
      registerModal.classList.remove('flex');
     }
     if(loginModal){
      loginModal.classList.remove('hidden');
      loginModal.classList.add('flex');
     }
    })
  }

  if (closeLoginModalBtn) {
    closeLoginModalBtn.addEventListener('click', function () {
      console.log('Close login modal clicked');
      if (loginModal) {
        loginModal.classList.add('hidden');
        loginModal.classList.remove('flex');
      }
    });
  }


  if (closeRegisterModalBtn) {
    closeRegisterModalBtn.addEventListener('click', function () {
      console.log('Close register modal clicked');
      if (registerModal) {
        registerModal.classList.add('hidden');
        registerModal.classList.remove('flex');
      }
    });
  }

  if (loginModal) {
    loginModal.addEventListener('click', function (e) {
      if (e.target === loginModal) {
        loginModal.classList.add('hidden');
        loginModal.classList.remove('flex');
      }
    });
  }


  if (registerModal) {
    registerModal.addEventListener('click', function (e) {
      if (e.target === registerModal) {
        registerModal.classList.add('hidden');
        registerModal.classList.remove('flex');
      }
    });
  }

 
  if (submitRegisterBtn) {
    submitRegisterBtn.addEventListener('click', function () {
      const email = registerEmail ? registerEmail.value : '';
      const password = registerPassword ? registerPassword.value : '';
      const confirmPass = confirmPassword ? confirmPassword.value : '';
      const captchaResponse = typeof grecaptcha !== 'undefined' ? grecaptcha.getResponse() : '';

    
      if (!email || !password || !confirmPass) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
      }

      if (password !== confirmPass) {
        alert('Mật khẩu và xác nhận mật khẩu không khớp!');
        return;
      }

      if (!captchaResponse) {
        alert('Vui lòng xác minh CAPTCHA!');
        return;
      }

      fetch('register.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email: email,
          password: password,
          'g-recaptcha-response': captchaResponse
        }),
      })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
          if (data.status === 'success' && registerModal) {
            registerModal.classList.add('hidden');
            registerModal.classList.remove('flex');
            if (typeof grecaptcha !== 'undefined') {
              grecaptcha.reset(); // Reset CAPTCHA
            }
          }
        })
        .catch(error => {
          console.error('Lỗi:', error);
          
        });
    });
  }
});