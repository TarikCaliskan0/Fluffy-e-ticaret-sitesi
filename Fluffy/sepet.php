<?php
session_start();

// Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['e_posta'])) {
    header("Location: giris.php");
    exit;
}

require_once 'baglan.php';

// Ürün bilgilerini tanımla (urun_detay.php'den kopyalandı)
$urunler = [
    'aslan' => [
        'id' => 'aslan',
        'isim' => 'Peluş Aslan',
        'fiyat' => '299.90',
        'resim' => 'resimler/Aslan.jpg'
    ],
    'balik' => [
        'id' => 'balik',
        'isim' => 'Peluş Balık',
        'fiyat' => '349.90',
        'resim' => 'resimler/balık.webp'
    ],
    'fil' => [
        'id' => 'fil',
        'isim' => 'Peluş Fil',
        'fiyat' => '449.90',
        'resim' => 'resimler/fil.webp'
    ],
    'hamster' => [
        'id' => 'hamster',
        'isim' => 'Peluş Hamster',
        'fiyat' => '279.90',
        'resim' => 'resimler/hamster.jpg'
    ],
    'kapibara' => [
        'id' => 'kapibara',
        'isim' => 'Peluş Kapibara',
        'fiyat' => '399.90',
        'resim' => 'resimler/kapibara.jpg'
    ],
    'koyun' => [
        'id' => 'koyun',
        'isim' => 'Peluş Koyun',
        'fiyat' => '329.90',
        'resim' => 'resimler/koyun.jpg'
    ],
    'kedi' => [
        'id' => 'kedi',
        'isim' => 'Peluş Kedi',
        'fiyat' => '299.90',
        'resim' => 'resimler/peluskedi.jpg'
    ],
    'kurt' => [
        'id' => 'kurt',
        'isim' => 'Peluş Kurt',
        'fiyat' => '479.90',
        'resim' => 'resimler/peluskurt.jpg'
    ],
    'maymun' => [
        'id' => 'maymun',
        'isim' => 'Peluş Maymun',
        'fiyat' => '369.90',
        'resim' => 'resimler/pelusmaymun.jpg'
    ],
    'midilli' => [
        'id' => 'midilli',
        'isim' => 'Peluş Midilli',
        'fiyat' => '429.90',
        'resim' => 'resimler/pelusmidilli.png'
    ],
    'penguen' => [
        'id' => 'penguen',
        'isim' => 'Peluş Penguen',
        'fiyat' => '289.90',
        'resim' => 'resimler/peluspenguen.jpg'
    ],
    'pikachu' => [
        'id' => 'pikachu',
        'isim' => 'Peluş Pikachu',
        'fiyat' => '499.90',
        'resim' => 'resimler/peluspikachu.webp'
    ],
    'tavsan' => [
        'id' => 'tavsan',
        'isim' => 'Peluş Tavşan',
        'fiyat' => '259.90',
        'resim' => 'resimler/pelustavsan.jpg'
    ],
    'tilki' => [
        'id' => 'tilki',
        'isim' => 'Peluş Tilki',
        'fiyat' => '389.90',
        'resim' => 'resimler/tilki.webp'
    ]
];

// Kampanya önerilen ürünler (her kullanıcıya bir kez)
if (!isset($_SESSION['kampanya_onerilenler'])) {
    // Kampanyalar sayfasında önerilen ürünler session'a kaydedilmişse onu kullan
    if (isset($_SESSION['kampanya_son_oneriler']) && is_array($_SESSION['kampanya_son_oneriler'])) {
        $_SESSION['kampanya_onerilenler'] = $_SESSION['kampanya_son_oneriler'];
    } else {
        $_SESSION['kampanya_onerilenler'] = [];
    }
}
$kampanya_onerilenler = $_SESSION['kampanya_onerilenler'];

// Sepetten ürün silme işlemi
if (isset($_POST['urun_sil'])) {
    $sepet_id = $_POST['sepet_id'];
    $sil = $db->prepare("DELETE FROM sepet WHERE id = :id AND e_posta = :e_posta");
    $sil->execute(['id' => $sepet_id, 'e_posta' => $_SESSION['e_posta']]);
    
    header("Location: sepet.php?durum=silindi");
    exit;
}

