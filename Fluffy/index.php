<?php
session_start();
require_once 'baglan.php';
require_once 'sepet_fonksiyon.php';

// Sepet sayısını hesapla
$sepet_sayisi = sepetSayisiGetir($db, $_SESSION['e_posta'] ?? null);
?>
<!DOCTYPE html>
<html lang="tr" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Fluffy - Ana Sayfa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
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

    <div class="arka1">
        <p>Yumuşak dostlarınız her zaman yanınızda</p>
        <img src="resimler/Arkaplan5.png" alt="">

    </div>

    <div class="gozat">
        <h1>🧸 Yeni Ürünlere Göz At 🧸</h1>
    </div>

    <div class="yeniurun-wrapper">
        <div class="yeniurun" id="yeniurun">
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/peluskurt.jpg" alt="Peluş Kurt">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=kurt" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Kurt</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/pelusmidilli.png" alt="Peluş Midilli">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=midilli" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Midilli</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/peluspenguen.jpg" alt="Peluş Penguen">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=penguen" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Penguen</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/peluspikachu.webp" alt="Peluş Pikachu">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=pikachu" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Pikachu</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/pelustavsan.jpg" alt="Peluş Tavşan">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=tavsan" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Tavşan</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/pelusayş.jpg" alt="Peluş Ayı">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=aslan" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Ayı</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/peluskedi.jpg" alt="Peluş Kedi">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=kedi" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Kedi</h3>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="resimler/pelusmaymun.jpg" alt="Peluş Maymun">
                    <div class="product-overlay">
                        <a href="urun_detay.php?id=maymun" class="goz-at-btn add-to-cart">Göz At</a>
                    </div>
                </div>
                <h3>Peluş Maymun</h3>
            </div>
        </div>
        <div class="scroll-dots">
            <button class="nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
            <div class="dots-container">
                <button class="scroll-dot active"></button>
                <button class="scroll-dot"></button>
                <button class="scroll-dot"></button>
            </div>
            <button class="nav-btn next-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <div class="arka2">
        <p>Sana arkadaşlık yapacak bir Peluş seç</p>
        <img src="resimler/Arkaplan3.png" alt="">
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

    <!-- Şık Chatbot Widget -->
    <style>
    #chatbot-fab {
      position: fixed; bottom: 30px; right: 30px; z-index: 9999;
      width: 64px; height: 64px; background: linear-gradient(135deg, #ffb3e2 0%, #6a82fb 100%);
      border-radius: 50%; box-shadow: 0 4px 24px rgba(106,130,251,0.18);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
      border: none;
      animation: chatbotFabPop 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes chatbotFabPop {
      0% { transform: scale(0.5); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    #chatbot-fab:hover { background: linear-gradient(135deg, #f870c4 0%, #6a82fb 100%); box-shadow: 0 8px 32px rgba(255,179,226,0.25); transform: scale(1.08); }
    #chatbot-fab i { font-size: 2rem; color: #fff; }
    #chatbot-widget {
      position: fixed; bottom: 110px; right: 30px; z-index: 9999;
      width: 370px; max-width: 98vw; background: linear-gradient(135deg, #fff4ec 0%, #f3e8ff 100%);
      border-radius: 22px; box-shadow: 0 8px 32px rgba(106,130,251,0.18);
      font-family: 'Cabin', 'Poppins', sans-serif; display: none; flex-direction: column;
      overflow: hidden; animation: chatbotOpen 0.5s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes chatbotOpen {
      0% { transform: translateY(60px) scale(0.95); opacity: 0; }
      100% { transform: translateY(0) scale(1); opacity: 1; }
    }
    #chatbot-widget.open { display: flex; }
    #chatbot-header {
      background: linear-gradient(90deg, #ffb3e2 0%, #6a82fb 100%); color: #fff; padding: 16px 22px; border-radius: 22px 22px 0 0;
      font-weight: bold; font-size: 1.25rem; letter-spacing: 1px;
      display: flex; justify-content: space-between; align-items: center;
      box-shadow: 0 2px 8px rgba(255,179,226,0.08);
    }
    #chatbot-header .avatar {
      width: 36px; height: 36px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; margin-right: 10px;
      box-shadow: 0 2px 8px rgba(255,179,226,0.15);
    }
    #chatbot-header .avatar img {
      width: 28px; height: 28px; border-radius: 50%; object-fit: cover;
    }
    #chatbot-close {
      background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; transition: color 0.2s;
    }
    #chatbot-close:hover { color: #ffe0f7; }
    #chatbot-messages {
      padding: 18px 12px 12px 12px; height: 320px; overflow-y: auto; font-size: 1rem;
      background: transparent; display: flex; flex-direction: column; gap: 10px;
      scroll-behavior: smooth;
    }
    .chatbot-msg {
      display: flex; align-items: flex-end; gap: 8px;
      animation: chatbotMsgIn 0.4s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes chatbotMsgIn {
      0% { transform: translateY(20px) scale(0.95); opacity: 0; }
      100% { transform: translateY(0) scale(1); opacity: 1; }
    }
    .chatbot-msg.user { flex-direction: row-reverse; }
    .chatbot-bubble {
      max-width: 75%; padding: 12px 16px; border-radius: 18px; font-size: 1rem;
      background: #fff; color: #6a82fb; box-shadow: 0 2px 8px rgba(255,179,226,0.10);
      margin-bottom: 2px; word-break: break-word;
      transition: background 0.2s, color 0.2s;
    }
    .chatbot-msg.user .chatbot-bubble {
      background: linear-gradient(90deg, #ffb3e2 0%, #f870c4 100%); color: #fff; align-self: flex-end;
    }
    .chatbot-msg.bot .chatbot-bubble {
      background: linear-gradient(90deg, #e0e7ff 0%, #fff4ec 100%); color: #6a82fb; align-self: flex-start;
    }
    .chatbot-avatar {
      width: 32px; height: 32px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center;
      box-shadow: 0 2px 8px rgba(255,179,226,0.10);
    }
    .chatbot-avatar img {
      width: 28px; height: 28px; border-radius: 50%; object-fit: cover;
    }
    #chatbot-input-area {
      display: flex; border-top: 1px solid #eee; background: #fff; padding: 0 0 0 8px;
    }
    #chatbot-input {
      flex: 1; border: none; padding: 14px; font-size: 1rem; border-radius: 0 0 0 22px;
      outline: none; background: #fff;
    }
    #chatbot-send {
      background: linear-gradient(135deg, #ffb3e2 0%, #6a82fb 100%); color: #fff; border: none; padding: 0 28px;
      font-size: 1.1rem; font-weight: bold; border-radius: 0 0 22px 0; cursor: pointer;
      transition: background 0.2s;
    }
    #chatbot-send:hover { background: linear-gradient(135deg, #f870c4 0%, #6a82fb 100%); }
    @media (max-width: 600px) {
      #chatbot-widget { width: 99vw; right: 0; border-radius: 0; }
      #chatbot-header { border-radius: 0; }
      #chatbot-input { border-radius: 0 0 0 0; }
      #chatbot-send { border-radius: 0 0 0 0; }
    }
    </style>
    <button id="chatbot-fab" title="Sohbet Botu"><i class="fa-solid fa-comments"></i></button>
    <div id="chatbot-widget">
      <div id="chatbot-header">
        <span style="display:flex;align-items:center;gap:10px;">
          <span class="avatar"><img src="resimler/logo.png" alt="Fluffy Bot"></span>
          Fluffy ChatBot
        </span>
        <button id="chatbot-close" title="Kapat">&times;</button>
      </div>
      <div id="chatbot-messages"></div>
      <div id="chatbot-input-area">
        <input type="text" id="chatbot-input" placeholder="Sorunuzu yazın..." autocomplete="off" />
        <button id="chatbot-send">Gönder</button>
      </div>
    </div>
    <script>
    const fab = document.getElementById('chatbot-fab');
    const widget = document.getElementById('chatbot-widget');
    const closeBtn = document.getElementById('chatbot-close');
    fab.onclick = () => widget.classList.add('open');
    closeBtn.onclick = () => widget.classList.remove('open');
    const messages = document.getElementById('chatbot-messages');
    const input = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    function addMessage(text, sender) {
      const msg = document.createElement('div');
      msg.className = 'chatbot-msg ' + sender;
      msg.innerHTML = `
        <span class="chatbot-avatar">${sender==='bot'?'<img src=\'resimler/logo.png\' alt=\'Bot\'>' : '<i class=\'fa-solid fa-user\'></i>'}</span>
        <span class="chatbot-bubble">${text}</span>
      `;
      messages.appendChild(msg);
      messages.scrollTop = messages.scrollHeight;
    }
    function sendMessage() {
      const text = input.value.trim();
      if (!text) return;
      addMessage(text, 'user');
      input.value = '';
      addMessage('Yükleniyor...', 'bot');
      fetch('chatbot.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message=' + encodeURIComponent(text)
      })
      .then(r => r.text())
      .then(answer => {
        messages.lastChild.querySelector('.chatbot-bubble').innerHTML = answer;
        messages.scrollTop = messages.scrollHeight;
      });
    }
    sendBtn.onclick = sendMessage;
    input.onkeydown = e => { if (e.key === 'Enter') sendMessage(); };
    </script>

    <script src="script.js"></script>
</body>

</html> 