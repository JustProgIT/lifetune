<?php
  session_start();
  include 'translate.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Life.Tune - Pick Your Focus</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .choice-icon {
      background: none !important;
      box-shadow: none !important;
      border-radius: 0 !important;
      padding: 0 !important;
    }
  </style>
</head>
<body class="onboarding-page">

  <main class="onboarding container">
    <!-- Header -->
      <div class="ai-top">
      <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo">
    </div>

    <!-- Welcome Section -->
    <div class="welcome-section">
      <h1 class="welcome-title"><?= $messages[$lang]['onboard_title'] ?></h1>
      <p class="welcome-subtitle"><?= $messages[$lang]['onboard_subtitle'] ?></p>
    </div>

    <!-- Choice buttons with icons -->
    <div class="onboarding-choices">
      <button class="btn-choice" value="<?= $messages[$lang]['onboard_value1'] ?>">
        <div class="choice-icon" style="font-size:2rem;">💼</div>
        <div class="choice-content">
          <span class="choice-title"><?= $messages[$lang]['onboard_txt1'] ?></span>
          <span class="choice-desc"><?= $messages[$lang]['onboard_subtxt1'] ?></span>
        </div>
      </button>
      
      <button class="btn-choice" value="<?= $messages[$lang]['onboard_value2'] ?>">
        <div class="choice-icon" style="font-size:2rem;">❤️</div>
        <div class="choice-content">
          <span class="choice-title"><?= $messages[$lang]['onboard_txt2'] ?></span>
          <span class="choice-desc"><?= $messages[$lang]['onboard_subtxt2'] ?></span>
        </div>
      </button>
      
      <button class="btn-choice" value="<?= $messages[$lang]['onboard_value3'] ?>">
        <div class="choice-icon" style="font-size:2rem;">🧘</div>
        <div class="choice-content">
          <span class="choice-title"><?= $messages[$lang]['onboard_txt3'] ?></span>
          <span class="choice-desc"><?= $messages[$lang]['onboard_subtxt3'] ?></span>
        </div>
      </button>
      
      <button class="btn-choice" value="<?= $messages[$lang]['onboard_value4'] ?>">
        <div class="choice-icon" style="font-size:2rem;">✨</div>
        <div class="choice-content">
          <span class="choice-title"><?= $messages[$lang]['onboard_txt4'] ?></span>
          <span class="choice-desc"><?= $messages[$lang]['onboard_subtxt4'] ?></span>
        </div>
      </button>
    </div>
  </main>

    <!-- fixed bottom navigation -->
  <nav class="bottom-nav">
    <button class="nav-item" aria-label="Home" onclick="location.href='index'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>
      <span class="nav-label"><?= $messages[$lang]['home'] ?></span>
    </button>
    <button class="nav-item" aria-label="AI Assistant" onclick="location.href='aichat'">《》
      <span class="nav-label"><?= $messages[$lang]['aiassistant'] ?></span>
    </button>
    <button class="nav-item" aria-label="Insight" onclick="location.href='result'">☀
      <span class="nav-label"><?= $messages[$lang]['insight'] ?></span>
    </button>
    <button class="nav-item" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
      <span class="nav-label"><?= $messages[$lang]['profile'] ?></span>
    </button>
  </nav> 

  <script>
    document.querySelectorAll('.btn-back').forEach(btn =>
    btn.addEventListener('click', () => {
      window.location.href = 'questionaire';
    }));
  
    document.querySelectorAll('.btn-choice').forEach(btn =>
    btn.addEventListener('click', () => {
    const value = btn.value;
    //  Console.log("Clicked button value:", value);
      // window.location.href = 'aichat?chat=on&message=' + value;//'aichat';
      window.location.href = 'aichat';
    }));

  </script>
</body>
</html>
