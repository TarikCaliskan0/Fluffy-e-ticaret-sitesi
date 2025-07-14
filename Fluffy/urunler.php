<?php
session_start();
require_once 'baglan.php';
require_once 'sepet_fonksiyon.php';

// Sepet sayısını hesapla
$sepet_sayisi = sepetSayisiGetir($db, $_SESSION['e_posta'] ?? null);
?>
<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - Ürünler</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="urunler.css" />
    <style>
        /* Sepet Badge Stilleri */
        .market {
            position: relative;
        }
        
        .sepet-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            min-width: 20px;
            animation: badgePulse 2s infinite;
        }
        
        @keyframes badgePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .sepet-badge:hover {
            animation: none;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="banner">
        <div class="marquee">
            💖 Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;💖
            Üyelerimize Özel İndirim Fırsatlarını Kaçırmayın!💖 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>

    <div class="navbar">
        <div class="toggle_btn">
            <i class="fa-solid fa-bars"></i>
        </div>
        <div class="logo-container">
            <a href="index.php"><img src="resimler/logo.png" alt="Fluffy Logo" width="275" /></a>
        </div>
        <div class="nav-right">
            <?php if(isset($_SESSION['e_posta'])): ?>
                <!-- Kullanıcı giriş yapmışsa -->
                <a href="profil.php" class="login-btn">
                    <i class="fa-solid fa-user"></i>
                    Profilim
                </a>
            <?php else: ?>
                <!-- Kullanıcı giriş yapmamışsa -->
                <a href="giris.php" class="login-btn desktop-only">
                    <i class="fa-solid fa-user"></i>
                    Giriş Yap
                </a>
            <?php endif; ?>
            <a href="sepet.php" class="market">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if($sepet_sayisi > 0): ?>
                    <span class="sepet-badge"><?php echo $sepet_sayisi; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <div class="navbar2">
        <ul class="links">
            <li><a href="index.php">AnaSayfa</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="geridonusler.php">Geri Dönüşler</a></li>
            <li><a href="hakkımızda.php">Neden Fluffy?</a></li>
            <li><a href="kampanyalar.php">Kampanyalar</a></li>
        </ul>

        <div class="dropdown_menu">
            <div class="anadiv">
                <h1>Ana Menü</h1>
                <div class="close_btn">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <li><a href="index.php">AnaSayfa</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="geridonusler.php">Geri Dönüşler</a></li>
            <li><a href="hakkımızda.php">Neden Fluffy?</a></li>
            <li><a href="kampanyalar.php">Kampanyalar</a></li>
            <?php if(isset($_SESSION['e_posta'])): ?>
                <li><a href="profil.php" class="login-btn-mobile"><i class="fa-solid fa-user"></i> Profilim</a></li>
            <?php else: ?>
                <li><a href="giris.php" class="login-btn-mobile"><i class="fa-solid fa-user"></i> Giriş Yap</a></li>
            <?php endif; ?>
        </div>
    </div>

    <div class="urunler-container">
        <div class="urun-banner" style="background-image: url('resimler/Arkaplan2.png');">
            <div class="banner-content">
                <h1>Tüm Ürünlerimiz</h1>
            </div>
        </div>

        <div class="urunler-header">
            <div class="siralama-container">
                <label for="siralama">Sıralama:</label>
                <select id="siralama" class="siralama-select">
                    <option value="isim-az">A'dan Z'ye</option>
                    <option value="isim-za">Z'den A'ya</option>
                    <option value="fiyat-artan">Fiyata Göre (Önce En Düşük)</option>
                    <option value="fiyat-azalan">Fiyata Göre (Önce En Yüksek)</option>
                </select>
            </div>
        </div>
        <div class="urunler-grid">
            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/Aslan.jpg" alt="Peluş Aslan">
                </div>
                <h3>Peluş Aslan</h3>
                <p class="fiyat">299.90 TL</p>
                <a href="urun_detay.php?id=aslan" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/balık.webp" alt="Peluş Balık">
                </div>
                <h3>Peluş Balık</h3>
                <p class="fiyat">349.90 TL</p>
                <a href="urun_detay.php?id=balik" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/fil.webp" alt="Peluş Fil">
                </div>
                <h3>Peluş Fil</h3>
                <p class="fiyat">449.90 TL</p>
                <a href="urun_detay.php?id=fil" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/hamster.jpg" alt="Peluş Hamster">
                </div>
                <h3>Peluş Hamster</h3>
                <p class="fiyat">279.90 TL</p>
                <a href="urun_detay.php?id=hamster" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/kapibara.jpg" alt="Peluş Kapibara">
                </div>
                <h3>Peluş Kapibara</h3>
                <p class="fiyat">399.90 TL</p>
                <a href="urun_detay.php?id=kapibara" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/koyun.jpg" alt="Peluş Koyun">
                </div>
                <h3>Peluş Koyun</h3>
                <p class="fiyat">329.90 TL</p>
                <a href="urun_detay.php?id=koyun" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/peluskedi.jpg" alt="Peluş Kedi">
                </div>
                <h3>Peluş Kedi</h3>
                <p class="fiyat">299.90 TL</p>
                <a href="urun_detay.php?id=kedi" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/peluskurt.jpg" alt="Peluş Kurt">
                </div>
                <h3>Peluş Kurt</h3>
                <p class="fiyat">479.90 TL</p>
                <a href="urun_detay.php?id=kurt" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/pelusmaymun.jpg" alt="Peluş Maymun">
                </div>
                <h3>Peluş Maymun</h3>
                <p class="fiyat">369.90 TL</p>
                <a href="urun_detay.php?id=maymun" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/pelusmidilli.png" alt="Peluş Midilli">
                </div>
                <h3>Peluş Midilli</h3>
                <p class="fiyat">429.90 TL</p>
                <a href="urun_detay.php?id=midilli" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/peluspenguen.jpg" alt="Peluş Penguen">
                </div>
                <h3>Peluş Penguen</h3>
                <p class="fiyat">289.90 TL</p>
                <a href="urun_detay.php?id=penguen" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/peluspikachu.webp" alt="Peluş Pikachu">
                </div>
                <h3>Peluş Pikachu</h3>
                <p class="fiyat">499.90 TL</p>
                <a href="urun_detay.php?id=pikachu" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/pelustavsan.jpg" alt="Peluş Tavşan">
                </div>
                <h3>Peluş Tavşan</h3>
                <p class="fiyat">259.90 TL</p>
                <a href="urun_detay.php?id=tavsan" class="goz-at-btn">Göz At</a>
            </div>

            <div class="urun-card">
                <div class="urun-image">
                    <img src="resimler/tilki.webp" alt="Peluş Tilki">
                </div>
                <h3>Peluş Tilki</h3>
                <p class="fiyat">389.90 TL</p>
                <a href="urun_detay.php?id=tilki" class="goz-at-btn">Göz At</a>
            </div>
        </div>

        <div class="dots-navigation">
            <button class="nav-button prev-button" disabled>&lt;</button>
            <div class="dots-container">
                <!-- Dots will be added dynamically by JavaScript -->
            </div>
            <button class="nav-button next-button">&gt;</button>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Hakkımızda</h3>
                <p>Peluş ürünler konusunda uzmanlaşmış bir marka olarak, kalite ve sevimliliği bir araya getiriyoruz.
                </p>
            </div>
            <div class="footer-section">
                <h3>Hızlı Bağlantılar</h3>
                <ul>
                    <li><a href="index.php">AnaSayfa</a></li>
                    <li><a href="urunler.php">Ürünler</a></li>
                    <li><a href="geridonusler.php">Geri Dönüşler</a></li>
                    <li><a href="hakkımızda.php">Neden Fluffy?</a></li>
                    <li><a href="kampanyalar.php">Kampanyalar</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>İletişim</h3>
                <p>E-posta: bilgi@pelus.com</p>
                <p>Telefon: +90 123 456 7890</p>
                <p>Adres: İstanbul, Türkiye</p>
            </div>
            <div class="footer-section">
                <h3>Bizi Takip Edin</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Peluş. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="script.js"></script>
    <script src="urunler.js"></script>
</body>

</html> 