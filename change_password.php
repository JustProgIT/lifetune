<?php
require 'config.php';
include 'translate.php';
$errors = [];
$successMessage = "";
$hashedFromDB = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {	
	$email = $_POST['email'];
    $oldPassword = $_POST['curpassword'] ?? '';
    $newPassword = $_POST['newpassword'] ?? '';
    $confirmPassword = $_POST['newpassword2'] ?? '';

    // Fetch old password from DB
    $stmt = $pdo->prepare("SELECT password FROM tbl_userinfo WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
	$hashedFromDB = $user['password'];
	
	if(!empty($hashedFromDB) && strlen($hashedFromDB) < 20) {
		$hashedFromDB = password_hash($hashedFromDB, PASSWORD_DEFAULT);	
		$hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE tbl_userinfo SET password = ? WHERE email = ?");
        $updateStmt->execute([$hashedFromDB, $email]);
	}

    if (!password_verify($oldPassword, $hashedFromDB)) {
        $errors[] = "- Current password is incorrect.";
    }
	
	if (password_verify($newPassword, $hashedFromDB)) {
        $errors[] = "- New password cannot be the same with current password.";
    }

    // Validate new password format
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)) {
        $errors[] = "- New password must be at least 8 characters and include uppercase, lowercase, and a number.";
    }

    if ($newPassword !== $confirmPassword) {
        $errors[] = "- New passwords do not match.";
    }

    // If no errors, update password
    if (empty($errors)) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE tbl_userinfo SET password = ? WHERE email = ?");
        $updateStmt->execute([$hashed, $email]);
		header("Location: profile");
        $successMessage = "Password updated successfully. $newPassword , $hashed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Life.Tune - Set Password</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    /* 与profile.php完全一致的清新风格 */
    body {
      background-image: url('img/computer background LIFE.TUNE.png') !important;
      background-size: cover !important;
      background-position: center !important;
      background-repeat: no-repeat !important;
      background-attachment: fixed !important;
      color: #0A1632;
    }
    
    .change-password-page {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: clamp(1rem, 5vw, 2rem);
      box-sizing: border-box;
      min-height: 0;
      margin-bottom: 6rem;
    }
    
    .change-password-header {
      text-align: center;
      margin-bottom: clamp(2rem, 5vh, 3rem);
    }
    
    .change-password-header h1 {
      font-size: clamp(2.5rem, 5vh, 3.5rem);
      color: #0A1632;
      margin-bottom: 0.5rem;
      font-weight: bold;
    }
    
    .change-password-form {
      display: flex;
      flex-direction: column;
      gap: clamp(0.75rem, 2vh, 1rem);
      width: 100%;
      max-width: 700px;
      margin: 0 auto;
    }
    
    .input-group {
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
    }
    
    .input-group:hover {
      background: rgba(255, 255, 255, 0.95);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
      border-color: rgba(255, 157, 0, 0.4);
    }
    
    .input-group:focus-within {
      background: rgba(255, 255, 255, 0.95);
      border-color: rgba(255, 157, 0, 0.4);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
    }
    
    .input-left {
      display: flex;
      align-items: center;
      gap: clamp(1rem, 2.5vh, 1.25rem);
    }
    
    .input-icon {
      font-size: 1.5rem;
    }
    
    .input-content {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      width: 100%;
    }
    
    .input-label {
      font-size: clamp(1.1rem, 2.5vh, 1.25rem);
      font-weight: bold;
      color: #0A1632 !important;
      margin: 0;
    }
    
    .input-placeholder {
      font-size: clamp(0.9rem, 2vh, 1rem);
      color: #666 !important;
      margin: 0;
      opacity: 0.8;
    }
    
    .label-row {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 0.5rem;
    }
    
    .input-group input {
      border: none;
      background: transparent;
      font-size: clamp(0.9rem, 2vh, 1rem);
      color: #0A1632 !important;
      outline: none;
      width: 100%;
      margin-top: 0.25rem;
    }
    
    .input-group input::placeholder {
      color: #666;
      opacity: 0.6;
    }
    
    .password-info {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 157, 0, 0.2);
      border-radius: 1rem;
      padding: clamp(1rem, 2.5vh, 1.25rem) clamp(1.25rem, 4vw, 1.5rem);
      font-size: clamp(0.9rem, 2vh, 1rem);
      color: #666;
      text-align: left;
      margin: clamp(0.75rem, 2vh, 1rem) 0;
      opacity: 0.8;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.1);
    }
    
    .save-btn-container {
      margin-top: clamp(1.5rem, 4vh, 2rem);
      margin-bottom: clamp(5rem, 8vh, 7rem);
      display: flex;
      justify-content: center;
    }
    
    .oval-save-btn {
      background: linear-gradient(135deg, #FF9D00, #FFB74D);
      color: white;
      border: none;
      border-radius: 2rem;
      padding: clamp(0.75rem, 2vh, 1rem) clamp(2rem, 5vw, 3rem);
      font-size: clamp(1rem, 2.5vh, 1.1rem);
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.3);
    }
    
    .oval-save-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.4);
    }
    
    #serverErrors {
      background: rgba(255, 76, 76, 0.1);
      border: 1px solid rgba(255, 76, 76, 0.3);
      border-radius: 1rem;
      padding: clamp(1rem, 2.5vh, 1.25rem) clamp(1.25rem, 4vw, 1.5rem);
      margin: clamp(0.75rem, 2vh, 1rem) 0;
      box-shadow: 0 4px 15px rgba(255, 76, 76, 0.1);
    }
    
    #serverErrors p {
      color: #FF4C4C;
      font-size: clamp(0.9rem, 2vh, 1rem);
      margin: 0.25rem 0;
    }
    
    .success-message {
      background: rgba(76, 175, 80, 0.1);
      border: 1px solid rgba(76, 175, 80, 0.3);
      border-radius: 1rem;
      padding: clamp(1rem, 2.5vh, 1.25rem) clamp(1.25rem, 4vw, 1.5rem);
      margin: clamp(0.75rem, 2vh, 1rem) 0;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.1);
      color: #4CAF50;
      font-size: clamp(0.9rem, 2vh, 1rem);
    }
  </style>
