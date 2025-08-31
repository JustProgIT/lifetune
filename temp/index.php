<?php
include 'config.php';
include 'translate.php';
$login_sta = empty($_SESSION['bazi_data']) ? 'yes' : 'no';

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
		<div class="lang-icon" onclick="toggleLangMenu()">??</div>
		<div class="lang-menu" id="langMenu">
		  <button onclick="setLang('en')">English</button>
		  <button onclick="setLang('cn')">??</button>
		  <button onclick="setLang('th')">???</button>
		</div>
    </div>
  </header>

  <main class="hero" style="text-align: left; align-items: flex-start;">
    <div class="hero-text" style="text-align: left;">
      <h1 style="text-align: left;"><span style="color:rgb(255, 205, 96);">Feeling lost</span> <span style="color:rgb(68, 68, 68);">in your own emotions?</span></h1>
      <p class="subtitle" style="color:rgb(68, 68, 68); text-align: left;">
        Let's find clarity together â€” with AI that truly understands you.
      </p>
    </div>
  

  
    <button class="btn-primary" id="start-btn" style="align-self: flex-start;">Start My Journey</button>
  </main>
   
    <!-- fixed bottom navigation -->
  <nav class="bottom-nav">
    <button class="nav-item active" aria-label="Home" onclick="location.href='index'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>
      <span class="nav-label">Home</span>
    </button>
    <button class="nav-item" aria-label="AI Assistant" onclick="location.href='aichat'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M4 17h2v-7H4v7zm4 0h2V7H8v10zm4 0h2v-4h-2v4zm4 0h2v-9h-2v9z"/></svg>
      <span class="nav-label">AI Assistant</span>
    </button>
    <button class="nav-item" aria-label="Insight" onclick="location.href='result'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm.93 15h-1.86v-1.86h1.86V17zm1.52-7.1c-.2.3-.4.6-.4 1 0 .6.4 1 1 1s1-.4 1-1c0-1-.6-1.7-1.6-2.5-.8-.6-1.2-1-1.2-1.9 0-1.1.9-2 2-2s2 .9 2 2h2a4 4 0 0 0-4-4c-2.3 0-4 1.7-4 4 0 1.2.6 2 1.6 2.8.9.6 1.4 1 1.4 1.8 0 .6-.4 1-1 1z"/></svg>
      <span class="nav-label">Insight</span>
    </button>
    <button class="nav-item" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/></svg>
      <span class="nav-label">Profile</span>
    </button>
  </nav> 

  <!-- Optional: register a service worker for offline or caching -->
  <script>
    document.getElementById('start-btn').addEventListener('click', () => {
      // TODO: hook up your app's onboarding flow
      window.location.href = 'onboarding';
    });
  </script>
</body>
</html>
