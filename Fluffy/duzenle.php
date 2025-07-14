<?php require_once 'baglan.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Güncelleme İşlemi</title>
</head>
<body>

    <h1>Üye Güncelleme Sayfası</h1>
    <hr>

    <?php 
    if (@$_GET['durum'] == "ok") {
        echo "<p style='color:green;'>İşlem başarılı</p>";
    } elseif (@$_GET['durum'] == "no") {
        echo "<p style='color:red;'>İşlem başarısız</p>";
    }

    // Kullanıcının bilgilerini çekiyoruz
    $uyesor = $db->prepare("SELECT * FROM uye WHERE e_posta = :e_posta");
    $uyesor->execute(array('e_posta' => $_GET['e_posta']));
    $uyecek = $uyesor->fetch(PDO::FETCH_ASSOC);
    ?>

    <form action="islem.php" method="POST">
        <input type="text" required name="ad" value="<?php echo $uyecek['ad']; ?>" placeholder="Ad">
        <input type="text" required name="soyad" value="<?php echo $uyecek['soyad']; ?>" placeholder="Soyad">
        <input type="email" required name="e_posta" value="<?php echo $uyecek['e_posta']; ?>" placeholder="E-posta">
        <input type="text" required name="yas" value="<?php echo $uyecek['yas']; ?>" placeholder="Yaş">
        <input type="password" required name="sifre" value="<?php echo $uyecek['sifre']; ?>" placeholder="Şifre">

        <input type="hidden" name="eski_eposta" value="<?php echo $uyecek['e_posta']; ?>">

        <button type="submit" name="updateislemi">Güncelle</button>
    </form>

</body>
</html>
