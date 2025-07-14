<?php
require_once 'baglan.php';

if ($_POST) {
    $e_posta = $_POST['e_posta'];
    $sifre = $_POST['sifre'];

    $kullanicisor = $db->prepare("SELECT * FROM uye WHERE e_posta = :e_posta AND sifre = :sifre");
    $kullanicisor->execute(array(
        'e_posta' => $e_posta,
        'sifre' => $sifre
    ));

    $kullanici = $kullanicisor->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
        session_start();
        $_SESSION['e_posta'] = $e_posta;
        $_SESSION['ad'] = $kullanici['ad'];
        $_SESSION['soyad'] = $kullanici['soyad'];
        
        // Ana sayfaya yönlendir
        header("Location: index.php");
        exit;
    } else {
        header("Location: giris.php?durum=no");
        exit;
    }
}
?> 