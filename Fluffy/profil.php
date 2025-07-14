<?php
session_start();
require_once 'baglan.php';
require_once 'sepet_fonksiyon.php';

// Sepet sayısını hesapla
$sepet_sayisi = sepetSayisiGetir($db, $_SESSION['e_posta'] ?? null);

// Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['e_posta'])) {
    header("Location: giris.php");
    exit;
}

// Kullanıcı bilgilerini çek
$kullanicisor = $db->prepare("SELECT * FROM uye WHERE e_posta = :e_posta");
$kullanicisor->execute(['e_posta' => $_SESSION['e_posta']]);
$kullanici = $kullanicisor->fetch(PDO::FETCH_ASSOC);

// Kullanıcının geçmiş siparişlerini çek
$siparissor = $db->prepare("SELECT * FROM siparisler WHERE e_posta = :e_posta ORDER BY siparis_tarihi DESC");
$siparissor->execute(['e_posta' => $_SESSION['e_posta']]);
$siparisler = $siparissor->fetchAll(PDO::FETCH_ASSOC);
$urunler = [
    'aslan' => 'Peluş Aslan',
    'balik' => 'Peluş Balık',
    'fil' => 'Peluş Fil',
    'hamster' => 'Peluş Hamster',
    'kapibara' => 'Peluş Kapibara',
    'koyun' => 'Peluş Koyun',
    'kedi' => 'Peluş Kedi',
    'kurt' => 'Peluş Kurt',
    'maymun' => 'Peluş Maymun',
    'midilli' => 'Peluş Midilli',
    'penguen' => 'Peluş Penguen',
    'pikachu' => 'Peluş Pikachu',
    'tavsan' => 'Peluş Tavşan',
    'tilki' => 'Peluş Tilki'
];

