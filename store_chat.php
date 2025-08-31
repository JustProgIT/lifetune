<?php
include 'config.php';

$logtime = date("Y-m-d H:i:s");
$user_input = $_POST['user_input'] ?? '';
$ai_response = $_POST['ai_response'] ?? '';
$refid = $_SESSION['referral_id'];	

if(!empty($_SESSION['email'])) {
$email = $_SESSION['email'];
$getid = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$getid->execute([$email]);
$user = $getid->fetch();
$userid = $user['userid'];
} else {
	$userid = $_SESSION['referral_id'];	
}

$stmt = $pdo->prepare("INSERT INTO tbl_chat_logs (userid,role,message,tokens,cost,refid,logtime) VALUES (?,'user',?,'','',?,?)");
$stmt->execute([$userid,$user_input,$refid, $logtime]);
	
$stmt = $pdo->prepare("INSERT INTO tbl_chat_logs (userid,role,message,tokens,cost,refid,logtime) VALUES (?,'assistant',?,'','',?,?)");
$stmt->execute([$userid,$ai_response,$refid, $logtime]);

?>