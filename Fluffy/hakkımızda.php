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
    <title>Fluffy - Hakkımızda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="hakkımızda.css" />
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
            <li><a href="#">Kampanyalar</a></li>
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
            <li><a href="#">Kampanyalar</a></li>
            <?php if(isset($_SESSION['e_posta'])): ?>
                <li><a href="profil.php" class="login-btn-mobile"><i class="fa-solid fa-user"></i> Profilim</a></li>
            <?php else: ?>
                <li><a href="giris.php" class="login-btn-mobile"><i class="fa-solid fa-user"></i> Giriş Yap</a></li>
            <?php endif; ?>
        </div>
    </div>

    <div class="about-hero">
        <div class="about-hero-content">
            <h1>Neden Fluffy?</h1>
            <p>Sevdiklerinize özel anlar yaratmanız için tasarlanmış, kaliteli ve güvenilir bir marka olarak öne
                çıkıyoruz.</p>
        </div>
        <div class="about-hero-image">
            <img src="resimler/Arkaplan2.png" alt="Arkaplan">
        </div>
    </div>

    <div class="about-container">
        <div class="about-section">
            <div class="about-card">
                <div class="about-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h2>Kaliteli ve Güvenli Ürünler</h2>
                <p>Ürünlerimizde sadece en kaliteli ve güvenli malzemeleri kullanıyoruz. Evcil hayvanınızın sağlığı
                    bizim için her şeyden önemli.</p>
            </div>
            <div class="about-card">
                <div class="about-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h2>İade ve Değişim Garantisi</h2>
                <p>Fluffy , müşteri memnuniyetini ön planda tutar. Beklentilerinizi karşılamayan bir durum
                    olduğunda ürünlerinizi kolayca iade edebilir veya değiştirebilirsiniz.</p>
            </div>
            <div class="about-card">
                <div class="about-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Güvenli Ödeme Seçenekleri</h2>
                <p>Fluffy, güvenli ödeme yöntemleri sunarak kredi kartı bilgilerinizin koruma altında olmasını
                    sağlar. Dünyanın en büyük ödeme altyapısı olan Stripe 3D Secure sistemi ile alışverişlerinizi gönül
                    rahatlığıyla tamamlayabilirsiniz.</p>
            </div>
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
                    <li><a href="#">Geri Dönüşler</a></li>
                    <li><a href="hakkımızda.php">Neden Fluffy?</a></li>
                    <li><a href="#">Kampanyalar</a></li>
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
</body>

</html> 