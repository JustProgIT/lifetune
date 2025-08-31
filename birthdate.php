<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {	
	$bd = $_POST['birthdate'];
    $bt = $_POST['birthtime'] ?? '';
    $location = $_POST['location'] ?? '';
	$email = $_SESSION['email'];
	
    $updateStmt = $pdo->prepare("UPDATE tbl_userinfo SET birthdate = ?, birthtime = ?, birthplace = ? WHERE email = ?");
    $updateStmt->execute([$bd,$bt,$location, $email]);
	header("Location: result");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Life.Tune - Tell Us About You</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    #loadingOverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
      z-index: 9999;

      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 24px;
      color: #333;
      font-weight: bold;
    }
  </style>
  <script>
    window.onload = function() {
      // Hide overlay just in case
      document.getElementById("loadingOverlay").style.display = "none";
    };

    function showLoading() {
      document.getElementById("loadingOverlay").style.display = "flex";
    }
    window.addEventListener("pageshow", function (event) {
      document.getElementById("loadingOverlay").style.display = "none";
    });
  </script>
</head>
<body class="login-body">

  <main class="login container">
    <!-- Welcome Section -->
    <div class="login-welcome">
      <img
        src="img/bird LIFE.TUNE logo.png"
        alt="Life.Tune logo"
        class="login-logo"
      >
      <p class="login-subtitle">To guide you better, we'd love to learn more about you</p>
    </div>

    <!-- Birthdate Form Card -->
    <div class="login-card">
      <form class="login-form" method="post" onsubmit="showLoading()">
        <div class="form-group">
          <label for="birthdate" class="form-label">
            <svg viewBox="0 0 24 24" class="input-icon">
              <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
            </svg>
            Birthdate
          </label>
          <input
            type="date"
            id="birthdate"
            name="birthdate"
            placeholder="Select your birthdate"
            required
            class="form-input"
          >
        </div>
        
        <div class="form-group">
          <label for="birthtime" class="form-label">
            <svg viewBox="0 0 24 24" class="input-icon">
              <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
            </svg>
            Time of Birth
          </label>
          <input
            type="text"
            id="birthtime"
            name="birthtime"
            placeholder="Enter time of birth"
            required
            class="form-input"
          >
        </div>

        <div class="form-group">
          <label for="location" class="form-label">
            <svg viewBox="0 0 24 24" class="input-icon">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            Country
          </label>
          <input
            type="text"
            id="location"
            name="location"
            placeholder="Enter your country"
            required
            class="form-input"
          >
        </div>

        <div class="form-actions">
          <button class="btn-login" type="submit">
            Create Insight Report
          </button>
        </div>
      </form>
    </div>

    <div id="loadingOverlay"><img src="img/loading2.gif" alt="Loading..." /></div>
  </main>

  <!-- fixed bottom navigation -->
  <nav class="bottom-nav">
    <button class="nav-item" aria-label="Home" onclick="location.href='index'">
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
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
	const birthdateInput = document.getElementById('birthdate');
    const today = new Date().toISOString().split('T')[0];
	
    birthdateInput.setAttribute('max', today);

    // time‐only picker
    flatpickr("input[name='birthtime']", {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",   // e.g. "14:30"
      time_24hr: false     // set to true if you want 24-hour clock
    });
  </script>
</body>
</html>
