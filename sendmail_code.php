<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_GET['email'];
$emailpass = getenv('EMAIL_PASS');
$code = rand(00000,99999);
$mail = new PHPMailer(true);
$logtime = date("Y-m-d H:i:s");

function generateSecurePassword($length = 8) {
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $digits = '0123456789';
    $all = $upper . $lower . $digits;

    // Ensure at least one of each
    $password = [
        $upper[random_int(0, strlen($upper) - 1)],
        $lower[random_int(0, strlen($lower) - 1)],
        $digits[random_int(0, strlen($digits) - 1)]
    ];

    // Fill the rest with random characters
    for ($i = 3; $i < $length; $i++) {
        $password[] = $all[random_int(0, strlen($all) - 1)];
    }

    // Shuffle the password to avoid predictable pattern
    shuffle($password);

    return implode('', $password);
}

// Example usage
$pass = generateSecurePassword();

$stmt = $pdo->prepare("SELECT userid, email FROM tbl_userinfo WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
$userid = $user['userid'];

$stmt2 = $pdo->prepare("INSERT INTO tbl_reset_email (userid, email, code, logtime) VALUES (?, ?, ?, ?)");
$stmt2->execute([$userid, $email, $pass, $logtime]);
$stmt3 = $pdo->prepare("UPDATE tbl_userinfo set password = ? where userid = ?");
$stmt3->execute([$pass, $userid]);

$message = "<html>
<body>
  <p>Your new password is <b>$pass</b></p>
  <p>Please click the link to change your new password.</p>
  <p>https://lifetune.cornerv.com/life.tune/change_password</p>
</body>
</html>";

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'lifetuneai@gmail.com'; // Your Gmail
    $mail->Password = $emailpass;   // Gmail app password (not your real password)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('lifetuneai@gmail.com', 'LIFE.TUNE');
    $mail->addAddress($email, 'Recipient');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Reset password';
    $mail->Body    = $message;

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email failed. Error: {$mail->ErrorInfo}";
}

header("Location: password_reset");
?>