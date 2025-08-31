<?php
include 'config.php';
$logtime = date("Y-m-d H:i:s");

$stmt = $pdo->prepare("INSERT INTO tbl_login_log (userid,email,login_status,logtime) VALUES (?,?,?,?)");
$userid = empty($_SESSION['userid']) ? '' : $_SESSION['userid'];
$data = array($userid,$_SESSION['email'],'inactive',$logtime); 
$stmt->execute($data);

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index');
exit;
?>