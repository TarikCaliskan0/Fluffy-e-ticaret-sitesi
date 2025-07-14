<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - Kayıt Ol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="giris.css">
    <link rel="stylesheet" href="ollama_chatbot.css" />
</head>

<body>
    <?php
    if (isset($_GET['durum'])) {
        if ($_GET['durum'] == "ok") {
            echo "<div class='alert alert-success'>Kayıt işlemi başarılı! Giriş sayfasına yönlendiriliyorsunuz...</div>";
            header("refresh:2;url=giris.php");
        } else if ($_GET['durum'] == "no") {
            echo "<div class='alert alert-danger'>Kayıt işlemi başarısız!</div>";
        }
    }
    ?>






    <div class="auth-container">
        <div class="auth-box">
            <div class="logo-section">
                <a href="index.html">
                    <img src="resimler/logo.png" alt="Fluffy Logo" width="200">
                </a>
            </div>
            <form action="islem.php" method="POST">
                <div class="form-group">
                    <label for="name">Ad</label>
                    <input type="text" id="name" name="ad" required>
                </div>
                <div class="form-group">
                    <label for="surname">Soyad</label>
                    <input type="text" id="surname" name="soyad" required>
                </div>
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="e_posta" required>
                </div>
                <div class="form-group">
                    <label for="age">Yaş</label>
                    <input type="number" id="age" name="yas" min="18" required>
                </div>
                <div class="form-group">
                    <label for="address">Adres</label>
                    <textarea id="address" name="adres" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <div class="password-input">
                        <input type="password" id="password" name="sifre" required>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Şifre Tekrar</label>
                    <div class="password-input">
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" name="insertislemi" class="auth-button">Kayıt Ol</button>
                </div>
                <div class="form-footer">
                    <p>Zaten hesabınız var mı? <a href="giris.html">Giriş Yap</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="giris.js"></script>
    <script src="chatbot_ollama.js"></script>
</body>
</html>