<?php
require_once 'baglan.php';
require_once 'sepet_fonksiyon.php';
session_start();

// Sepete ekleme işlemi
if (isset($_POST['sepete_ekle']) && isset($_SESSION['e_posta'])) {
    $urun_id = $_POST['urun_id'];
    $adet = isset($_POST['adet']) ? (int)$_POST['adet'] : 1;
    
    // Önce bu ürünün sepette olup olmadığını kontrol et
    $kontrol = $db->prepare("SELECT * FROM sepet WHERE e_posta = :e_posta AND urun_id = :urun_id");
    $kontrol->execute(['e_posta' => $_SESSION['e_posta'], 'urun_id' => $urun_id]);
    $mevcut = $kontrol->fetch();
    
    if ($mevcut) {
        // Ürün zaten sepette varsa adetini artır
        $guncelle = $db->prepare("UPDATE sepet SET adet = adet + :adet WHERE e_posta = :e_posta AND urun_id = :urun_id");
        $guncelle->execute(['adet' => $adet, 'e_posta' => $_SESSION['e_posta'], 'urun_id' => $urun_id]);
    } else {
        // Yeni ürün ekle
        $ekle = $db->prepare("INSERT INTO sepet (e_posta, urun_id, adet) VALUES (:e_posta, :urun_id, :adet)");
        $ekle->execute(['e_posta' => $_SESSION['e_posta'], 'urun_id' => $urun_id, 'adet' => $adet]);
    }
    
    header("Location: urun_detay.php?id=" . $urun_id . "&durum=sepete_eklendi");
    exit;
}

// Ürün ID'sini URL'den al
$urun_id = isset($_GET['id']) ? $_GET['id'] : '';