// Profil güncelleme işlemi
if (isset($_POST['profil_guncelle'])) {
    $guncelle = $db->prepare("UPDATE uye SET 
        ad = :ad,
        soyad = :soyad,
        yas = :yas,
        adres = :adres
        WHERE e_posta = :e_posta");
    
    $sonuc = $guncelle->execute([
        'ad' => $_POST['ad'],
        'soyad' => $_POST['soyad'],
        'yas' => $_POST['yas'],
        'adres' => $_POST['adres'],
        'e_posta' => $_SESSION['e_posta']
    ]);
    
    if ($sonuc) {
        $_SESSION['ad'] = $_POST['ad'];
        $_SESSION['soyad'] = $_POST['soyad'];
        $mesaj = "Profil bilgileriniz başarıyla güncellendi!";
        $mesaj_tipi = "success";
        
        // Güncel bilgileri çek
        $kullanicisor->execute(['e_posta' => $_SESSION['e_posta']]);
        $kullanici = $kullanicisor->fetch(PDO::FETCH_ASSOC);
    } else {
        $mesaj = "Profil güncellenirken bir hata oluştu!";
        $mesaj_tipi = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - Profilim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="giris.css">
    <link rel="stylesheet" href="ollama_chatbot.css" />
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
        
        /* Profil Sayfası Stilleri */
        .profil-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .profil-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .profil-header h1 {
            color: #333;
            font-family: 'Cabin', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .profil-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .profil-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .profil-sidebar {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        
        .profil-avatar {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profil-avatar i {
            font-size: 4rem;
            color: #ffb3e2;
            background: #f8f9fa;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .profil-info h3 {
            color: #333;
            font-family: 'Cabin', sans-serif;
            margin-bottom: 10px;
        }
        
        .profil-info p {
            color: #666;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .profil-main {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profil-section {
            margin-bottom: 30px;
        }
        
        .profil-section h2 {
            color: #333;
            font-family: 'Cabin', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #ffb3e2;
            padding-bottom: 10px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-row .form-group {
            margin-bottom: 0;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-family: 'Cabin', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-family: 'Cabin', sans-serif;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .profil-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .profil-header h1 {
                font-size: 2rem;
            }
        }
        .profil-main-flex {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }
        .profil-siparisler-col {
            flex: 2;
            min-width: 0;
        }
        .profil-bilgiler-col {
            flex: 1;
            min-width: 280px;
            max-width: 400px;
        }
        @media (max-width: 900px) {
            .profil-main-flex { flex-direction: column; gap: 24px; }
            .profil-bilgiler-col { max-width: 100%; }
        }
        .siparisler-kartlar {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 20px;
        }
        .siparis-kart {
            background: linear-gradient(120deg, #f7cac9 0%, #b2f7ef 100%);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(106,130,251,0.10);
            padding: 24px 32px;
            min-width: 260px;
            max-width: 340px;
            flex: 1 1 260px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: transform 0.18s, box-shadow 0.18s;
            position: relative;
        }
        .siparis-kart:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(106,130,251,0.18);
        }
        .siparis-urun-resim {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 10px;
            align-self: center;
        }
        .siparis-urun-adi {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
            text-align: center;
            width: 100%;
        }
        .siparis-detay {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            font-size: 1.05rem;
            margin-bottom: 4px;
        }
        .siparis-detay i {
            color: #6a82fb;
            margin-right: 4px;
        }
        @media (max-width: 700px) {
            .siparisler-kartlar { flex-direction: column; gap: 16px; }
            .siparis-kart { max-width: 100%; min-width: 0; padding: 18px 12px; }
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
            <a href="profil.php" class="login-btn">
                <i class="fa-solid fa-user"></i>
                Profilim
            </a>
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

    <div class="profil-container">
        <div class="profil-header">
            <h1>Profilim</h1>
            <p>Kişisel bilgilerinizi görüntüleyin ve güncelleyin</p>
        </div>

        <?php if (isset($mesaj)): ?>
            <div class="alert alert-<?php echo $mesaj_tipi; ?>">
                <?php echo $mesaj; ?>
            </div>
        <?php endif; ?>

        <div class="profil-content">
            <div class="profil-sidebar">
                <div class="profil-avatar">
                    <i class="fa-solid fa-user"></i>
                    <h3><?php echo $kullanici['ad'] . ' ' . $kullanici['soyad']; ?></h3>
                </div>
                <div class="profil-info">
                    <h3>Hesap Bilgileri</h3>
                    <p><strong>E-posta:</strong> <?php echo $kullanici['e_posta']; ?></p>
                    <p><strong>Üyelik Tarihi:</strong> <?php echo date('d.m.Y', strtotime('now')); ?></p>
                    <p><strong>Durum:</strong> <span style="color: #28a745;">Aktif</span></p>
                    <div style="margin-top: 20px;">
                        <a href="cikis.php" class="btn-secondary" style="width: 100%; text-align: center; background-color: #dc3545; border-color: #dc3545;">
                            <i class="fa-solid fa-sign-out-alt"></i>
                            Çıkış Yap
                        </a>
                    </div>
                </div>
            </div>

            <div class="profil-main">
                <div class="profil-section">
                    <h2>Kişisel Bilgiler</h2>
                    <form action="" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ad">Ad</label>
                                <input type="text" id="ad" name="ad" value="<?php echo $kullanici['ad']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="soyad">Soyad</label>
                                <input type="text" id="soyad" name="soyad" value="<?php echo $kullanici['soyad']; ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="yas">Yaş</label>
                                <input type="number" id="yas" name="yas" value="<?php echo $kullanici['yas']; ?>" min="18" required>
                            </div>
                            <div class="form-group">
                                <label for="e_posta">E-posta</label>
                                <input type="email" id="e_posta" value="<?php echo $kullanici['e_posta']; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="adres">Adres</label>
                            <textarea id="adres" name="adres" rows="4" required><?php echo $kullanici['adres']; ?></textarea>
                        </div>
                        <div class="btn-group">
                            <a href="index.php" class="btn-secondary">İptal</a>
                            <button type="submit" name="profil_guncelle" class="auth-button">Güncelle</button>
                        </div>
                    </form>
                </div>
                <div class="profil-section">
                    <h2>Geçmiş Siparişlerim</h2>
                    <?php if (count($siparisler) > 0): ?>
                        <div class="siparisler-kartlar">
                            <?php foreach ($siparisler as $siparis): ?>
                                <div class="siparis-kart">
                                    <?php
                                        $urun_id = $siparis['urun_id'];
                                        $urun_resim = '';
                                        switch ($urun_id) {
                                            case 'aslan': $urun_resim = 'resimler/Aslan.jpg'; break;
                                            case 'balik': $urun_resim = 'resimler/balık.webp'; break;
                                            case 'fil': $urun_resim = 'resimler/fil.webp'; break;
                                            case 'hamster': $urun_resim = 'resimler/hamster.jpg'; break;
                                            case 'kapibara': $urun_resim = 'resimler/kapibara.jpg'; break;
                                            case 'koyun': $urun_resim = 'resimler/koyun.jpg'; break;
                                            case 'kedi': $urun_resim = 'resimler/peluskedi.jpg'; break;
                                            case 'kurt': $urun_resim = 'resimler/peluskurt.jpg'; break;
                                            case 'maymun': $urun_resim = 'resimler/pelusmaymun.jpg'; break;
                                            case 'midilli': $urun_resim = 'resimler/pelusmidilli.png'; break;
                                            case 'penguen': $urun_resim = 'resimler/peluspenguen.jpg'; break;
                                            case 'pikachu': $urun_resim = 'resimler/peluspikachu.webp'; break;
                                            case 'tavsan': $urun_resim = 'resimler/pelustavsan.jpg'; break;
                                            case 'tilki': $urun_resim = 'resimler/tilki.webp'; break;
                                        }
                                    ?>
                                    <?php if ($urun_resim): ?>
                                        <img src="<?php echo $urun_resim; ?>" alt="<?php echo $urun_id; ?>" class="siparis-urun-resim">
                                    <?php endif; ?>
                                    <div class="siparis-urun-adi">
                                        <?php echo isset($urunler[$urun_id]) ? $urunler[$urun_id] : $urun_id; ?>
                                    </div>
                                    <div class="siparis-detay">
                                        <i class="fa-solid fa-box"></i> Adet: <b><?php echo $siparis['adet']; ?></b>
                                    </div>
                                    <div class="siparis-detay">
                                        <i class="fa-solid fa-calendar-days"></i> <?php echo $siparis['siparis_tarihi']; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="color:#888; font-size:1.1rem;">Henüz hiç siparişiniz yok.</div>
                    <?php endif; ?>
                </div>
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