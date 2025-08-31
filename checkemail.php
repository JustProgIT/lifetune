<?php
include 'config.php';

$email = $_REQUEST["q"];

if ($email !== "") {  
	$stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = :email LIMIT 1");
	$stmt->execute(['email' => $email]);        
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	$name = $user['email'];

	echo !isset($name) ? "ok" : "exists";
//echo " This email exists, please click sign in";
}
?>