<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nickname = ''; //$_POST['nickname'];
    $email = $_POST['email'];
    $birthdate= $_POST['dob'];
	$birthtime = $_POST['tob'];
	$birthplace = $_POST['location'];
	$gender = ''; //$_POST['gender'];
	$logtime = date("Y-m-d H:i:s");
$_SESSION['bazi_data'] = $_POST;
    // Basic validation (you can add more complex validation as needed)
 //   if (empty($username) || empty($email) || empty($password)) {
 //       echo "<p style='color: red; text-align: center;'>Please fill in all fields.</p>";
//		header("Location: index.html");
 //   } else {
        $stmt = $pdo->prepare("INSERT INTO tbl_userinfo (nickname,birthdate,birthtime,birthplace,gender,email,password,login_status,subscribe,logtime) VALUES (?,?,?,?,?,?,?,?,?,?)");
		$data = array($nickname,$birthdate,$birthtime,$birthplace,$gender,$email,'','no','no',$logtime); 
		$stmt->execute($data);
        echo "<p style='color: green; text-align: center;'>Registration successful!</p>";
		//header("Location: signin.php");
		header("Location: result.php");
 //   }
}
?>

