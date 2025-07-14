<?php
require_once 'baglan.php';

// Eğer e_posta parametresi varsa kullanıcı bilgilerini çek
if (isset($_GET['e_posta'])) {
    $bilgilerimsor = $db->prepare("SELECT * from uye WHERE e_posta=:e_posta");
    $bilgilerimsor->execute(
        [
            'e_posta' => $_GET['e_posta']
        ]
    );

    $bilgilerimcek = $bilgilerimsor->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Kullanıcı Listesi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .admin-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 30px 40px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-edit {
            background: #ffc107;
            color: #fff;
        }
        .btn-delete {
            background: #dc3545;
            color: #fff;
        }
        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.85;
        }
        .admin-title {
            font-family: 'Cabin', sans-serif;
            font-size: 2rem;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-title">Kullanıcı Listesi</div>
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ad</th>
                    <th scope="col">Soyad</th>
                    <th scope="col">E-posta</th>
                    <th scope="col">Yaş</th>
                    <th scope="col">Adres</th>
                    <th scope="col">Şifre</th>
                    <th scope="col" colspan="2">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bilgilerimsor = $db->prepare("SELECT * from uye ");
                $bilgilerimsor->execute();
                $say = 0;
                while ($bilgilerimcek = $bilgilerimsor->fetch(PDO::FETCH_ASSOC)) {
                    $say++;
                ?>
                <tr>
                    <form action="islem.php" method="POST" style="display:contents;">
                        <th scope="row"><?php echo $say ?></th>
                        <td><input type="text" name="ad" value="<?php echo htmlspecialchars($bilgilerimcek['ad']) ?>" class="form-control form-control-sm" required></td>
                        <td><input type="text" name="soyad" value="<?php echo htmlspecialchars($bilgilerimcek['soyad']) ?>" class="form-control form-control-sm" required></td>
                        <td><input type="email" name="e_posta" value="<?php echo htmlspecialchars($bilgilerimcek['e_posta']) ?>" class="form-control form-control-sm" required></td>
                        <td><input type="number" name="yas" value="<?php echo htmlspecialchars($bilgilerimcek['yas']) ?>" class="form-control form-control-sm" required></td>
                        <td><input type="text" name="adres" value="<?php echo htmlspecialchars($bilgilerimcek['adres']) ?>" class="form-control form-control-sm" required></td>
                        <td><input type="text" name="sifre" value="<?php echo htmlspecialchars($bilgilerimcek['sifre']) ?>" class="form-control form-control-sm" required></td>
                        <td>
                            <input type="hidden" name="eski_eposta" value="<?php echo htmlspecialchars($bilgilerimcek['e_posta']) ?>">
                            <button type="submit" name="updateislemi" class="btn btn-edit btn-sm">Güncelle</button>
                        </td>
                        <td>
                            <a href="islem.php?e_posta=<?php echo urlencode($bilgilerimcek['e_posta']) ?>&bilgilerimsil=ok" class="btn btn-delete btn-sm" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </form>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Siparişler Tablosu Başlangıç -->
    <div class="admin-container" style="margin-top:40px;">
        <div class="admin-title">Siparişler</div>
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kullanıcı Adı</th>
                    <th scope="col">Soyad</th>
                    <th scope="col">E-posta</th>
                    <th scope="col">Ürün</th>
                    <th scope="col">Adet</th>
                    <th scope="col">Sipariş Tarihi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $siparissor = $db->prepare("SELECT s.*, u.ad, u.soyad FROM siparisler s LEFT JOIN uye u ON s.e_posta = u.e_posta ORDER BY s.siparis_tarihi DESC");
                $siparissor->execute();
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
                $sira = 0;
                foreach ($siparisler as $siparis):
                    $sira++;
                ?>
                <tr>
                    <th scope="row"><?php echo $sira; ?></th>
                    <td><?php echo htmlspecialchars($siparis['ad']); ?></td>
                    <td><?php echo htmlspecialchars($siparis['soyad']); ?></td>
                    <td><?php echo htmlspecialchars($siparis['e_posta']); ?></td>
                    <td><?php echo isset($urunler[$siparis['urun_id']]) ? $urunler[$siparis['urun_id']] : $siparis['urun_id']; ?></td>
                    <td><?php echo $siparis['adet']; ?></td>
                    <td><?php echo $siparis['siparis_tarihi']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Ürün Stok Yönetimi Başlangıç -->
    <div class="admin-container" style="margin-top:40px;">
        <div class="admin-title">Ürün Stok Yönetimi</div>
        <form method="POST" action="islem.php">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ürün Kodu</th>
                        <th scope="col">Ürün Adı</th>
                        <th scope="col">Stok (adet)</th>
                        <th scope="col">Yeni Stok</th>
                        <th scope="col">Güncelle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $urunsor = $db->prepare("SELECT * FROM urunler ORDER BY isim ASC");
                    $urunsor->execute();
                    $sira = 0;
                    while ($urun = $urunsor->fetch(PDO::FETCH_ASSOC)) {
                        $sira++;
                    ?>
                    <tr>
                        <th scope="row"><?php echo $sira; ?></th>
                        <td><?php echo htmlspecialchars($urun['id']); ?></td>
                        <td><?php echo htmlspecialchars($urun['isim']); ?></td>
                        <td><?php echo htmlspecialchars($urun['stok']); ?></td>
                        <td style="max-width:100px;">
                            <input type="number" name="stok[<?php echo $urun['id']; ?>]" value="<?php echo $urun['stok']; ?>" min="0" class="form-control form-control-sm" required>
                        </td>
                        <td>
                            <button type="submit" name="stok_guncelle" value="<?php echo $urun['id']; ?>" class="btn btn-primary btn-sm">Güncelle</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
    <!-- Ürün Stok Yönetimi Bitiş -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>