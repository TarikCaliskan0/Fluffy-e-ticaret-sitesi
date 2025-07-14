<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - Giriş Yap</title>
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
    <div class="auth-container">
        <div class="auth-box">
            <div class="logo-section">
                <a href="index.html">
                    <img src="resimler/logo.png" alt="Fluffy Logo" width="200">
                </a>
            </div>
            <?php if(isset($_GET['durum']) && $_GET['durum'] == "no"): ?>
                <div class="alert alert-danger">E-posta veya şifre hatalı!</div>
            <?php endif; ?>
            <form class="auth-form" action="giris_kontrol.php" method="POST">
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="e_posta" required>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <div class="password-input">
                        <input type="password" id="password" name="sifre" required>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="auth-button">Giriş Yap</button>
                </div>
                <div class="form-footer">
                    <p>Hesabınız yok mu? <a href="kayit.php">Kayıt Ol</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="giris.js"></script>
    <script src="chatbot_ollama.js"></script>
</body>

</html> 