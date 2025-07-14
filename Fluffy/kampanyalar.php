<?php
session_start();
require_once 'baglan.php';
require_once 'sepet_fonksiyon.php';

// Ürünler dizisi (urun_detay.php ile aynı id'ler)
$urunler = [
    'aslan' => [ 'isim' => 'Peluş Aslan', 'resim' => 'resimler/Aslan.jpg', 'fiyat' => '299.90 TL' ],
    'balik' => [ 'isim' => 'Peluş Balık', 'resim' => 'resimler/balık.webp', 'fiyat' => '349.90 TL' ],
    'fil' => [ 'isim' => 'Peluş Fil', 'resim' => 'resimler/fil.webp', 'fiyat' => '449.90 TL' ],
    'hamster' => [ 'isim' => 'Peluş Hamster', 'resim' => 'resimler/hamster.jpg', 'fiyat' => '279.90 TL' ],
    'kapibara' => [ 'isim' => 'Peluş Kapibara', 'resim' => 'resimler/kapibara.jpg', 'fiyat' => '399.90 TL' ],
    'koyun' => [ 'isim' => 'Peluş Koyun', 'resim' => 'resimler/koyun.jpg', 'fiyat' => '329.90 TL' ],
    'kedi' => [ 'isim' => 'Peluş Kedi', 'resim' => 'resimler/peluskedi.jpg', 'fiyat' => '299.90 TL' ],
    'kurt' => [ 'isim' => 'Peluş Kurt', 'resim' => 'resimler/peluskurt.jpg', 'fiyat' => '479.90 TL' ],
    'maymun' => [ 'isim' => 'Peluş Maymun', 'resim' => 'resimler/pelusmaymun.jpg', 'fiyat' => '369.90 TL' ],
    'midilli' => [ 'isim' => 'Peluş Midilli', 'resim' => 'resimler/pelusmidilli.png', 'fiyat' => '429.90 TL' ],
    'penguen' => [ 'isim' => 'Peluş Penguen', 'resim' => 'resimler/peluspenguen.jpg', 'fiyat' => '289.90 TL' ],
    'pikachu' => [ 'isim' => 'Peluş Pikachu', 'resim' => 'resimler/peluspikachu.webp', 'fiyat' => '499.90 TL' ],
    'tavsan' => [ 'isim' => 'Peluş Tavşan', 'resim' => 'resimler/pelustavsan.jpg', 'fiyat' => '259.90 TL' ],
    'tilki' => [ 'isim' => 'Peluş Tilki', 'resim' => 'resimler/tilki.webp', 'fiyat' => '389.90 TL' ],
];