// Ürün bilgilerini tanımla
$urunler = [
    'aslan' => [
        'id' => 'aslan',
        'isim' => 'Peluş Aslan',
        'fiyat' => '299.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/Aslan.jpg',
        'aciklama' => 'Yumuşak ve sevimli peluş aslan. Çocuklar için ideal oyuncak arkadaşı. Yüksek kaliteli malzemelerden üretilmiştir.',
        'boyut' => '25cm x 30cm',
        'renk' => 'Sarı-Kahverengi',
        'yas_grubu' => '3+ yaş',
        'stok' => '15 adet'
    ],
    'balik' => [
        'id' => 'balik',
        'isim' => 'Peluş Balık',
        'fiyat' => '349.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/balık.webp',
        'aciklama' => 'Renkli ve eğlenceli peluş balık. Su altı dünyasının en sevimli temsilcisi. Çocukların hayal gücünü geliştirir.',
        'boyut' => '20cm x 35cm',
        'renk' => 'Mavi-Turuncu',
        'yas_grubu' => '3+ yaş',
        'stok' => '12 adet'
    ],
    'fil' => [
        'id' => 'fil',
        'isim' => 'Peluş Fil',
        'fiyat' => '449.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/fil.webp',
        'aciklama' => 'Büyük ve yumuşak peluş fil. Gerçekçi detayları ile çocukların favorisi. Dayanıklı ve uzun ömürlü.',
        'boyut' => '35cm x 40cm',
        'renk' => 'Gri',
        'yas_grubu' => '3+ yaş',
        'stok' => '8 adet'
    ],
    'hamster' => [
        'id' => 'hamster',
        'isim' => 'Peluş Hamster',
        'fiyat' => '279.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/hamster.jpg',
        'aciklama' => 'Minik ve sevimli peluş hamster. Küçük boyutu ile her yere kolayca taşınabilir. Çocukların en yakın arkadaşı.',
        'boyut' => '15cm x 20cm',
        'renk' => 'Kahverengi-Beyaz',
        'yas_grubu' => '3+ yaş',
        'stok' => '20 adet'
    ],
    'kapibara' => [
        'id' => 'kapibara',
        'isim' => 'Peluş Kapibara',
        'fiyat' => '399.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/kapibara.jpg',
        'aciklama' => 'Sakin ve dost canlısı peluş kapibara. Dünyanın en büyük kemirgeni. Çocuklara huzur verir.',
        'boyut' => '30cm x 35cm',
        'renk' => 'Kahverengi',
        'yas_grubu' => '3+ yaş',
        'stok' => '10 adet'
    ],
    'koyun' => [
        'id' => 'koyun',
        'isim' => 'Peluş Koyun',
        'fiyat' => '329.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/koyun.jpg',
        'aciklama' => 'Yumuşak yünlü peluş koyun. Çiftlik hayvanlarının en sevimlisi. Çocukların uyku arkadaşı.',
        'boyut' => '25cm x 30cm',
        'renk' => 'Beyaz',
        'yas_grubu' => '3+ yaş',
        'stok' => '18 adet'
    ],
    'kedi' => [
        'id' => 'kedi',
        'isim' => 'Peluş Kedi',
        'fiyat' => '299.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/peluskedi.jpg',
        'aciklama' => 'Sevimli ve oyuncu peluş kedi. Evcil hayvan sevgisini aşılar. Çocukların en yakın dostu.',
        'boyut' => '20cm x 25cm',
        'renk' => 'Turuncu-Beyaz',
        'yas_grubu' => '3+ yaş',
        'stok' => '22 adet'
    ],
    'kurt' => [
        'id' => 'kurt',
        'isim' => 'Peluş Kurt',
        'fiyat' => '479.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/peluskurt.jpg',
        'aciklama' => 'Güçlü ve asil peluş kurt. Vahşi doğanın en güzel temsilcisi. Çocuklara cesaret verir.',
        'boyut' => '30cm x 35cm',
        'renk' => 'Gri-Siyah',
        'yas_grubu' => '3+ yaş',
        'stok' => '6 adet'
    ],
    'maymun' => [
        'id' => 'maymun',
        'isim' => 'Peluş Maymun',
        'fiyat' => '369.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/pelusmaymun.jpg',
        'aciklama' => 'Eğlenceli ve akıllı peluş maymun. Ormanın en neşeli hayvanı. Çocukları güldürür.',
        'boyut' => '25cm x 30cm',
        'renk' => 'Kahverengi',
        'yas_grubu' => '3+ yaş',
        'stok' => '14 adet'
    ],
    'midilli' => [
        'id' => 'midilli',
        'isim' => 'Peluş Midilli',
        'fiyat' => '429.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/pelusmidilli.png',
        'aciklama' => 'Küçük ve sevimli peluş midilli. At sevgisini aşılar. Çocukların hayal dünyasını zenginleştirir.',
        'boyut' => '28cm x 32cm',
        'renk' => 'Kahverengi',
        'yas_grubu' => '3+ yaş',
        'stok' => '9 adet'
    ],
    'penguen' => [
        'id' => 'penguen',
        'isim' => 'Peluş Penguen',
        'fiyat' => '289.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/peluspenguen.jpg',
        'aciklama' => 'Sevimli ve komik peluş penguen. Antarktika\'nın en sevimli sakinleri. Çocukları eğlendirir.',
        'boyut' => '22cm x 28cm',
        'renk' => 'Siyah-Beyaz',
        'yas_grubu' => '3+ yaş',
        'stok' => '16 adet'
    ],
    'pikachu' => [
        'id' => 'pikachu',
        'isim' => 'Peluş Pikachu',
        'fiyat' => '499.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/peluspikachu.webp',
        'aciklama' => 'Pokemon dünyasının en sevilen karakteri Pikachu. Elektrik gücü ile çocukları büyüler.',
        'boyut' => '25cm x 30cm',
        'renk' => 'Sarı',
        'yas_grubu' => '3+ yaş',
        'stok' => '5 adet'
    ],
    'tavsan' => [
        'id' => 'tavsan',
        'isim' => 'Peluş Tavşan',
        'fiyat' => '259.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/pelustavsan.jpg',
        'aciklama' => 'Uzun kulaklı sevimli peluş tavşan. Baharın en güzel habercisi. Çocukların favorisi.',
        'boyut' => '18cm x 25cm',
        'renk' => 'Beyaz-Gri',
        'yas_grubu' => '3+ yaş',
        'stok' => '25 adet'
    ],
    'tilki' => [
        'id' => 'tilki',
        'isim' => 'Peluş Tilki',
        'fiyat' => '389.90 TL',
        'indirim' => '30% İndirim',
        'resim' => 'resimler/tilki.webp',
        'aciklama' => 'Zeki ve hızlı peluş tilki. Ormanın en akıllı hayvanı. Çocuklara zeka katar.',
        'boyut' => '24cm x 30cm',
        'renk' => 'Turuncu-Beyaz',
        'yas_grubu' => '3+ yaş',
        'stok' => '11 adet'
    ]
];

// Ürün bulunamadıysa ana sayfaya yönlendir
if (!isset($urunler[$urun_id])) {
    header("Location: urunler.php");
    exit;
}

$urun = $urunler[$urun_id];

