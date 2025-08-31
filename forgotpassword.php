<?php
require 'config.php';
$result = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email-input'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = "❌ Invalid email format.";
    } else {
		$stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
		$stmt->execute([$email]);
        $result = $stmt->fetchColumn();
		if($result > 0) {
			header("Location: sendmail_code?email=$email");
			$result = "✅ Email exists in the database.";
		} else {
			$result = "❌ Email not found.";
		}
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Life.Tune - Forgot Password</title>
  <link rel="stylesheet" href="styles.css" />
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
      <p class="login-subtitle">Reset your password to continue your journey</p>
    </div>

    <!-- Forgot Password Form Card -->
    <div class="login-card">
      <form class="login-form" method="POST" onsubmit="showLoading()">
        <div class="form-group">
          <label for="email-input" class="form-label">
            <svg viewBox="0 0 24 24" class="input-icon">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            Email Address
          </label>
          <input
            type="email"
            id="email-input"
            name="email-input"
            placeholder="Enter your email"
            required
            class="form-input"
          >
          <div id="result"></div>
          <span id="txtHint" style="color:red"></span>
          <?php if ($result): ?>
            <div class="result"><?= htmlspecialchars($result) ?></div>
          <?php endif; ?>
        </div>

        <div class="form-actions">
          <button class="btn-login" type="submit" id="reset-btn">
            Reset Password
          </button>
        </div>
      </form>

      <!-- Links -->
      <div class="login-links">
        <a href="login" class="link-item">
          <svg viewBox="0 0 24 24" class="link-icon">
            <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5z"/>
          </svg>
          Back to Sign In
        </a>
      </div>
    </div>

    <div id="loadingOverlay"><img src="img/loading2.gif" alt="Loading..." /></div>
  </main>
  <script>
    const presetCode = 'a@b.com';
    function checkCode() {
      const userInput = document.getElementById("email-input").value.trim();
      const resultBox = document.getElementById("result");

      if (userInput === presetCode) {
       // document.getElementById('reset-btn').disabled = false;
		location.href = `sendmail_code?email=${encodeURIComponent(email)}`;
      } else {
	  //  document.getElementById('reset-btn').disabled = true;
        resultBox.textContent = "❌ Incorrect email. Try again.";
        resultBox.style.color = "red";
      }
    }
  </script>
  <script>
    document.getElementById('reset-btn').addEventListener('click', () => {
      const email = document.getElementById('email-input').value.trim();
      if (!email) {
        alert('Please enter a valid email address.');
        return;
      }
      // Redirect to step 2, passing email as a URL parameter
      //location.href = `sendmail_code?email=${encodeURIComponent(email)}`;
    });
  </script>
</body>
</html>