// Adet güncelleme işlemi
if (isset($_POST['adet_guncelle'])) {
    $sepet_id = $_POST['sepet_id'];
    $yeni_adet = $_POST['yeni_adet'];
    
    if ($yeni_adet > 0) {
        $guncelle = $db->prepare("UPDATE sepet SET adet = :adet WHERE id = :id AND e_posta = :e_posta");
        $guncelle->execute(['adet' => $yeni_adet, 'id' => $sepet_id, 'e_posta' => $_SESSION['e_posta']]);
    } else {
        // Adet 0 veya negatifse ürünü sil
        $sil = $db->prepare("DELETE FROM sepet WHERE id = :id AND e_posta = :e_posta");
        $sil->execute(['id' => $sepet_id, 'e_posta' => $_SESSION['e_posta']]);
    }
    
    header("Location: sepet.php?durum=guncellendi");
    exit;
}

// Sepetteki ürünleri çek
$sepetsor = $db->prepare("SELECT * FROM sepet WHERE e_posta = :e_posta ORDER BY eklenme_tarihi DESC");
$sepetsor->execute(['e_posta' => $_SESSION['e_posta']]);
$sepet_urunleri = $sepetsor->fetchAll(PDO::FETCH_ASSOC);

// Siparişi tamamlama işlemi
$siparis_tamamlandi = false;
$siparis_urunleri = [];
if (isset($_POST['siparisi_tamamla'])) {
    // Sepetteki ürünleri siparisler tablosuna ekle
    foreach ($sepet_urunleri as $sepet_urun) {
        $ekle = $db->prepare("INSERT INTO siparisler (e_posta, urun_id, adet) VALUES (:e_posta, :urun_id, :adet)");
        $ekle->execute([
            'e_posta' => $_SESSION['e_posta'],
            'urun_id' => $sepet_urun['urun_id'],
            'adet' => $sepet_urun['adet']
        ]);
        $siparis_urunleri[] = [
            'urun_id' => $sepet_urun['urun_id'],
            'adet' => $sepet_urun['adet']
        ];
        // --- Stok azalt ---
        $stok_guncelle = $db->prepare("UPDATE urunler SET stok = GREATEST(stok - :adet, 0) WHERE id = :id");
        $stok_guncelle->execute([
            'adet' => $sepet_urun['adet'],
            'id' => $sepet_urun['urun_id']
        ]);
    }
    // Sepeti temizle
    $sil = $db->prepare("DELETE FROM sepet WHERE e_posta = :e_posta");
    $sil->execute(['e_posta' => $_SESSION['e_posta']]);
    $siparis_tamamlandi = true;
    // Sepet ürünlerini tekrar boşalt
    $sepet_urunleri = [];
}

