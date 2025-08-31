<?php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$name = $data['name'] ?? '';
$picture = $data['picture'] ?? '';
$provider = $data['provider'] ?? '';
$logtime = date("Y-m-d H:i:s");
$_SESSION['email'] = $email;

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    // Insert new user
	$stmt = $pdo->prepare("INSERT INTO tbl_userinfo (nickname,birthdate,birthtime,birthplace,gender,email,password,login_status,subscribe,profile_picture,logtime) VALUES (?,'','','','',?,?,'','',?,?)");
    $stmt->execute([$name,$email,$provider,$picture,$logtime]);
    echo json_encode(["success" => true,"status" => "registered"]);
} else {
    echo json_encode(["success" => true]);
}
?>
