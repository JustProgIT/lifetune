<?php
include 'config.php';
include 'translate.php';
$profilePic = '';

if(empty($_SESSION['email'])) {
	header("Location: login");
	$email = '';
	$name = '';
	exit;
} else {
$email = '';
$email = $_SESSION['email'];
$getid = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$getid->execute([$email]);
$user = $getid->fetch();
$name = $user['nickname'];
if($user['password'] == 'google') {
	$profilePic = $user['profile_picture'];
} else {
	$profilePic = $user['profile_picture'] ? "uploads/" . htmlspecialchars($user['profile_picture']) : "img/avatar-placeholder.jpg";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Life.Tune - Profile</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    /* 清新风格的Profile页面样式 */
    body {
      background-image: url('img/computer background LIFE.TUNE.png') !important;
      background-size: cover !important;
      background-position: center !important;
      background-repeat: no-repeat !important;
      background-attachment: fixed !important;
      color: #0A1632;
    }
    
    .profile-container {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: clamp(1rem, 5vw, 2rem);
      box-sizing: border-box;
      min-height: 0;
      margin-bottom: 6rem;
    }
    
    .profile-header {
      text-align: center;
      margin-bottom: clamp(2rem, 5vh, 3rem);
    }
    
    .profile-header h1 {
      font-size: clamp(2.5rem, 5vh, 3.5rem);
      color: #0A1632;
      margin-bottom: 0.5rem;
      font-weight: bold;
    }
    
    .profile-header .subtitle {
      font-size: clamp(1.1rem, 2.5vh, 1.3rem);
      color: #666;
      opacity: 0.8;
    }
    
    .profile-list {
      display: flex;
      flex-direction: column;
      gap: clamp(0.75rem, 2vh, 1rem);
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
    }
    
    .profile-item {
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
    
    .profile-item:hover {
      background: rgba(255, 255, 255, 0.95);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
      border-color: rgba(255, 157, 0, 0.4);
    }
    
    .profile-item .left {
      display: flex;
      align-items: center;
      gap: clamp(1rem, 2.5vh, 1.25rem);
    }
    
    .profile-item .avatar {
      width: 3.5rem;
      height: 3.5rem;
      border-radius: 1rem;
      object-fit: cover;
      border: 2px solid rgba(255, 157, 0, 0.3);
    }
    
    .profile-item .info {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    
    .profile-item .info .name {
      font-size: clamp(1.1rem, 2.5vh, 1.25rem);
      font-weight: bold;
      color: #0A1632;
      margin: 0;
    }
    
    .profile-item .info .email {
      font-size: clamp(0.9rem, 2vh, 1rem);
      color: #666;
      margin: 0;
      opacity: 0.8;
    }
    
    .profile-item span:last-child {
      color: #FF9D00;
      font-size: 1.25rem;
      font-weight: bold;
    }
    
    .profile-item.logout {
      background: rgba(255, 76, 76, 0.1);
      border-color: rgba(255, 76, 76, 0.3);
    }
    
    .profile-item.logout:hover {
      background: rgba(255, 76, 76, 0.15);
      border-color: rgba(255, 76, 76, 0.5);
    }
    
    .profile-item.logout span:last-child {
      color: #FF4C4C;
    }
    
    .profile-item.logout {
      margin-bottom: 5rem;
    }
    
    .profile-item .left span:first-child {
      font-size: 1.5rem;
      margin-right: 0.5rem;
    }
    
    .profile-item .left span:nth-child(2) {
      font-size: clamp(1rem, 2.5vh, 1.1rem);
      font-weight: 500;
      color: #0A1632;
    }
    

  </style>
</head>

<body class="home-body" style="background-image: url('img/computer background LIFE.TUNE.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
  <!-- fixed header -->
  <header class="site-header">
    <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo" />
  </header>

  <!-- main profile content -->
  <main class="profile-container">
    <div class="profile-header">
      <h1><?= $messages[$lang]['profile_title'] ?></h1>
    </div>
    
    <ul class="profile-list">
      <!-- 1) User info -->
      <div class="profile-item" onclick="location.href='profile_edit'">
        <div class="left">
          <img src="<?= $profilePic ?>" alt="avatar" class="avatar" />
          <div class="info">
            <div class="name"><?php echo $name; ?></div>
            <div class="email"><?php echo $email; ?></div>
          </div>
        </div>
        <span>›</span>
      </div>

      <!-- 2) Change Password -->
      <li class="profile-item" onclick="location.href='change_password'">
        <div class="left">
          <span>⚡</span>
          <span><?= $messages[$lang]['change_password'] ?></span>
        </div>
        <span>›</span>
      </li>

      <!-- 3) Language Settings -->
      <li class="profile-item" onclick="location.href='language'">
        <div class="left">
          <span>⌨️</span>
          <span><?= $messages[$lang]['language'] ?></span>
        </div>
        <span>›</span>
      </li>

      <!-- 4) Log out -->
      <li class="profile-item logout" onclick="handleLogout()">
        <div class="left">
          <span>🚪</span>
          <span><?= $messages[$lang]['logout'] ?></span>
        </div>
        <span>›</span>
      </li>
    </ul>
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
    <button class="nav-item active" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
      <span class="nav-label"><?= $messages[$lang]['profile'] ?></span>
    </button>
  </nav> 

  <script>
    function handleLogout() {
      if (confirm('Are you sure you want to log out?')) {
        // Redirect to login page
        location.href = 'logout';
      }
      // If they cancel, do nothing
    }
  </script>
</body>
</html>
