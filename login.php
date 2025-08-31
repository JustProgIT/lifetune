<?php
include 'config.php';
include 'translate.php';
if(!empty($_SESSION['email'])) {
	header("Location: index");
}
$logtime = date("Y-m-d H:i:s");

$login_error = '';
$user = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if( isset($_POST['email']) ) {
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);
  echo "<script>console.log('Debug Objects: " . $username == $password . "' );</script>";  
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
		$pw = $user['password'];
		if(!empty($pw) && strlen($pw) < 20) {
		$pw = password_hash($pw, PASSWORD_DEFAULT);	
        $updateStmt = $pdo->prepare("UPDATE tbl_userinfo SET password = ? WHERE email = ?");
        $updateStmt->execute([$pw, $username]);
	}
		//$hashed_password = password_hash($pw, PASSWORD_DEFAULT);		
        
        if ($user && password_verify($password, $pw)) {
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['email'] = $username;
			
			$stmt = $pdo->prepare("INSERT INTO tbl_login_log (userid,email,login_status,logtime) VALUES (?,?,?,?)");
			$data = array($user['userid'],$username,'active',$logtime); 
			$stmt->execute($data);
			
			header('Location: index');

            exit;
        } else {
            $login_error = 'Invalid username or password';
        }
    } else {
        $login_error = 'Please enter both username and password';
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Life.Tune - Login</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
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
      <p class="login-subtitle"><?= $messages[$lang]['login_head'] ?></p>
    </div>

    <!-- Login Form Card -->
    <div class="login-card">
<?php if (!empty($login_error)): ?>
        <div class="alert alert-danger"><?php echo $login_error; ?></div>
        <?php endif; ?>		

    <form class="login-form" action="login" method="post">
        <div class="form-group">
          <label for="email" class="form-label">
            <svg viewBox="0 0 24 24" class="input-icon">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <?= $messages[$lang]['email'] ?>
          </label>
        <input
          type="email"
		  id="email"
          name="email"
            placeholder="<?= $messages[$lang]['login_putemail'] ?>"
          required
            class="form-input"
          >
        </div>
        
        <div class="form-group">
          <label for="password" class="form-label">
            <svg viewBox="0 0 24 24" class="input-icon">
              <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <?= $messages[$lang]['password'] ?>
      </label>
        <input
          type="password"
		  id="password"
          name="password"
            placeholder="<?= $messages[$lang]['login_putpassword'] ?>"
          required
            class="form-input"
        >
	</div>

        <div class="form-actions">
          <button class="btn-login" type="submit">
            <?= $messages[$lang]['login_signin'] ?>
          </button>
        </div>
</form>  

      <!-- Divider -->
      <div class="login-divider">
        <span>or</span>
      </div>

      <!-- Google Sign In -->
      <div class="google-signin">
  <div id="g_id_onload"
       data-client_id="195041945057-95isdb5ascqeme9sg23c93niscnvjo0o.apps.googleusercontent.com"
       data-context="signin"
       data-callback="handleGoogleResponse"
       data-ux_mode="popup"
       data-auto_prompt="false">
  </div>

  <div class="g_id_signin"
       data-type="standard"
       data-shape="rectangular"
       data-theme="outline"
       data-size="large"
       data-logo_alignment="left"
             data-text="signin_with">
        </div>
  </div>

      <!-- Links -->
      <div class="login-links">
        <a href="register" class="link-item">
          <svg viewBox="0 0 24 24" class="link-icon">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          <?= $messages[$lang]['register'] ?>
        </a>
        <a href="forgotpassword" class="link-item">
          <svg viewBox="0 0 24 24" class="link-icon">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <?= $messages[$lang]['forgotpassword'] ?>
        </a>
      </div>
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
      <span class="nav-label"><?= $messages[$lang]['setting'] ?></span>
    </button>
  </nav> 
  
  <script>
    // GOOGLE
	function handleGoogleResponse(response) {
	  const user = jwt_decode(response.credential);
	  const name = user.name;
	  const email = user.email;
	  const picture = user.picture;
	  sendToBackend(email, name, picture, 'google');
	}
    // Send data to PHP
    function sendToBackend(email, name, picture, provider) {
      fetch("login_google", {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, name, picture, provider })
      }).then(res => res.json())
        .then(data => {
        if (data.success) {
          // ✅ Redirect to index page
          window.location.href = "index";
        } else {
          console.log("Backend:", data.success);
        }
      });
    }
  </script> 
</body>
</html>
