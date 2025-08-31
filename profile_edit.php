<?php
include 'config.php';
include 'translate.php';
 
if(empty($_SESSION['email'])) {
	header("Location: login");
	$email = '';
	$name = '';
} else {
$email = $_SESSION['email'];
$getid = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$getid->execute([$email]);
$user = $getid->fetch();
$userId = $user['userid'];
$name = $user['nickname'];
$nickname = $name;
$bod = $user['birthdate'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = trim($_POST['nickname']);
    
    // Validate nickname
    if (empty($nickname)) {
        $message = "Nickname cannot be empty.";
    } else {
        // Handle file upload (optional)
        if (!empty($_FILES['profile_picture']['name'])) {
            $file = $_FILES['profile_picture'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if ($file['error'] === 0 && in_array($file['type'], $allowedTypes)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = "profile_" . $userId . "." . $ext;
                $targetPath = "uploads/" . $newName;
                
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    // Update both image and nickname
                    $stmt = $pdo->prepare("UPDATE tbl_userinfo SET nickname = ?, profile_picture = ? WHERE userid = ?");
                    $stmt->execute([$nickname, $newName, $userId]);
                    $message = "Nickname and profile picture updated.";
                } else {
                    $message = "Failed to upload image.";
                }
            } else {
                $message = "Invalid image type.";
            }
        } else {
            // Only nickname update
            $stmt = $pdo->prepare("UPDATE tbl_userinfo SET nickname = ? WHERE userid = ?");
            $stmt->execute([$nickname, $userId]);
            $message = "Nickname updated.";
        }
    }
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE userid = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
if($user['password'] == 'google') {
	$profilePic = $user['profile_picture'];
} else {
	$profilePic = $user['profile_picture'] ? "uploads/" . htmlspecialchars($user['profile_picture']) : "img/avatar-placeholder.jpg";
}
$nickname = htmlspecialchars($user['nickname']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Life.Tune - Personal Information</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
  />
  <link rel="stylesheet" href="styles.css" />
  <style>
    /* 清新风格的Profile Edit页面样式 */
    body {
      background-image: url('img/computer background LIFE.TUNE.png') !important;
      background-size: cover !important;
      background-position: center !important;
      background-repeat: no-repeat !important;
      background-attachment: fixed !important;
      color: #0A1632;
    }
    
    .profile-edit-page {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: clamp(1rem, 5vw, 2rem);
      box-sizing: border-box;
      min-height: 0;
      margin-bottom: 6rem;
    }
    
    .profile-edit-header {
      display: flex;
      align-items: center;
      width: 100%;
      max-width: 500px;
      margin-bottom: clamp(2rem, 5vh, 3rem);
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
    
    .profile-edit-header h2 {
      font-size: clamp(1.4rem, 2vh, 2rem);
      color: #0A1632;
      margin: 0;
      font-weight: bold;
    }
    
    .avatar-container {
      position: relative;
      margin-bottom: clamp(2rem, 5vh, 3rem);
    }
    
    .avatar-img {
      width: 8rem;
      height: 8rem;
      border-radius: 2rem;
      object-fit: cover;
      border: 3px solid rgba(255, 157, 0, 0.3);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
    }
    
    .btn-avatar-edit {
      position: absolute;
      bottom: 0;
      right: 0;
      background: rgba(255, 157, 0, 0.9);
      border: 2px solid white;
      border-radius: 50%;
      width: 2.5rem;
      height: 2.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.3);
    }
    
    .btn-avatar-edit:hover {
      background: rgba(255, 157, 0, 1);
      transform: scale(1.1);
    }
    
    .avatar-edit-icon {
      width: 1.25rem;
      height: 1.25rem;
      fill: white;
    }
    
    .profile-edit-page h3 {
      font-size: clamp(1.5rem, 4vh, 2rem);
      color: #0A1632;
      margin-bottom: clamp(1.5rem, 4vh, 2rem);
      font-weight: bold;
      text-align: center;
    }
    
    .profile-edit-form {
      width: 100%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      gap: clamp(1rem, 2.5vh, 1.25rem);
    }
    
    .input-group {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 157, 0, 0.2);
      border-radius: 1rem;
      padding: clamp(1rem, 2.5vh, 1.25rem) clamp(1.25rem, 4vw, 1.5rem);
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.1);
    }
    
    .input-group:focus-within {
      background: rgba(255, 255, 255, 0.95);
      border-color: rgba(255, 157, 0, 0.4);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.2);
    }
    
    .input-icon {
      width: 1.5rem;
      height: 1.5rem;
      fill: #FF9D00;
      margin-right: 1rem;
    }
    
    .input-group input {
      flex: 1;
      border: none;
      background: transparent;
      font-size: clamp(1rem, 2.5vh, 1.1rem);
      color: #0A1632;
      outline: none;
    }
    
    .input-group input::placeholder {
      color: #666;
      opacity: 0.8;
    }
    
    .save-btn-container {
      margin-top: clamp(1.5rem, 4vh, 2rem);
      margin-bottom: clamp(4.5rem, 7.5vh, 5.5rem);
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
    
    .profile-edit-page p {
      text-align: center;
      font-size: clamp(0.9rem, 2vh, 1rem);
      margin-top: clamp(1rem, 2.5vh, 1.25rem);
    }
  </style>
</head>
<body class="home-body" style="background-image: url('img/computer background LIFE.TUNE.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
  <!-- fixed header -->
  <header class="site-header">
    <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo" />
  </header>

  <main class="profile-edit-page">
    <!-- Top bar: back arrow + title + save check -->
    <div class="profile-edit-header">
      <button class="btn-back" onclick="location.href='profile'" aria-label="Go back">
        <svg viewBox="0 0 24 24" class="back-icon">
          <path d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <h2><?= $messages[$lang]['profileedit_head'] ?></h2>
    </div>

    <!-- Avatar with edit overlay -->
    <div class="avatar-container">
      <img src="<?= $profilePic ?>" alt="User avatar" class="avatar-img"/>
      <button id="choosepic" class="btn-avatar-edit" aria-label="Change avatar" onclick="document.getElementById('fileInput').click();">
        <svg viewBox="0 0 24 24" class="avatar-edit-icon">
          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-11.5c.39-.39.39-1.02 0-1.41l-3.59-3.59a.9959.9959 0 0 0-1.41 0L14.13 4.13l4.59 4.59 2.68-2.67z"/>
        </svg>
      </button>
    </div>
<h3><?= $messages[$lang]['profileedit_txt'] ?></h3>
    <!-- Editable fields -->
<form class="profile-edit-form" method="POST" enctype="multipart/form-data">
      <label class="input-group" style="margin-top:20px">
        <svg viewBox="0 0 24 24" class="input-icon">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        <input type="text" name="nickname" value="<?php echo $nickname; ?>" />
      </label>
    <input type="file" name="profile_picture" id="fileInput" style="display: none;" accept="image/*">

    <!-- New Save Button below the form -->
    <div class="save-btn-container">
      <button type="submit" class="oval-save-btn"><?= $messages[$lang]['profileedit_btn'] ?></button>
    </div>
	</form>
	<p style="color:green;margin-top:20px"><?= $message ?></p>
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
	  // Optional: handle file selection
	  document.getElementById('fileInput').addEventListener('change', function () {
		if (this.files.length > 0) {
		  alert("Selected: " + this.files[0].name);
		}
	  });
	</script>

</body>
</html>