// Yorumları çek
$yorumlarsor = $db->prepare("SELECT y.*, u.ad, u.soyad FROM yorumlar y 
                            LEFT JOIN uye u ON y.e_posta = u.e_posta 
                            WHERE y.urun_id = :urun_id 
                            ORDER BY y.tarih DESC");
$yorumlarsor->execute(['urun_id' => $urun_id]);
$yorumlar = $yorumlarsor->fetchAll(PDO::FETCH_ASSOC);

// Yorum ekleme işlemi
if (isset($_POST['yorum_ekle']) && isset($_SESSION['e_posta'])) {
    $yorum = trim($_POST['yorum']);
    
    if (!empty($yorum)) {
    $yorumkaydet = $db->prepare("INSERT INTO yorumlar (urun_id, e_posta, yorum, tarih) 
                                VALUES (:urun_id, :e_posta, :yorum, NOW())");
    $kaydet = $yorumkaydet->execute([
        'urun_id' => $urun_id,
            'e_posta' => $_SESSION['e_posta'],
        'yorum' => $yorum
    ]);
    
    if ($kaydet) {
        header("Location: urun_detay.php?id=" . $urun_id . "&durum=ok");
        exit;
    }
}
}

// Sepet sayısını hesapla
$sepet_sayisi = sepetSayisiGetir($db, $_SESSION['e_posta'] ?? null);

// --- Stok bilgisini veritabanından çek ---
$stok_db = null;
if ($urun_id) {
    $sorgu = $db->prepare("SELECT stok FROM urunler WHERE id = :id LIMIT 1");
    $sorgu->execute(['id' => $urun_id]);
    $stok_kayit = $sorgu->fetch(PDO::FETCH_ASSOC);
    if ($stok_kayit) {
        $stok_db = $stok_kayit['stok'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - <?php echo $urun['isim']; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .urun-detay-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .urun-detay-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-bottom: 50px;
        }

        .urun-image-section {
            text-align: center;
        }

        .urun-image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .urun-info-section {
            padding: 20px;
        }

        .urun-baslik {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 15px;
            font-family: 'Cabin', sans-serif;
        }

        .urun-fiyat {
            font-size: 2rem;
            color: #ffb3e2;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .urun-aciklama {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 30px;
        }

        .urun-ozellikler {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .ozellik-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .ozellik-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .ozellik-label {
            font-weight: 600;
            color: #333;
        }

        .ozellik-deger {
            color: #666;
        }

        .stok-durumu {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .buton-grup {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .sepet-btn {
            background: #ffb3e2;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
        }

        .sepet-btn:hover {
            background: #ff8cd1;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 179, 226, 0.3);
        }

        .geri-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .geri-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .urun-detay-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .urun-baslik {
                font-size: 2rem;
            }

            .buton-grup {
                flex-direction: column;
            }
        }

        /* Yorumlar Stilleri */
        .yorumlar-section {
            margin-top: 50px;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .yorumlar-baslik {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 25px;
            font-family: 'Cabin', sans-serif;
            border-bottom: 2px solid #ffb3e2;
            padding-bottom: 10px;
        }

        .yorum-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .yorum-form h3 {
            color: #333;
            margin-bottom: 15px;
            font-family: 'Cabin', sans-serif;
        }

        .yorum-textarea {
            width: 100%;
            min-height: 100px;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-family: 'Cabin', sans-serif;
            font-size: 1rem;
            resize: vertical;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .yorum-textarea:focus {
            border-color: #ffb3e2;
        }

        .yorum-gonder-btn {
            background: #ffb3e2;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .yorum-gonder-btn:hover {
            background: #ff8cd1;
            transform: translateY(-2px);
        }

        .yorum-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #ffb3e2;
        }

        .yorum-baslik {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .yorum-yazan {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .yorum-tarih {
            color: #666;
            font-size: 0.9rem;
        }

        .yorum-icerik {
            color: #444;
            line-height: 1.6;
            font-size: 1rem;
        }

        .yorum-yok {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 30px;
        }

        .giris-uyari {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .giris-uyari a {
            color: #ffb3e2;
            text-decoration: none;
            font-weight: 600;
        }

        .giris-uyari a:hover {
            text-decoration: underline;
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

    <div class="urun-detay-container">
        <a href="urunler.php" class="geri-btn">
            <i class="fa-solid fa-arrow-left"></i> Ürünlere Geri Dön
        </a>
                
                <?php if (isset($_GET['durum']) && $_GET['durum'] == "ok"): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i> Yorumunuz başarıyla eklendi!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['durum']) && $_GET['durum'] == "sepete_eklendi"): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i> Ürün sepetinize başarıyla eklendi!
            </div>
                <?php endif; ?>

        <div class="urun-detay-content">
            <div class="urun-image-section">
                <img src="<?php echo $urun['resim']; ?>" alt="<?php echo $urun['isim']; ?>">
            </div>

            <div class="urun-info-section">
                <h1 class="urun-baslik"><?php echo $urun['isim']; ?></h1>
                <p class="urun-fiyat">
                    <?php
                    if (isset($_SESSION['e_posta']) && isset($_SESSION['kampanya_onerilenler']) && in_array($urun['id'], $_SESSION['kampanya_onerilenler'])) {
                        $fiyat = floatval(str_replace([',', ' TL'], ['.', ''], $urun['fiyat']));
                        $indirimli = $fiyat * 0.8;
                        echo '<span style="font-size:1.2rem;color:#888;text-decoration:line-through;margin-right:10px;">' . number_format($fiyat, 2, ',', '.') . ' TL</span>';
                        echo '<span style="font-size:2rem;color:#ffb3e2;font-weight:bold;">' . number_format($indirimli, 2, ',', '.') . ' TL</span>';
                    } else {
                        echo $urun['fiyat'];
                    }
                    ?>
                </p>
                <p class="urun-aciklama"><?php echo $urun['aciklama']; ?></p>
                <div class="stok-durumu">
                    <i class="fa-solid fa-check-circle"></i> Stokta <?php echo ($stok_db !== null ? $stok_db . ' adet' : $urun['stok']); ?>
                </div>
                <div class="urun-ozellikler">
                    <h3>Ürün Özellikleri</h3>
                    <div class="ozellik-item">
                        <span class="ozellik-label">Boyut:</span>
                        <span class="ozellik-deger"><?php echo $urun['boyut']; ?></span>
                    </div>
                    <div class="ozellik-item">
                        <span class="ozellik-label">Renk:</span>
                        <span class="ozellik-deger"><?php echo $urun['renk']; ?></span>
                    </div>
                    <div class="ozellik-item">
                        <span class="ozellik-label">Yaş Grubu:</span>
                        <span class="ozellik-deger"><?php echo $urun['yas_grubu']; ?></span>
                    </div>
                </div>

                <div class="buton-grup">
                    <?php if(isset($_SESSION['e_posta'])): ?>
                        <!-- Giriş yapmış kullanıcılar için sepet butonu -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="urun_id" value="<?php echo $urun['id']; ?>">
                            <input type="hidden" name="adet" value="1">
                            <button type="submit" name="sepete_ekle" class="sepet-btn">
                                <i class="fa-solid fa-cart-plus"></i> Sepete Ekle
                            </button>
                        </form>
                    <?php else: ?>
                        <!-- Giriş yapmamış kullanıcılar için uyarı -->
                        <div class="giris-uyari" style="margin-top: 20px;">
                            <i class="fa-solid fa-info-circle"></i> 
                            Sepete ürün ekleyebilmek için lütfen <a href="giris.php">giriş yapın</a>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Yorumlar Bölümü -->
        <div class="yorumlar-section">
            <h2 class="yorumlar-baslik">
                <i class="fa-solid fa-comments"></i> Müşteri Yorumları
            </h2>

            <?php if(isset($_SESSION['e_posta'])): ?>
                <!-- Giriş yapmış kullanıcılar için yorum formu -->
                <div class="yorum-form">
                    <h3>Yorum Yap</h3>
                    <form action="" method="POST">
                        <textarea name="yorum" class="yorum-textarea" placeholder="Bu ürün hakkında düşüncelerinizi paylaşın..." required></textarea>
                        <button type="submit" name="yorum_ekle" class="yorum-gonder-btn">
                            <i class="fa-solid fa-paper-plane"></i> Yorum Gönder
                        </button>
                        </form>
                    </div>
                <?php else: ?>
                <!-- Giriş yapmamış kullanıcılar için uyarı -->
                <div class="giris-uyari">
                    <i class="fa-solid fa-info-circle"></i> 
                    Yorum yapabilmek için lütfen <a href="giris.php">giriş yapın</a>.
                </div>
                <?php endif; ?>

            <!-- Mevcut yorumlar -->
                <div class="yorumlar-listesi">
                <?php if (count($yorumlar) > 0): ?>
                    <?php foreach ($yorumlar as $yorum): ?>
                        <div class="yorum-item">
                            <div class="yorum-baslik">
                                <span class="yorum-yazan"><?php echo htmlspecialchars($yorum['ad'] . ' ' . $yorum['soyad']); ?></span>
                                <span class="yorum-tarih"><?php echo date('d.m.Y H:i', strtotime($yorum['tarih'])); ?></span>
                            </div>
                            <div class="yorum-icerik">
                                <?php echo nl2br(htmlspecialchars($yorum['yorum'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="yorum-yok">
                        <i class="fa-solid fa-comment-slash"></i>
                        Henüz yorum yapılmamış. İlk yorumu siz yapın!
                    </div>
                <?php endif; ?>
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