<?php
include 'config.php';

$logtime = date("Y-m-d H:i:s");
$historys = $_POST['historys'] ?? '';
$stage = $_POST['stage'] ?? '';
$reality_summary = $_POST['reality_summary'] ?? '';

if(!empty($_SESSION['email'])) {
$email = $_SESSION['email'];
$getid = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$getid->execute([$email]);
$user = $getid->fetch();
$userid = $user['userid'];
} else {
	$userid = $_SESSION['referral_id'];	
}

$stmt = $pdo->prepare("INSERT INTO tbl_chat_history_stage (userid,historys,stage,realitySummary,logtime) VALUES (?,?,?,?,?)");
$stmt->execute([$userid,$historys,$stage,$reality_summary, $logtime]);
?>