// Kullanıcıya özel öneri: daha önce almadığı ürünlerden rastgele 4 öner
$oneriler = [];
if (isset($_SESSION['e_posta'])) {
    // Kampanya önerileri veritabanında saklanıyor mu kontrol et
    $kampanya_sor = $db->prepare("SELECT urunler FROM kampanya_oneriler WHERE e_posta = :e_posta LIMIT 1");
    $kampanya_sor->execute(['e_posta' => $_SESSION['e_posta']]);
    $kampanya_kayit = $kampanya_sor->fetch(PDO::FETCH_ASSOC);
    if ($kampanya_kayit && !empty($kampanya_kayit['urunler'])) {
        $oneriler = explode(',', $kampanya_kayit['urunler']);
    } else {
        $siparissor = $db->prepare("SELECT urun_id FROM siparisler WHERE e_posta = :e_posta");
        $siparissor->execute(['e_posta' => $_SESSION['e_posta']]);
        $alinanlar = $siparissor->fetchAll(PDO::FETCH_COLUMN);
        $kalan_urunler = array_diff(array_keys($urunler), $alinanlar);
        if (count($kalan_urunler) < 4) {
            $oneriler = $kalan_urunler;
        } else {
            $oneriler = array_rand(array_flip($kalan_urunler), 4);
        }
        if (!is_array($oneriler)) $oneriler = [$oneriler];
        // Veritabanına kaydet
        $kayitla = $db->prepare("INSERT INTO kampanya_oneriler (e_posta, urunler) VALUES (:e_posta, :urunler)");
        $kayitla->execute([
            'e_posta' => $_SESSION['e_posta'],
            'urunler' => implode(',', $oneriler)
        ]);
    }
    $_SESSION['kampanya_onerilenler'] = $oneriler;
} else {
    // Giriş yoksa rastgele 4 ürün öner
    $oneriler = array_rand($urunler, 4);
    if (!is_array($oneriler)) $oneriler = [$oneriler];
}
// Kampanya önerilen ürünleri session'a kaydet (her kullanıcıya bir kez)
if (isset($_SESSION['e_posta'])) {
    $_SESSION['kampanya_son_oneriler'] = $oneriler;
}
?>
<!DOCTYPE html>
<html lang="tr" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Kampanyalar & Öneriler - Fluffy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <style>
        .kampanya-baslik {
            text-align: center;
            font-size: 2.5rem;
            color: #ffb3e2;
            margin-top: 40px;
            font-family: 'Cabin', sans-serif;
            font-weight: bold;
        }
        .kampanya-aciklama {
            text-align: center;
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }
        .oneriler-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
            margin-bottom: 60px;
        }
        .onerilen-urun-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(106,130,251,0.10);
            padding: 32px 28px 24px 28px;
            max-width: 320px;
            min-width: 220px;
            flex: 1 1 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.18s, box-shadow 0.18s;
            position: relative;
        }
        .onerilen-urun-card:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(106,130,251,0.18);
        }
        .onerilen-urun-resim {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 12px;
            background: #fff4ec;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 14px;
        }
        .onerilen-urun-adi {
            font-size: 1.2rem;
            font-weight: bold;
            color: #6a82fb;
            margin-bottom: 10px;
            text-align: center;
        }
        .onerilen-urun-fiyat {
            color: #ffb3e2;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 18px;
        }
        .goz-at-btn {
            background: white;
            color: #ffb3e2;
            border: 2px solid #ffb3e2;
            padding: 10px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            font-family: 'Cabin', sans-serif;
            text-decoration: none;
            display: inline-block;
        }
        .goz-at-btn:hover {
            background: #ffb3e2;
            color: white;
            transform: scale(1.1);
            text-decoration: none;
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
                <a href="profil.php" class="login-btn">
                    <i class="fa-solid fa-user"></i>
                    Profilim
                </a>
            <?php else: ?>
                <a href="giris.php" class="login-btn desktop-only">
                    <i class="fa-solid fa-user"></i>
                    Giriş Yap
                </a>
            <?php endif; ?>
            <a href="sepet.php" class="market">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if(isset($sepet_sayisi) && $sepet_sayisi > 0): ?>
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

    <div class="arka1">
        <p>Sizin için seçtiğimiz özel ürünler!</p>
        <img src="resimler/Arkaplan5.png" alt="">
    </div>

    <div class="kampanya-baslik">Kampanyalar & Öneriler</div>
    <div class="kampanya-aciklama">
        <?php if (isset($_SESSION['e_posta'])): ?>
            Bu 4 ürün sadece sana özel %20 indirimli!
        <?php else: ?>
            Kayıt ol ve sana özel 4 üründen indirimi yakala!
        <?php endif; ?>
    </div>
    <div class="oneriler-wrapper">
        <?php foreach ($oneriler as $urun_id): $urun = $urunler[$urun_id];
            if (isset($_SESSION['e_posta']) && isset($_SESSION['kampanya_onerilenler']) && in_array($urun_id, $_SESSION['kampanya_onerilenler'])) {
                $fiyat = floatval(str_replace([',', ' TL'], ['.', ''], $urun['fiyat']));
                $indirimli_fiyat = $fiyat * 0.8;
            ?>
                <div class="onerilen-urun-card">
                    <img src="<?php echo $urun['resim']; ?>" alt="<?php echo $urun['isim']; ?>" class="onerilen-urun-resim">
                    <div class="onerilen-urun-adi"><?php echo $urun['isim']; ?></div>
                    <div class="onerilen-urun-fiyat">
                        <span style="font-size:0.95rem;color:#888;text-decoration:line-through;margin-right:8px;">
                            <?php echo number_format($fiyat, 2, ',', '.') . ' TL'; ?>
                        </span>
                        <span style="font-size:1.25rem;color:#ffb3e2;font-weight:bold;">
                            <?php echo number_format($indirimli_fiyat, 2, ',', '.') . ' TL'; ?>
                        </span>
                    </div>
                    <a href="urun_detay.php?id=<?php echo $urun_id; ?>" class="goz-at-btn">Göz At</a>
                </div>
            <?php } else { ?>
                <div class="onerilen-urun-card">
                    <img src="<?php echo $urun['resim']; ?>" alt="<?php echo $urun['isim']; ?>" class="onerilen-urun-resim">
                    <div class="onerilen-urun-adi"><?php echo $urun['isim']; ?></div>
                    <div class="onerilen-urun-fiyat"><?php echo $urun['fiyat']; ?></div>
                    <a href="urun_detay.php?id=<?php echo $urun_id; ?>" class="goz-at-btn">Göz At</a>
                </div>
            <?php } ?>
        <?php endforeach; ?>
    </div>

    <div class="arka2">
        <p>Sana arkadaşlık yapacak bir Peluş seç</p>
        <img src="resimler/Arkaplan3.png" alt="">
    </div>

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