// Toplam hesapla
$toplam_fiyat = 0;
$toplam_adet = 0;
foreach ($sepet_urunleri as $sepet_urun) {
    if (isset($urunler[$sepet_urun['urun_id']])) {
        $urun = $urunler[$sepet_urun['urun_id']];
        $fiyat = $urun['fiyat'];
        if (in_array($sepet_urun['urun_id'], $kampanya_onerilenler)) {
            $fiyat = $fiyat * 0.8;
        }
        $toplam_fiyat += $fiyat * $sepet_urun['adet'];
        $toplam_adet += $sepet_urun['adet'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - Sepetim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .sepet-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .sepet-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .sepet-header h1 {
            color: #333;
            font-family: 'Cabin', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .sepet-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .sepet-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sepet-bos {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .sepet-bos i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .sepet-bos h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .sepet-bos p {
            margin-bottom: 30px;
        }

        .alisverise-devam-btn {
            background: #ffb3e2;
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

        .alisverise-devam-btn:hover {
            background: #ff8cd1;
            transform: translateY(-2px);
        }

        .sepet-urun {
            display: grid;
            grid-template-columns: 100px 2fr 1fr 1fr 1fr auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        .sepet-urun:hover {
            background-color: #f8f9fa;
        }

        .sepet-urun:last-child {
            border-bottom: none;
        }

        .urun-resim {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
        }

        .urun-resim img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .urun-bilgi h3 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .urun-bilgi p {
            color: #666;
            font-size: 0.9rem;
        }

        .urun-fiyat {
            color: #ffb3e2;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .adet-kontrol {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .adet-btn {
            background: #f8f9fa;
            border: 1px solid #ddd;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .adet-btn:hover {
            background: #ffb3e2;
            color: white;
        }

        .adet-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            font-size: 1rem;
        }

        .toplam-fiyat {
            color: #ffb3e2;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .urun-sil-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .urun-sil-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .sepet-ozet {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .ozet-satir {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .ozet-satir:last-child {
            border-top: 2px solid #ffb3e2;
            padding-top: 15px;
            margin-bottom: 0;
            font-weight: bold;
            font-size: 1.3rem;
        }

        .satin-al-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .satin-al-btn:hover {
            background: #218838;
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

        .siparis-tamamla-btn {
            background: linear-gradient(90deg, #ffb3e2 0%, #6a82fb 100%);
            color: #fff;
            font-size: 1.5rem;
            padding: 15px 40px;
            border-radius: 10px;
            font-weight: bold;
            border: none;
            box-shadow: 0 2px 12px rgba(106,130,251,0.12);
            transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
            outline: none;
            cursor: pointer;
            letter-spacing: 1px;
        }
        .siparis-tamamla-btn:hover, .siparis-tamamla-btn:focus {
            background: linear-gradient(90deg, #6a82fb 0%, #ffb3e2 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 6px 24px rgba(106,130,251,0.18);
        }
        .siparis-basarili {
            background: linear-gradient(90deg, #b2f7ef 0%, #f7cac9 100%);
            color: #333;
            border: none;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .sepet-urun {
                grid-template-columns: 80px 1fr;
                gap: 15px;
                padding: 15px;
            }

            .urun-bilgi, .urun-fiyat, .adet-kontrol, .toplam-fiyat, .urun-sil-btn {
                grid-column: 2;
            }

            .urun-bilgi {
                grid-row: 1;
            }

            .urun-fiyat {
                grid-row: 2;
            }

            .adet-kontrol {
                grid-row: 3;
            }

            .toplam-fiyat {
                grid-row: 4;
            }

            .urun-sil-btn {
                grid-row: 5;
                justify-self: start;
            }
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
            <li><a href="profil.php" class="login-btn-mobile"><i class="fa-solid fa-user"></i> Profilim</a></li>
        </div>
    </div>

    <div class="sepet-container">
        <div class="sepet-header">
            <h1><i class="fa-solid fa-shopping-cart"></i> Sepetim</h1>
            <p>Alışveriş sepetinizdeki ürünleri yönetin</p>
        </div>

        <?php if (isset($_GET['durum'])): ?>
            <?php if ($_GET['durum'] == 'silindi'): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle"></i> Ürün sepetten kaldırıldı!
                </div>
            <?php elseif ($_GET['durum'] == 'guncellendi'): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle"></i> Sepet güncellendi!
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($siparis_tamamlandi): ?>
            <div class="alert alert-success siparis-basarili" style="margin-bottom:30px; text-align:center; font-size:1.3rem; font-weight:bold;">
                Siparişiniz tamamlandı.
            </div>
        <?php endif; ?>

        <div class="sepet-content">
            <?php if (count($sepet_urunleri) == 0): ?>
                <div class="sepet-bos">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <h3>Sepetiniz Boş</h3>
                    <p>Henüz sepetinize ürün eklemediniz.</p>
                    <a href="urunler.php" class="alisverise-devam-btn">
                        <i class="fa-solid fa-shopping-bag"></i> Alışverişe Devam Et
                    </a>
                </div>
            <?php else: ?>
                <!-- Sepet başlığı -->
                <div class="sepet-urun" style="border-bottom: 2px solid #ffb3e2; font-weight: bold; color: #333;">
                    <div></div>
                    <div>Ürün</div>
                    <div>Fiyat</div>
                    <div>Adet</div>
                    <div>Toplam</div>
                    <div>İşlem</div>
                </div>

                <!-- Sepet ürünleri -->
                <?php foreach ($sepet_urunleri as $sepet_urun): ?>
                    <?php if (isset($urunler[$sepet_urun['urun_id']])): ?>
                        <?php $urun = $urunler[$sepet_urun['urun_id']]; ?>
                        <?php
                            $fiyat = $urun['fiyat'];
                            $indirimli = false;
                            if (in_array($sepet_urun['urun_id'], $kampanya_onerilenler)) {
                                $indirimli = true;
                                $fiyat_indirimli = $fiyat * 0.8;
                            }
                        ?>
                        <div class="sepet-urun">
                            <div class="urun-resim">
                                <img src="<?php echo $urun['resim']; ?>" alt="<?php echo $urun['isim']; ?>">
                            </div>
                            <div class="urun-bilgi">
                                <h3><?php echo $urun['isim']; ?></h3>
                                <p>Ürün Kodu: <?php echo $urun['id']; ?></p>
                            </div>
                            <div class="urun-fiyat">
                                <?php if ($indirimli): ?>
                                    <span style="font-size:0.95rem;color:#888;text-decoration:line-through;margin-right:8px;">
                                        <?php echo number_format($fiyat, 2); ?> TL
                                    </span>
                                    <span style="font-size:1.15rem;color:#ffb3e2;font-weight:bold;">
                                        <?php echo number_format($fiyat_indirimli, 2); ?> TL
                                    </span>
                                <?php else: ?>
                                    <?php echo number_format($fiyat, 2); ?> TL
                                <?php endif; ?>
                            </div>
                            <div class="adet-kontrol">
                                <form method="POST" style="display: flex; align-items: center; gap: 10px;">
                                    <input type="hidden" name="sepet_id" value="<?php echo $sepet_urun['id']; ?>">
                                    <button type="button" class="adet-btn" onclick="adetDegistir(<?php echo $sepet_urun['id']; ?>, -1)">-</button>
                                    <input type="number" name="yeni_adet" value="<?php echo $sepet_urun['adet']; ?>" min="1" class="adet-input" onchange="adetGuncelle(<?php echo $sepet_urun['id']; ?>, this.value)">
                                    <button type="button" class="adet-btn" onclick="adetDegistir(<?php echo $sepet_urun['id']; ?>, 1)">+</button>
                                </form>
                            </div>
                            <div class="toplam-fiyat">
                                <?php if ($indirimli): ?>
                                    <span style="font-size:0.95rem;color:#888;text-decoration:line-through;margin-right:8px;">
                                        <?php echo number_format($fiyat * $sepet_urun['adet'], 2); ?> TL
                                    </span>
                                    <span style="font-size:1.15rem;color:#ffb3e2;font-weight:bold;">
                                        <?php echo number_format($fiyat_indirimli * $sepet_urun['adet'], 2); ?> TL
                                    </span>
                                <?php else: ?>
                                    <?php echo number_format($fiyat * $sepet_urun['adet'], 2); ?> TL
                                <?php endif; ?>
                            </div>
                            <div>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="sepet_id" value="<?php echo $sepet_urun['id']; ?>">
                                    <button type="submit" name="urun_sil" class="urun-sil-btn" onclick="return confirm('Bu ürünü sepetten kaldırmak istediğinizden emin misiniz?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Sepet özeti -->
                <div class="sepet-ozet">
                    <div class="ozet-satir">
                        <span>Toplam Ürün:</span>
                        <span><?php echo $toplam_adet; ?> adet</span>
                    </div>
                    <div class="ozet-satir">
                        <span>Kargo:</span>
                        <span>Ücretsiz</span>
                    </div>
                    <div class="ozet-satir">
                        <span>Genel Toplam:</span>
                        <span><?php echo number_format($toplam_fiyat, 2); ?> TL</span>
                    </div>
                    <form method="POST" style="text-align:right; margin-top:30px;">
                        <button type="submit" name="siparisi_tamamla" class="siparis-tamamla-btn">
                            <i class="fa-solid fa-credit-card"></i> Siparişi Tamamla
                        </button>
                    </form>
                </div>
            <?php endif; ?>
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
    <script>
        function adetDegistir(sepetId, degisim) {
            const input = document.querySelector(`input[name="yeni_adet"][onchange*="${sepetId}"]`);
            const yeniAdet = parseInt(input.value) + degisim;
            if (yeniAdet >= 1) {
                input.value = yeniAdet;
                adetGuncelle(sepetId, yeniAdet);
            }
        }

        function adetGuncelle(sepetId, yeniAdet) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="sepet_id" value="${sepetId}">
                <input type="hidden" name="yeni_adet" value="${yeniAdet}">
                <input type="hidden" name="adet_guncelle" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html> 