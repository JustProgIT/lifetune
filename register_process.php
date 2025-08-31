<?php
include 'config.php';
require_once 'api_handler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 /*   $nickname = $_POST['name'];    
    $birthdate= $_POST['birthdate'];
	$birthtime = $_POST['birthtime'];
	$birthplace = $_POST['location'];
	$gender = ''; //$_POST['gender']; */
	$email = $_POST['email'];
	$password = $_POST['password'];
	$logtime = date("Y-m-d H:i:s");
	//$_SESSION['bazi_data'] = $_POST;
	$hashed = password_hash($password, PASSWORD_DEFAULT);
	
        $stmt = $pdo->prepare("INSERT INTO tbl_userinfo (nickname,birthdate,birthtime,birthplace,gender,email,password,login_status,subscribe,profile_picture,logtime) VALUES ('','','','','',?,?,'','','',?)");
		$data = array($email,$hashed,$logtime); 
		$stmt->execute($data);
	
//===================Create Bazi====================================================	
	/*	$bazi_chart = getBaziCalculation($birthdate,$birthtime,$birthplace);
		$bazi_chart = str_replace('*','',$bazi_chart);
		$bazipo = strrpos($bazi_chart,")");
		$bazichart = substr($bazi_chart,0,$bazipo+1);


		$bazi_result = getkeys($birthdate,$birthtime,$birthplace);

		$bazi_result = str_replace('–','',$bazi_result);
		$bazi_result = str_replace('’','\'',$bazi_result);

		$k1po = strpos($bazi_result,"1.");
		$k2po = strpos($bazi_result,"2.");
		$k1 = substr($bazi_result,$k1po,$k2po-$k1po);
		$k2po = strpos($bazi_result,"2.");
		$k3po = strpos($bazi_result,"3.");
		$k2 = substr($bazi_result,$k2po,$k3po-$k2po);
		$k3po = strpos($bazi_result,"3.");
		$k4po = strpos($bazi_result,"4.");
		$k3 = substr($bazi_result,$k3po,$k4po-$k3po);
		$k4po = strpos($bazi_result,"4.");
		$k5po = strpos($bazi_result,"5.");
		$k4 = substr($bazi_result,$k4po,$k5po-$k4po);
		$k5po = strpos($bazi_result,"5.");
		$k6po = strpos($bazi_result,"6.");
		$k5 = substr($bazi_result,$k5po,$k6po-$k5po);
		$k6po = strpos($bazi_result,"6.");
		$k7po = strpos($bazi_result,"7.");
		$k6 = substr($bazi_result,$k6po,$k7po-$k6po);
		$k7po = strpos($bazi_result,"7.");
		$k7 = substr($bazi_result,$k7po);

		$keys7 = "$k1\r\n\r\n$k2\r\n\r\n$k3\r\n\r\n$k4\r\n\r\n$k5\r\n\r\n$k6\r\n\r\n$k7";

		$getid = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
		$getid->execute([$email]);
		$user = $getid->fetch();
		$userid = $user['userid'];

		$stmt = $pdo->prepare("INSERT INTO tbl_7keys (userid,fourpillars,7keys,logtime) VALUES (?,?,?,?)");
		$data = array($userid,$bazichart,$keys7,$logtime); 
		$stmt->execute($data);
		*/
        //echo "<p style='color: green; text-align: center;'>Registration successful!</p>";
		//header("Location: signin.php");
		header("Location: login");
 //   }
}
?>