<?php
session_start();
include 'translate.php';
/*
$messages = [
  'en' => ['title' => 'Language'],
  'th' => ['title' => 'ภาษา'],
  'cn' => ['title' => '汉语']
];*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Life.Tune – Language</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      color: #0A1632;
    }
    .language-container {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: clamp(1rem, 5vw, 2rem);
      box-sizing: border-box;
      min-height: 0;
      margin-bottom: 6rem;
    }
    .language-header {
      text-align: center;
      margin-bottom: clamp(2rem, 5vh, 3rem);
    }
    .language-header h1 {
      font-size: clamp(2.5rem, 5vh, 3.5rem);
      color: #0A1632;
      margin-bottom: 0.5rem;
      font-weight: bold;
    }
    .settings-list {
      display: flex;
      flex-direction: column;
      gap: clamp(0.75rem, 2vh, 1rem);
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
      padding: 0;
      list-style: none;
    }
    .settings-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 157, 0, 0.2);
      border-radius: 1rem;
      padding: clamp(1rem, 2.5vh, 1.25rem) clamp(1.25rem, 4vw, 1.5rem);
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.1);
      font-size: clamp(1.1rem, 2.5vh, 1.25rem);
      font-weight: 500;
      color: #0A1632;
    }
    .settings-item.selected {
      border-color: #FF9D00;
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
      background: rgba(255, 255, 255, 0.95);
    }
    .settings-item .label {
      font-size: clamp(1.1rem, 2.5vh, 1.25rem);
      font-weight: bold;
      color: #0A1632;
    }
    .settings-item .check-icon {
      width: 1.5rem;
      height: 1.5rem;
      fill: #FF9D00;
	  display: none;
    }
	.settings-item.selected .check-icon {
	  display: block;
	}
    .btn-back {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 157, 0, 0.2);
      border-radius: 1rem;
      padding: 0.75rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.1);
      margin-right: 1rem;
    }
    .btn-back:hover {
      background: rgba(255, 255, 255, 0.95);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
      border-color: rgba(255, 157, 0, 0.4);
    }
    .back-icon {
      width: 1.5rem;
      height: 1.5rem;
      fill: #0A1632;
    }
  </style>
</head>
<body class="home-body" style="background-image: url('img/computer background LIFE.TUNE.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
  <!-- fixed header -->
  <header class="site-header">
    <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo" />
  </header>
  <main class="language-container">
    <div class="language-header">
      <h2 id="title"><?= $messages[$lang]['language_title'] ?></h2>
    </div>
    <ul class="settings-list">
      <li class="settings-item" data-lang="en">
        <span class="label">English</span>
        <svg viewBox="0 0 24 24" class="check-icon">
          <path d="M9 16.17l-3.5-3.5 1.41-1.42L9 13.34l7.09-7.1 1.41 1.42z"/>
        </svg>
      </li>
	  <li class="settings-item" data-lang="cn">
        <span class="label">汉语</span>
        <svg viewBox="0 0 24 24" class="check-icon">
          <path d="M9 16.17l-3.5-3.5 1.41-1.42L9 13.34l7.09-7.1 1.41 1.42z"/>
        </svg>
      </li>
      <li class="settings-item" data-lang="th">
        <span class="label">Thai</span>
        <svg viewBox="0 0 24 24" class="check-icon">
          <path d="M9 16.17l-3.5-3.5 1.41-1.42L9 13.34l7.09-7.1 1.41 1.42z"/>
        </svg>
      </li>
    </ul>
  </main>
  <!-- fixed bottom navigation -->
  <nav class="bottom-nav">
    <button class="nav-item" aria-label="Home" onclick="location.href='index'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>
      <span class="nav-label" id="home" ><?= $messages[$lang]['home'] ?></span>
    </button>
    <button class="nav-item" aria-label="AI Assistant" onclick="location.href='aichat'">《》
      <span class="nav-label" id="aiassistant"><?= $messages[$lang]['aiassistant'] ?></span>
    </button>
    <button class="nav-item" aria-label="Insight" onclick="location.href='result'">☀
      <span class="nav-label" id="insight"><?= $messages[$lang]['insight'] ?></span>
    </button>
    <button class="nav-item active" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
      <span class="nav-label" id="profile"><?= $messages[$lang]['profile'] ?></span>
    </button>
  </nav> 
  <script>
    const currentLang = "<?= $lang ?>";
    const texts = {
		en: { 
			title: 'Language',
			profile: "Setting",
			insight: "Insight",
			aiassistant: "AI Assistant",
			home: "Home"
		},
		th: { 
			title: 'ภาษา',
			profile: "ตั้งค่า",
			insight: "ข้อมูลพื้นฐาน",
			aiassistant: "AI ผู้ช่วย",
			home: "หน้าแรก"
		},
		cn: { title: '汉语',
			profile: "设置",
			insight: "基本信息",
			aiassistant: "人工智能助手",
			home: "主页"}
	};

          
    // Highlight selected item on load
    document.querySelectorAll('.settings-item').forEach(item => {
      if (item.dataset.lang === currentLang) {
        item.classList.add('selected');
      }
    });

    // Handle language change
    document.querySelectorAll('.settings-item').forEach(item => {
      item.addEventListener('click', () => {
        const lang = item.dataset.lang;

        // Toggle selection
        document.querySelectorAll('.settings-item').forEach(li => {
          li.classList.remove('selected');
        });
        item.classList.add('selected');
		document.getElementById('title').textContent = texts[lang].title;
		document.getElementById('profile').textContent = texts[lang].profile;
		document.getElementById('insight').textContent = texts[lang].insight;
		document.getElementById('aiassistant').textContent = texts[lang].aiassistant;
		document.getElementById('home').textContent = texts[lang].home;
        // Save to session via backend
        fetch('set_language', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'language=' + encodeURIComponent(lang)
        });
      });
    });
  </script>
</body>
</html>
