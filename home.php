<?php
include 'config.php';
include 'translate.php';
$login_sta = empty($_SESSION['bazi_data']) ? 'yes' : 'no';
$sessionLang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';

if (!isset($_COOKIE['referral_id'])) {
    $ref_id = uniqid('ref_', true);

    // Store in cookie for 30 days
    setcookie('referral_id', $ref_id, time() + (86400 * 30), "/");

    $_SESSION['referral_id'] = $ref_id;
    
    $stmt = $pdo->prepare("INSERT INTO tbl_visitors (ref_id, created_at) VALUES (?, NOW())");
    $stmt->execute([$ref_id]);
    
} else {
    $_SESSION['referral_id'] = $_COOKIE['referral_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Life.Tune - Find Clarity</title>
  <style>
    .lang-dropdown {
      position: relative;
      display: inline-block;
    }

    .lang-icon {
      font-size: 20px;
      cursor: pointer;
      padding: 5px 10px;
    }

    .lang-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 30px;
      background-color: white;
      border: 1px solid #ccc;
      min-width: 100px;
      z-index: 1;
    }

    .lang-menu button {
      width: 100%;
      padding: 8px 10px;
      border: none;
      background: white;
      text-align: left;
      cursor: pointer;
    }

    .lang-menu button:hover {
      background-color: #f0f0f0;
    }
	
    /* Modal styles */
    .modal1 {
      display: none; 
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    .modal1-content {
	  display: flex;
	  flex-direction: column;
	  bottom: -8rem;
      background-color: #ededeb;
      margin: 40% auto;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      text-align: center;
	  color: black;
    }

    .modal1-content button {
      margin: 10px;
      padding: 10px 15px;
    }	
    .lang1-buttons {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .lang1-buttons button {
      width: 70%;
      padding: 10px;
      font-size: 16px;
    }
    /* Terms and Conditions Popup Styles */
    .terms-popup {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      transform: translateY(100%);
      transition: transform 0.3s ease-in-out;
    }
    
    .terms-popup.show {
      transform: translateY(0);
    }
    
    .terms-popup-content {
      margin-bottom: 100px;
      background: white;
      padding: 20px;
      border-radius: 20px 20px 0 0;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
      max-height: 70vh;
      overflow-y: auto;
    }
    
    .terms-popup h3 {
      color: #333;
      margin-bottom: 15px;
      font-size: 18px;
      text-align: center;
    }
    
    .terms-popup p {
      color: #666;
      font-size: 14px;
      line-height: 1.5;
      margin-bottom: 15px;
      text-align: left;
    }
    
    .terms-links {
      margin: 15px 0;
    }
    
    .terms-links a {
      color: #FFCD60;
      text-decoration: none;
      font-weight: bold;
      margin: 0 5px;
    }
    
    .terms-links a:hover {
      text-decoration: underline;
    }
    
    .checkbox-container {
      display: flex;
      align-items: flex-start;
      margin: 15px 0;
      gap: 10px;
    }
    
    .checkbox-container input[type="checkbox"] {
      margin-top: 2px;
      transform: scale(1.2);
    }
    
    .checkbox-container label {
      color: #333;
      font-size: 14px;
      line-height: 1.4;
      cursor: pointer;
      flex: 1;
    }
    
    .terms-buttons {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }
    
    .terms-btn {
      flex: 1;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .terms-accept {
      background-color: #FFCD60;
      color: #333;
    }
    
    .terms-accept:hover {
      background-color: #e6b84d;
    }
    
    .terms-accept:disabled {
      background-color: #ccc;
      cursor: not-allowed;
      opacity: 0.6;
    }
    
    .terms-decline {
      background-color: transparent;
      color: #666;
      border: 1px solid #ddd;
    }
    
    .terms-decline:hover {
      background-color: #f5f5f5;
    }
  </style>

  <!-- Web App Manifest for PWA support (optional) 
  <link rel="manifest" href="manifest.json">-->
  <!-- Fallback icon -->

  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" href="styles.css">
</head>
<body style="background-image: url('img/computer background LIFE.TUNE.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <header class="site-header">
    <!-- Replace with your SVG or PNG logo -->
    <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo">
    <div class="lang-dropdown">
      <div class="lang-icon" onclick="toggleLangMenu()">🌐</div>
      <div class="lang-menu" id="langMenu">
        <button onclick="setLang('en')">English</button>
        <button onclick="setLang('cn')">汉语</button>
        <button onclick="setLang('th')">ไทย</button>
      </div>
    </div>
  </header>

  <main class="hero" style="text-align: left; align-items: flex-start;">
    <div class="hero-text" style="text-align: left;">
      <h1 style="text-align: left;">
      <span id="text1" style="color:rgb(255, 205, 96);">Feeling lost</span> 
      <span id="text1a" style="color:rgb(68, 68, 68);"></span>
      </h1>
      <p id="text2" class="subtitle" style="color:rgb(68, 68, 68); text-align: left;">
        <?= $messages[$lang]['index_text2'] ?>
      </p>
    </div>
    <button class="btn-primary" id="start-btn"><?= $messages[$lang]['index_start']."lang= ".$sessionLang ?></button>
	  <div style="margin: 12px auto 0;">
		  <a id="changelang" style="text-decoration: none;font-size:12px;color:gray" onclick="showModal()"><?= $messages[$lang]['index_changelang'] ?></a>
		  <a id="howtouse" style="text-decoration: none;margin-left:100px;font-size:12px;color:gray" onclick="showWarningModal()"><?= $messages[$lang]['index_howtouse'] ?></a>
	  </div>
  </main>
  <!-- Modal -->
  <div id="languageModal" class="modal1">
    <div class="modal1-content">
      <h3><?= $messages[$lang]['index_selectlang'] ?></h3>
      <button onclick="setLang('en')">English</button>
      <button onclick="setLang('cn')">汉语</button>
      <button onclick="setLang('th')">ไทย</button>
      <br>
      <button onclick="closeModal()"><?= $messages[$lang]['index_close'] ?></button>
    </div>
  </div>
  
  <!-- The modal popup -->
  <div id="howtouseModal" class="modal1">
      <div class="modal1-content">
          <h2 id="how1"><?= $messages[$lang]['index_how1'] ?></h2>
          <p id="how2"><?= $messages[$lang]['index_how2'] ?></p>
          <div class="modal1-buttons">
              <button id="cancelLeave" class="modal1-button cancel-button" onclick="closeModal()"><?= $messages[$lang]['alert3'] ?></button>
          </div>
      </div>
  </div>

  <!-- Terms and Conditions Popup -->
	<div id="termsPopup" class="terms-popup">
		<div class="terms-popup-content">
			<h3 id="termsTitle">Welcome to Life Tune</h3>
			<p id="termsText">Before you continue, please review and accept our policies:</p>
			
			<div class="terms-links">
				<a target="_blank" id="termsLink">Terms & Conditions</a> |
				<a target="_blank" id="privacyLink">Privacy Policy</a> |
				<a target="_blank" id="refundLink">Payment & Refund Policy</a>
			</div>
			
			<div class="checkbox-container">
				<input type="checkbox" id="acceptTerms" onchange="toggleAcceptButton()">
				<label for="acceptTerms" id="acceptLabel">
					I have read and agree to the Terms & Conditions, Privacy Policy, and Payment & Refund Policy.
				</label>
			</div>
			
			<div class="terms-buttons">
				<button class="terms-btn terms-decline" onclick="declineTerms()" id="declineBtn">Decline</button>
				<button class="terms-btn terms-accept" id="acceptBtn" onclick="acceptTerms()" disabled>Accept & Continue</button>
			</div>
		</div>
	</div>
	
    <!-- fixed bottom navigation -->
  <nav class="bottom-nav">
    <button class="nav-item active" aria-label="Home" onclick="location.href='index'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>
      <span class="nav-label" id="home" ><?= $messages[$lang]['home'] ?></span>
    </button>
    <button class="nav-item" aria-label="AI Assistant" onclick="location.href='aichat'">《》
      <span class="nav-label" id="aiassistant"><?= $messages[$lang]['aiassistant'] ?></span>
    </button>
    <button class="nav-item" aria-label="Insight" onclick="location.href='result'">☀
      <span class="nav-label" id="insight"><?= $messages[$lang]['insight'] ?></span>
    </button>
    <button class="nav-item" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
      <span class="nav-label" id="profile"><?= $messages[$lang]['profile'] ?></span>
    </button>
  </nav> 

  <!-- Optional: register a service worker for offline or caching -->
  <script>
    document.getElementById('start-btn').addEventListener('click', () => {
      // TODO: hook up your app's onboarding flow
      window.location.href = 'questionaire';
    });
  </script>

  <!-- Language related functionality -->
  <script>
	  const sessionLang = "<?= $sessionLang ?>";
	  // Toggle dropdown menu
	  function toggleLangMenu() {
      const menu = document.getElementById('langMenu');
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
	  }
	  
	  function toggleLangMenu2() {
      const menu = document.getElementById('langMenu2');
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
	  }

	  // Store and apply selected language
	  function setLang(lang) {
      localStorage.setItem('lang', lang);
      applyLanguage(lang);
      document.getElementById('langMenu').style.display = 'none';
      closeModal();
	  }
	  
    function showModal() {
      document.getElementById("languageModal").style.display = "block";
    }
	
    function showWarningModal() {
      const modal = document.getElementById('howtouseModal');	
      modal.style.display = 'block';
    }

    function closeModal() {
      document.getElementById("languageModal").style.display = "none";
	    document.getElementById("howtouseModal").style.display = "none";
    }
	
	  window.onload = () => {
      const detectedLang = sessionLang || (detectLanguage());
	    applyLanguage(detectedLang);
    };
	
    function detectLanguage() {
      const lang = navigator.language || navigator.userLanguage;
      if (lang.startsWith("th")) return "th";
      if (lang.startsWith("zh")) return "cn";
      return "en";
    }
    
    // Optional: Close modal if clicked outside
    window.onclick = function(event) {
      const modal = document.getElementById("languageModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

	  // Basic i18n strings
	  const translations = {
      en: {
        profile: "Setting",
        insight: "Insight",
        aiassistant: "AI Assistant",
        home: "Home",
        text1: "Feeling lost ",
        text1a: "in your own emotions?",
        text2: "Let\'s find clarity together — with AI that truly understands you.",
        start: "Start My Journey",
        changelang: "Change Language",
        howtouse: "How to use",
        how1: "How to use",
        how2: `<br><b>AI Assistant</b><br>
        - Use for chatting with AI chatbot<br><br>
        <b>Insight</b><br>
        - Use for viewing your insight report which requires you to register and login first.`
      },
      th: {
        profile: "ตั้งค่า",
        insight: "ข้อมูลพื้นฐาน",
        aiassistant: "AI ผู้ช่วย",
        home: "หน้าแรก",
        text1: "รู้สึกจม",
        text1a: "ในอารมณ์ของตัวเองไหม?",
        text2: "มาค้นหาความชัดเจนไปด้วยกันด้วย AI ที่เข้าใจคุณอย่างแท้จริง",
        start: "เริ่มการเดินทาง",
        changelang: "เปลี่ยนภาษา",
        howtouse: "วิธีใช้งาน",
        how1: "วิธีใช้งาน",
        how2: `<br><b>ผู้ช่วยเอไอ</b><br> - ใช้สำหรับแชทกับ AI chatbot<br><br><b>Insight</b><br>- ใช้สำหรับดูรายงานข้อมูลเชิงลึกของคุณ ซึ่งต้องลงทะเบียนและเข้าสู่ระบบก่อน`			
      },
      cn: {
        profile: "设置",
        insight: "基本信息",
        aiassistant: "人工智能助手",
        home: "主页",
        text1: "您是否感觉 ",
        text1a: "被自己的情绪淹没了？",
        text2: "让我们一起找到答案——使用真正了解您的人工智能。",
        start: "开始我的旅程",
        changelang: "更改语言",
        howtouse: "如何使用",
        how1: "如何使用",
        how2: `<br><b>AI 助手</b><br> - 用于与 AI 聊天机器人聊天<br><br><b>洞察力</b><br>- 用于查看您的 洞察力 报告，需要先注册并登录`			
      },
	  };

	  // Apply language to all elements with data-langkey
	  function applyLanguage(lang) {
      document.getElementById('profile').textContent = translations[lang].profile;
      document.getElementById('insight').textContent = translations[lang].insight;
      document.getElementById('aiassistant').textContent = translations[lang].aiassistant;
      document.getElementById('home').textContent = translations[lang].home;
      document.getElementById('text1').textContent = translations[lang].text1;
      document.getElementById('text1a').textContent = translations[lang].text1a;
      document.getElementById('text2').textContent = translations[lang].text2;
      document.getElementById('start-btn').textContent = translations[lang].start;
      document.getElementById('changelang').textContent = translations[lang].changelang;
      document.getElementById('howtouse').textContent = translations[lang].howtouse;
      document.getElementById('how1').textContent = translations[lang].how1;
      document.getElementById("how2").innerHTML = translations[lang].how2;

      // fetch('set_language', {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      //   body: 'language=' + encodeURIComponent(lang)
      // });
    }

	  // Load saved language on page load
	  const savedLang = localStorage.getItem('lang') || 'en';
	  applyLanguage(savedLang);
  </script>

  <!-- Terms and condition related functionality -->
  <script>
    // Refund link
    document.getElementById('refundLink').addEventListener('click', () => {
      window.location.href = 'payment_refund';
    });

    document.getElementById('privacyLink').addEventListener('click', () => {
      window.location.href = 'privacy';
    });

    document.getElementById('termsLink').addEventListener('click', () => {
      window.location.href = 'terms';
    });

    function showTermsPopup() {
      const popup = document.getElementById('termsPopup');
      popup.classList.add('show');
	  }
	  
	  function hideTermsPopup() {
      const popup = document.getElementById('termsPopup');
      popup.classList.remove('show');
	  }
	  
	  function toggleAcceptButton() {
      const checkbox = document.getElementById('acceptTerms');
      const acceptBtn = document.getElementById('acceptBtn');
      acceptBtn.disabled = !checkbox.checked;
	  }
	  
	  function acceptTerms() {
      // Store acceptance in localStorage
      localStorage.setItem('termsAccepted', 'true');
      localStorage.setItem('termsAcceptedDate', new Date().toISOString());
      hideTermsPopup();
	  }
	  
	  function declineTerms() {
      // Show a message and potentially redirect or prevent usage
      alert('You must accept our Terms & Conditions, Privacy Policy, and Payment & Refund Policy to use Life Tune.');
      // Optionally redirect to another page or close the app
	  }
	  
	  // Check if terms have been accepted on page  load
	  function checkTermsAcceptance() {
      const termsAccepted = localStorage.getItem('termsAccepted');
      const acceptedDate = localStorage.getItem('termsAcceptedDate');
      
      // Check if terms were accepted in the last 30 days
      if (termsAccepted === 'true' && acceptedDate) {
        const acceptedTime = new Date(acceptedDate).getTime();
        const currentTime = new Date().getTime();
        const daysDiff = (currentTime - acceptedTime) / (1000 * 3600 * 24);
        
        if (daysDiff <= 30) {
        // Terms accepted recently, don't show popup
        return;
        }
		  }
		
      // Show terms popup after a short delay
      setTimeout(showTermsPopup, 1000);
	  }
	  
	  // Initialize terms check on page load
	  document.addEventListener('DOMContentLoaded', checkTermsAcceptance);
	  
	  // Update terms popup text based on language
	  function updateTermsLanguage(lang) {
      const termsTranslations = {
        en: {
        title: 'Welcome to Life Tune',
        text: 'Before you continue, please review and accept our policies:',
        termsLink: 'Terms & Conditions',
        privacyLink: 'Privacy Policy',
        refundLink: 'Payment & Refund Policy',
        acceptLabel: 'I have read and agree to the Terms & Conditions, Privacy Policy, and Payment & Refund Policy.',
        declineBtn: 'Decline',
        acceptBtn: 'Accept & Continue'
        },
        cn: {
        title: '欢迎使用 Life Tune',
        text: '在继续之前，请查看并接受我们的政策：',
        termsLink: '条款和条件',
        privacyLink: '隐私政策',
        refundLink: '付款和退款政策',
        acceptLabel: '我已阅读并同意条款和条件、隐私政策以及付款和退款政策。',
        declineBtn: '拒绝',
        acceptBtn: '接受并继续'
        },
        th: {
        title: 'ยินดีต้อนรับสู่ Life.Tune',
        text: 'ก่อนดำเนินการต่อ กรุณาตรวจสอบและยอมรับนโยบายของเรา:',
        termsLink: 'ข้อกำหนดและเงื่อนไข',
        privacyLink: 'นโยบายความเป็นส่วนตัว',
        refundLink: 'นโยบายการชำระเงินและคืนเงิน',
        acceptLabel: 'ฉันได้อ่านและยอมรับข้อกำหนดและเงื่อนไข นโยบายความเป็นส่วนตัว และนโยบายการชำระเงินและคืนเงิน',
        declineBtn: 'ปฏิเสธ',
        acceptBtn: 'ยอมรับและดำเนินการต่อ'
		  }
		};
		
		const translation = termsTranslations[lang] || termsTranslations.en;
		
		document.getElementById('termsTitle').textContent = translation.title;
		document.getElementById('termsText').textContent = translation.text;
		document.getElementById('termsLink').textContent = translation.termsLink;
		document.getElementById('privacyLink').textContent = translation.privacyLink;
		document.getElementById('refundLink').textContent = translation.refundLink;
		document.getElementById('acceptLabel').textContent = translation.acceptLabel;
		document.getElementById('declineBtn').textContent = translation.declineBtn;
		document.getElementById('acceptBtn').textContent = translation.acceptBtn;
	  }
  </script>
</body>
</html>
