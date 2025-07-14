document.addEventListener('DOMContentLoaded', function () {
    const siralamaSec = document.getElementById('siralama');
    const urunlerContainer = document.querySelector('.urunler-grid');
    const dotsContainer = document.querySelector('.dots-container');
    const prevButton = document.querySelector('.prev-button');
    const nextButton = document.querySelector('.next-button');
    let urunCards = document.querySelectorAll('.urun-card');
    const itemsPerPage = 12; // 4 sütun x 3 satır = 12 ürün
    let currentPage = 0;
    let totalPages = Math.ceil(urunCards.length / itemsPerPage);

    // Noktaları dinamik olarak oluştur
    function createDots() {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement('span');
            dot.className = 'dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('data-index', i);
            dot.addEventListener('click', () => goToPage(i));
            dotsContainer.appendChild(dot);
        }
    }

    // Sayfa geçişi
    function goToPage(pageIndex) {
        currentPage = pageIndex;
        showPage(currentPage);
        updateNavigation();
    }

    // Navigasyon butonlarını güncelle
    function updateNavigation() {
        prevButton.disabled = currentPage === 0;
        nextButton.disabled = currentPage === totalPages - 1;

        // Aktif noktayı güncelle
        document.querySelectorAll('.dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === currentPage);
        });
    }

    // Ürünleri göster/gizle
    function showPage(pageIndex) {
        urunCards = document.querySelectorAll('.urun-card'); // Güncel ürün listesini al
        urunCards.forEach((card, index) => {
            if (index >= pageIndex * itemsPerPage && index < (pageIndex + 1) * itemsPerPage) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Navigasyon butonları için event listener'lar
    prevButton.addEventListener('click', () => {
        if (currentPage > 0) {
            goToPage(currentPage - 1);
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentPage < totalPages - 1) {
            goToPage(currentPage + 1);
        }
    });

    // Sıralama işlevi
    siralamaSec.addEventListener('change', function () {
        const urunler = Array.from(document.querySelectorAll('.urun-card'));

        urunler.sort((a, b) => {
            const isim1 = a.querySelector('h3').textContent.trim();
            const isim2 = b.querySelector('h3').textContent.trim();
            const fiyat1 = parseFloat(a.querySelector('.fiyat').textContent.replace('TL', '').trim());
            const fiyat2 = parseFloat(b.querySelector('.fiyat').textContent.replace('TL', '').trim());

            switch (siralamaSec.value) {
                case 'isim-az':
                    return isim1.localeCompare(isim2, 'tr');
                case 'isim-za':
                    return isim2.localeCompare(isim1, 'tr');
                case 'fiyat-artan':
                    return fiyat1 - fiyat2;
                case 'fiyat-azalan':
                    return fiyat2 - fiyat1;
                default:
                    return 0;
            }
        });

        // Sıralanmış ürünleri DOM'a yerleştir
        urunlerContainer.innerHTML = '';
        urunler.forEach(urun => urunlerContainer.appendChild(urun));

        // Ürün listesini ve sayfa sayısını güncelle
        urunCards = document.querySelectorAll('.urun-card');
        totalPages = Math.ceil(urunCards.length / itemsPerPage);

        // Noktaları yeniden oluştur
        createDots();

        // İlk sayfaya dön
        currentPage = 0;
        showPage(currentPage);
        updateNavigation();
    });

    // Başlangıç durumu
    createDots();
    showPage(0);
    updateNavigation();
}); 