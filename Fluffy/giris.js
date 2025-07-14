document.addEventListener('DOMContentLoaded', function () {
    // Bildirim gösterme fonksiyonu
    function showNotification(message, type = 'success') {
        // Varsa eski bildirimi kaldır
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Yeni bildirim oluştur
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
        notification.innerHTML = `
            <i class="fas ${icon}"></i>
            <span class="notification-message">${message}</span>
        `;

        document.body.appendChild(notification);

        // Animasyonu başlat
        setTimeout(() => notification.classList.add('show'), 10);

        // Yönlendirme öncesi bildirimi göster
        return new Promise(resolve => {
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(resolve, 500);
            }, 1500);
        });
    }

    // Şifre göster/gizle işlevselliği
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    });

    // Giriş formu işlemleri
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Burada giriş işlemleri yapılacak
            console.log('Giriş yapılıyor:', { email, password });

            await showNotification('Giriş başarılı! Yönlendiriliyorsunuz...');
            window.location.href = 'index.html';
        });
    }

    // Kayıt formu işlemleri
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = {
                name: document.getElementById('name').value,
                surname: document.getElementById('surname').value,
                email: document.getElementById('email').value,
                age: document.getElementById('age').value,
                address: document.getElementById('address').value,
                password: document.getElementById('password').value,
                confirmPassword: document.getElementById('confirmPassword').value
            };

            // Şifre kontrolü
            if (formData.password !== formData.confirmPassword) {
                showNotification('Şifreler eşleşmiyor!', 'error');
                return;
            }

            // Burada kayıt işlemleri yapılacak
            console.log('Kayıt yapılıyor:', formData);

            await showNotification('Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz...');
            window.location.href = 'giris.html';
        });
    }
}); 