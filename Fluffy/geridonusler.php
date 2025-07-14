<?php
session_start();
require_once 'baglan.php';
require_once 'sepet_fonksiyon.php';
// Sepet sayısını hesapla
$sepet_sayisi = sepetSayisiGetir($db, $_SESSION['e_posta'] ?? null);
// Örnek yorumlar ve ürünler
$yorumlar = [
    [
        'kullanici' => 'Ayşe Y.',
        'yorum' => 'Peluş Fil harika! Çok yumuşak ve kaliteli, kızım bayıldı.',
        'urun_id' => 'fil',
    ],
    [
        'kullanici' => 'Mehmet K.',
        'yorum' => 'Peluş Kurt beklediğimden büyük ve çok sevimli. Tavsiye ederim.',
        'urun_id' => 'kurt',
    ],
    [
        'kullanici' => 'Elif D.',
        'yorum' => 'Peluş Pikachu tam bir Pokemon hayranı için! Renkleri çok canlı.',
        'urun_id' => 'pikachu',
    ],
    [
        'kullanici' => 'Can S.',
        'yorum' => 'Peluş Tavşan oğlumun yeni uyku arkadaşı oldu. Çok memnunuz.',
        'urun_id' => 'tavsan',
    ],
    [
        'kullanici' => 'Zeynep A.',
        'yorum' => 'Peluş Balık çok tatlı ve kaliteli. Hızlı kargo için teşekkürler.',
        'urun_id' => 'balik',
    ],
];
$urunler = [
    'aslan' => ['isim' => 'Peluş Aslan', 'resim' => 'resimler/Aslan.jpg'],
    'balik' => ['isim' => 'Peluş Balık', 'resim' => 'resimler/balık.webp'],
    'fil' => ['isim' => 'Peluş Fil', 'resim' => 'resimler/fil.webp'],
    'hamster' => ['isim' => 'Peluş Hamster', 'resim' => 'resimler/hamster.jpg'],
    'kapibara' => ['isim' => 'Peluş Kapibara', 'resim' => 'resimler/kapibara.jpg'],
    'koyun' => ['isim' => 'Peluş Koyun', 'resim' => 'resimler/koyun.jpg'],
    'kedi' => ['isim' => 'Peluş Kedi', 'resim' => 'resimler/peluskedi.jpg'],
    'kurt' => ['isim' => 'Peluş Kurt', 'resim' => 'resimler/peluskurt.jpg'],
    'maymun' => ['isim' => 'Peluş Maymun', 'resim' => 'resimler/pelusmaymun.jpg'],
    'midilli' => ['isim' => 'Peluş Midilli', 'resim' => 'resimler/pelusmidilli.png'],
    'penguen' => ['isim' => 'Peluş Penguen', 'resim' => 'resimler/peluspenguen.jpg'],
    'pikachu' => ['isim' => 'Peluş Pikachu', 'resim' => 'resimler/peluspikachu.webp'],
    'tavsan' => ['isim' => 'Peluş Tavşan', 'resim' => 'resimler/pelustavsan.jpg'],
    'tilki' => ['isim' => 'Peluş Tilki', 'resim' => 'resimler/tilki.webp'],
];
?>
<!DOCTYPE html>
<html lang="tr" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Geri Dönüşler - Fluffy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff4ec; }
        .yorumlar-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 16px;
        }
        .yorumlar-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 36px;
            letter-spacing: 1px;
        }
        .yorumlar-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
        }
        .yorum-kart {
            background: linear-gradient(120deg, #f7cac9 0%, #b2f7ef 100%);
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(106,130,251,0.10);
            padding: 32px 28px 24px 28px;
            max-width: 340px;
            min-width: 260px;
            flex: 1 1 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.18s, box-shadow 0.18s;
            position: relative;
        }
        .yorum-kart:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(106,130,251,0.18);
        }
        .yorum-urun-resim {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 14px;
        }
        .yorum-urun-adi {
            font-size: 1.1rem;
            font-weight: bold;
            color: #6a82fb;
            margin-bottom: 10px;
            text-align: center;
        }
        .yorum-icerik {
            font-size: 1.08rem;
            color: #333;
            margin-bottom: 18px;
            text-align: center;
        }
        .yorum-kullanici {
            color: #888;
            font-size: 1rem;
            font-style: italic;
            text-align: right;
            width: 100%;
        }
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
        @media (max-width: 700px) {
            .yorumlar-grid { flex-direction: column; gap: 18px; }
            .yorum-kart { max-width: 100%; min-width: 0; padding: 18px 10px; }
        }
        .logo-container img {
            margin-top: -30px;
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
    <div class="yorumlar-container">
        <div class="yorumlar-title">
            <i class="fa-solid fa-comments"></i> Geri Dönüşler
        </div>
        <div class="yorumlar-grid">
            <?php foreach ($yorumlar as $yorum):
                $urun = $urunler[$yorum['urun_id']];
            ?>
            <div class="yorum-kart">
                <img src="<?php echo $urun['resim']; ?>" alt="<?php echo $urun['isim']; ?>" class="yorum-urun-resim">
                <div class="yorum-urun-adi"><?php echo $urun['isim']; ?></div>
                <div class="yorum-icerik">“<?php echo htmlspecialchars($yorum['yorum']); ?>”</div>
                <div class="yorum-kullanici">- <?php echo htmlspecialchars($yorum['kullanici']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Yorumlar bölümü bitti, footer başlıyor -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Hakkımızda</h3>
                <p>Peluş ürünler konusunda uzmanlaşmış bir marka olarak, kalite ve sevimliliği bir araya getiriyoruz.</p>
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
</body>
</html> 