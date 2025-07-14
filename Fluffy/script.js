document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.toggle_btn');
    const dropDownMenu = document.querySelector('.dropdown_menu');
    const closeBtn = document.querySelector('.close_btn');

    if (toggleBtn && dropDownMenu) {
        toggleBtn.onclick = function () {
            dropDownMenu.classList.add('open');
        };
    }
    if (closeBtn && dropDownMenu) {
        closeBtn.onclick = function () {
            dropDownMenu.classList.remove('open');
        };
    }

    // Kaydırma noktaları ve butonları için JavaScript
    const scrollContainer = document.querySelector('.yeniurun');
    const dots = document.querySelectorAll('.scroll-dot');
    const productCards = document.querySelectorAll('.product-card');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    if (scrollContainer && productCards.length > 0 && dots.length > 0 && prevBtn && nextBtn) {
        const itemsPerView = Math.floor(scrollContainer.clientWidth / productCards[0].offsetWidth);

        // Scroll pozisyonuna göre aktif noktayı güncelle
        function updateActiveDot() {
            const scrollPosition = scrollContainer.scrollLeft;
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            const scrollPercentage = scrollPosition / maxScroll;
            const activeDotIndex = Math.round(scrollPercentage * (dots.length - 1));

            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === activeDotIndex);
            });

            // Butonların görünürlüğünü güncelle
            prevBtn.style.opacity = scrollPosition <= 0 ? "0.5" : "1";
            nextBtn.style.opacity = scrollPosition >= maxScroll ? "0.5" : "1";
        }

        // Belirli bir noktaya scroll yapma fonksiyonu
        function scrollToDot(index) {
            const scrollWidth = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            const scrollPosition = (index / (dots.length - 1)) * scrollWidth;

            scrollContainer.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
        }

        // Noktalara tıklama olayı
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                scrollToDot(index);
            });
        });

        // Ok butonlarına tıklama olayları
        prevBtn.addEventListener('click', () => {
            const currentScroll = scrollContainer.scrollLeft;
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            const scrollPercentage = currentScroll / maxScroll;
            const currentDotIndex = Math.round(scrollPercentage * (dots.length - 1));

            // Bir önceki noktaya git
            if (currentDotIndex > 0) {
                scrollToDot(currentDotIndex - 1);
            }
        });

        nextBtn.addEventListener('click', () => {
            const currentScroll = scrollContainer.scrollLeft;
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            const scrollPercentage = currentScroll / maxScroll;
            const currentDotIndex = Math.round(scrollPercentage * (dots.length - 1));

            // Bir sonraki noktaya git
            if (currentDotIndex < dots.length - 1) {
                scrollToDot(currentDotIndex + 1);
            }
        });

        // Scroll olayını dinle
        scrollContainer.addEventListener('scroll', updateActiveDot);

        // Sayfa yüklendiğinde ilk durumu ayarla
        updateActiveDot();
    }
});





