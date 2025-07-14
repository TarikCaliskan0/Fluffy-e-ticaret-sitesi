<?php
// Sepet sayısını hesaplayan fonksiyon
function sepetSayisiGetir($db, $e_posta) {
    if (!$e_posta) {
        return 0;
    }
    
    $sorgu = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE e_posta = :e_posta");
    $sorgu->execute(['e_posta' => $e_posta]);
    $sonuc = $sorgu->fetch(PDO::FETCH_ASSOC);
    
    return $sonuc['toplam'] ? (int)$sonuc['toplam'] : 0;
}
?> 