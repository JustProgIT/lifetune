<?php
include 'config.php';
include 'translate.php';
$login_sta = empty($_SESSION['bazi_data']) ? 'no' : 'yes';

if(empty($_SESSION['email'])) {
	//header("Location: login");
	$k1 = '';
	$k2 = '';
	$k3 = '';
	$k4 = '';
	$k5 = '';
	$k6 = '';
	$k7 = '';
	$txt = 'Please login to find the insight of your life.';
	$btn = 'Login';
	$link = 'login';
} else {
$email = $_SESSION['email'];
require_once 'api_handler.php';
$logtime = date("Y-m-d H:i:s");
$txt = 'You are… insightful, empathetic, and deeply aware.';
$btn = 'Explore More';
$link = 'aichat';

$getid = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$getid->execute([$email]);
$user = $getid->fetch();
$userid = $user['userid'];
$dob = $user['birthdate'];
$tob = $user['birthtime'];
$location = $user['birthplace'];

$prepare7keys = $pdo->prepare("SELECT * FROM tbl_7keys WHERE userid = ?");
$prepare7keys->execute([$userid]);
$get7keys = $prepare7keys->fetch();

$k1 = '';
$k2 = '';
$k3 = '';
$k4 = '';
$k5 = '';
$k6 = '';
$k7 = '';

if(empty($dob)) {
	header("Location: birthdate");
	exit;
	
} else {	

if(!empty($get7keys)) {
	$sevenkeys = $get7keys['7keys'];
	
	$k1po = strpos($sevenkeys,"1.");
	$k2po = strpos($sevenkeys,"2.");
	$k1 = substr($sevenkeys,$k1po,$k2po-$k1po);
	$k2po = strpos($sevenkeys,"2.");
	$k3po = strpos($sevenkeys,"3.");
	$k2 = substr($sevenkeys,$k2po,$k3po-$k2po);
	$k3po = strpos($sevenkeys,"3.");
	$k4po = strpos($sevenkeys,"4.");
	$k3 = substr($sevenkeys,$k3po,$k4po-$k3po);
	
	$k4po = strpos($sevenkeys,"4.");
	$k5po = strpos($sevenkeys,"5.");
	$k4 = substr($sevenkeys,$k4po,$k5po-$k4po);
	$k5po = strpos($sevenkeys,"5.");
	$k6po = strpos($sevenkeys,"6.");
	$k5 = substr($sevenkeys,$k5po,$k6po-$k5po);
	$k6po = strpos($sevenkeys,"6.");
	$k7po = strpos($sevenkeys,"7.");
	$k6 = substr($sevenkeys,$k6po,$k7po-$k6po);
	$k7 = substr($sevenkeys,$k7po);
	
} else {


$bazi_chart = getBaziCalculation($dob,$tob,$location,$messages[$lang]['uselanguage']);
$bazi_chart = str_replace('*','',$bazi_chart);
$bazipo = strrpos($bazi_chart,")");
$bazichart = substr($bazi_chart,0,$bazipo+1);

$bazi_result = getkeys($dob,$tob,$location,$messages[$lang]['uselanguage']);
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


// If API call failed, redirect back with error
if (!$bazi_result || isset($bazi_result['error'])) {
    $_SESSION['error'] = $bazi_result['error'] ?? 'Failed to calculate BaZi. Please try again.';
	echo $_SESSION['error'] ;
    header('Location: index.php');
    exit;
}

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
	$k7 = substr($bazi_result,$k7po);
}
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Life.Tune – Your Insight</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body style="background-image: url('img/computer background LIFE.TUNE.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <header class="site-header">
    <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo">
  </header>

  <main class="result" style="text-align: center; align-items: center; padding: clamp(1rem,5vh,3rem) 1rem 0;">
    <!-- Heading -->
    <div class="result-text" style="text-align: center; max-width: min(90%,600px); margin-bottom: clamp(2rem,5vh,4rem);">
      <h1 style="font-size: clamp(2.5rem,5vw,3.5rem); line-height: 1.2; color: #444444; margin-bottom: clamp(1rem,3vh,1.5rem);"><?= $messages[$lang]['result_txt'] ?></h1>
    </div>
<?php 
	if(empty($_SESSION['email'])) {
		echo "<button class=\"btn-primary\" id=\"explore-btn\" style=\"display: block; margin: 1rem auto 5rem auto; width: fit-content;\">".$messages[$lang]['login']."</button>";
	} else {
?>
    <!-- Cards -->
    <div class="result-cards" style="display: flex; flex-direction: column; gap: clamp(1rem,3vh,1.5rem); width: 100%; max-width: min(90%,600px); margin-bottom: clamp(2rem,5vh,3rem); align-items: center;">
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key1'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo "$k1"; ?></p>
      </div>
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key2'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo $k2; ?></p>
      </div>
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key3'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo $k3; ?></p>
      </div>
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key4'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo $k4; ?></p>
      </div>
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key5'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo $k5; ?></p>
      </div>
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key6'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo $k6; ?></p>
      </div>
      <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 1.5rem; padding: clamp(1.5rem,4vh,2rem); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: clamp(1.1rem,3vw,1.3rem); color: #FF9D00; margin-bottom: 0.75rem; font-weight: 600;"><?= $messages[$lang]['result_key7'] ?></h2>
        <p style="font-size: clamp(0.9rem,2.5vw,1rem); line-height: 1.6; color: #444444; margin: 0;"><?php echo $k7; ?></p>
      </div>
    </div>

    <!-- CTA -->
    <button class="btn-primary" id="explore-btn" style="display: block; margin: 1rem auto 5rem auto; width: fit-content;"><?= $messages[$lang]['result_btn'] ?></button>
<?php
	}
?>
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
    <button class="nav-item active" aria-label="Insight" onclick="location.href='result'">☀
      <span class="nav-label"><?= $messages[$lang]['insight'] ?></span>
    </button>
    <button class="nav-item" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
      <span class="nav-label"><?= $messages[$lang]['profile'] ?></span>
    </button>
  </nav> 

  <script>
  
  
    document.getElementById('explore-btn').addEventListener('click', () => {
      window.location.href = '<?php echo $link; ?>';
    });
  </script>
</body>
</html>