</head>
<body class="home-body" style="background-image: url('img/computer background LIFE.TUNE.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
  <!-- fixed header -->
  <header class="site-header">
    <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo" />
  </header>

  <main class="change-password-page">
    <!-- Header -->
    <div class="change-password-header">
      <button class="btn-back" onclick="location.href='profile'" aria-label="Go back">
        <svg viewBox="0 0 24 24" class="back-icon">
          <path d="M15 19l-7-7 7-7"/>
        </svg>
      </button>	
      <h2><?= $messages[$lang]['changepassword_head'] ?></h2>
    </div>

    <!-- Form fields -->
    <form class="change-password-form" method="post">
      <!-- Email -->
      <div class="input-group">
        <div class="input-left">
          <div class="input-content">
            <div class="label-row">
              <div class="input-label"><?= $messages[$lang]['changepassword_txt1'] ?></div>
            </div>
            <input type="text" placeholder="<?= $messages[$lang]['changepassword_email'] ?>" name="email"/>
          </div>
        </div>
      </div>
      
      <!-- Current Password -->
      <div class="input-group">
        <div class="input-left">
          <div class="input-content">
            <div class="label-row">
              <div class="input-label"><?= $messages[$lang]['changepassword_txt2'] ?></div>
            </div>
            <input type="password" placeholder="<?= $messages[$lang]['changepassword_password'] ?>" name="curpassword"/>
          </div>
        </div>
      </div>
      
      <!-- New Password -->
      <div class="input-group">
        <div class="input-left">
          <div class="input-content">
            <div class="label-row">
              <div class="input-label"><?= $messages[$lang]['changepassword_txt3'] ?></div>
            </div>
            <input type="password" placeholder="<?= $messages[$lang]['changepassword_newpassword'] ?>" name="newpassword" id="newPassword" />
          </div>
        </div>
      </div>
      
      <!-- Confirm Password -->
      <div class="input-group">
        <div class="input-left">
          <div class="input-content">
            <div class="label-row">
              <div class="input-label"><?= $messages[$lang]['changepassword_txt4'] ?></div>
            </div>
            <input type="password" placeholder="<?= $messages[$lang]['changepassword_cfpassword'] ?>" name="newpassword2" id="confirmPassword"/>
          </div>
        </div>
      </div>

      <!-- Password rules -->
      <div class="password-info">
        <?= $messages[$lang]['changepassword_txt5'] ?>
      </div>
      
      <!-- Server-side errors -->
      <?php if (!empty($errors)): ?>
      <div id="serverErrors">
        <?php foreach ($errors as $error): ?>
          <p><?php echo $error; ?></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Success message -->
      <?php if ($successMessage): ?>
        <div class="success-message"><?php echo $successMessage; ?></div>
      <?php endif; ?>
      
      <!-- Save Button -->
      <div class="save-btn-container">
        <button type="submit" class="oval-save-btn"><?= $messages[$lang]['changepassword_btn'] ?></button>
      </div>
    </form>
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
      <span class="nav-label"><?= $messages[$lang]['setting'] ?></span>
    </button>
  </nav> 

  <script>
    function validatePasswords() {
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return false; // prevent form submission
      }
      return true;
    }
  </script>
</body>
</html>
