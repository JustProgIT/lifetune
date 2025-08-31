<?php
include 'config.php';
include 'translate.php';
if(empty($_SESSION['email'])) {
	header("Location: login");
	exit;
}
$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
$userid = $user['userid'];
$today = date('Y-m-d');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Life.Tune – AI Assistant History</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    /* Fresh and Clean Design Styles - Life.Tune Color Scheme */
    .fresh-history-page {
      background: #fff2eb;
      min-height: 100vh;
      padding: 20px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .fresh-header {
      text-align: center;
      margin-bottom: 30px;
      color: white;
    }

    .fresh-header h1 {
      font-size: clamp(2.5rem, 7vh, 4rem);
      line-height: 1.2;
      margin: 0;
      color: #000000;
      animation: slideDown 0.8s ease-out 0.2s both;
      opacity: 0;
      transform: translateY(-30px);
    }

    .fresh-header p {
      font-size: clamp(1.1rem, 4vh, 1.5rem);
      opacity: 0.85;
      margin: 10px 0 0 0;
      color: #000000;
      animation: slideDown 0.8s ease-out 0.5s both;
      opacity: 0;
      transform: translateY(-30px);
    }





    .fresh-section {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
      width: 100%;
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Specific styling for Recent section to increase bottom margin */
    .fresh-section:nth-child(2) {
      margin-bottom: 100px;
    }

    .fresh-section h2 {
      color: #FF9D00;
      font-size: 1.5rem;
      font-weight: 600;
      margin: 0 0 20px 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .fresh-section h2::before {
      content: '';
      width: 4px;
      height: 20px;
      background: linear-gradient(135deg, #FF9D00, #FFB84D);
      border-radius: 2px;
    }

    .fresh-history-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .fresh-history-item {
      background: linear-gradient(135deg, #fff2eb 0%, #fff8f0 100%);
      border: 1px solid rgba(255, 157, 0, 0.1);
      border-radius: 15px;
      padding: 18px 20px;
      margin-bottom: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #444444;
      font-weight: 500;
    }

    .fresh-history-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(255, 157, 0, 0.15);
      border-color: rgba(255, 157, 0, 0.3);
    }

    .fresh-history-item::after {
      content: '→';
      color: #FF9D00;
      font-size: 1.2rem;
      font-weight: bold;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .fresh-history-item:hover::after {
      opacity: 1;
    }

    .fresh-detail-view {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 25px;
      margin-bottom: 100px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
    }

    .fresh-detail-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid rgba(255, 157, 0, 0.1);
    }

    .fresh-back-btn {
      background: linear-gradient(135deg, #FF9D00, #FFB84D);
      border: none;
      border-radius: 50%;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 157, 0, 0.3);
    }

    .fresh-back-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 20px rgba(255, 157, 0, 0.4);
    }

    .fresh-back-btn svg {
      width: 20px;
      height: 20px;
      fill: white;
    }

    .fresh-detail-title {
      color: #FF9D00;
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
    }

    .fresh-message {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
      animation: fadeInUp 0.5s ease;
    }

    .fresh-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      flex-shrink: 0;
    }

    .fresh-avatar.you {
      background: linear-gradient(135deg, #FF9D00, #FFB84D);
    }

    .fresh-avatar.ai {
      background: linear-gradient(135deg, #001423, #0A1632);
    }

    .fresh-message-body {
      flex: 1;
      background: #fff2eb;
      border-radius: 15px;
      padding: 15px 20px;
      border-left: 4px solid #FF9D00;
    }

    .fresh-message.ai .fresh-message-body {
      background: #f8f9fa;
      border-left-color: #001423;
    }

    .fresh-sender {
      font-weight: 600;
      color: #FF9D00;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }

    .fresh-message.ai .fresh-sender {
      color: #001423;
    }

    .fresh-text {
      color: #444444;
      line-height: 1.6;
      margin: 0;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fresh-empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #a0aec0;
    }

    .fresh-empty-state svg {
      width: 60px;
      height: 60px;
      margin-bottom: 15px;
      opacity: 0.5;
    }

    .fresh-empty-state h3 {
      margin: 0 0 10px 0;
      font-weight: 500;
    }

    .fresh-empty-state p {
      margin: 0;
      font-size: 0.95rem;
    }

    /* Custom styling for Chat and History button text color */
    .ai-tab#tab-chat {
      color: rgb(68, 68, 68) !important;
    }
    
    .ai-tab#tab-history {
      color: rgb(68, 68, 68) !important;
    }
    
    .ai-tab#tab-history.active {
      color: rgb(68, 68, 68) !important;
    }

    /* Override max-width constraints for wider layout */
    main.ai-page {
      max-width: 600px !important;
      width: 100% !important;
      margin: 0 auto !important;
    }
    
    .ai-top {
      max-width: 1200px !important;
    }
    
    #history-container {
      width: 100% !important;
      max-width: 1200px !important;
    }
  </style>
</head>

<body class="aichat-page">
  <main class="ai-page">
    <!-- Fresh Header -->
    <div class="fresh-header">
      <h1>AI Assistant</h1>
      <p>Your conversation history</p>
    </div>

    <!-- AI Tabs -->
    <div class="ai-tabs">
      <button class="ai-tab" id="tab-chat" onclick="location.href='aichat'">
        <div class="tab-icon">
          <svg viewBox="0 0 24 24" width="20" height="20">
            <path d="M2 3h20v14H6l-4 4V3z"/>
          </svg>
        </div>
        <span><?= $messages[$lang]['btnchat'] ?></span>
      </button>
      <button class="ai-tab active" id="tab-history" onclick="location.href=''">
        <div class="tab-icon">
          <svg viewBox="0 0 24 24" width="20" height="20">
            <path d="M12 4V1L8 5l4 4V6a6 6 0 1 1-6 6H4a8 8 0 1 0 8-8z"/>
          </svg>
        </div>
        <span><?= $messages[$lang]['btnhistory'] ?></span>
      </button>
    </div>

    <!-- Fresh History List -->
    <div id="history-container">
      <div class="fresh-section">
        <h2><?= $messages[$lang]['history_chattoday'] ?></h2>
        <ul class="fresh-history-list">
          <?php
            $getid = $pdo->prepare("SELECT DATE(logtime) as lt FROM tbl_chat_logs WHERE userid = ? and role in ('user','assistant') and logtime like '$today%' GROUP BY DATE(logtime) order by DATE(logtime) desc");
            $getid->execute([$userid]);
            if($getid->rowCount() > 0) {
              while($log = $getid->fetch()) {
                $chattime = $log['lt'];
                $date = explode(' ',$chattime);
                $dt = $date['0'];
                echo "<li class=\"fresh-history-item\" data-session=\"$dt\">$dt</li>";
              }
            } else {
              echo "<li class=\"fresh-history-item\" data-session=\"0\">No conversations today</li>";
            }
          ?>
        </ul>
      </div>
      
      <div class="fresh-section">
        <h2><?= $messages[$lang]['history_chatrecent'] ?></h2>
        <ul class="fresh-history-list">
          <?php
            $getid = $pdo->prepare("SELECT DATE(logtime) as lt FROM tbl_chat_logs WHERE userid = ? and role in ('user','assistant') and logtime not like '$today%' GROUP BY DATE(logtime) order by DATE(logtime) desc");
            $getid->execute([$userid]);
            while($log = $getid->fetch()) {
              $chattime = $log['lt'];
              $date = explode(' ',$chattime);
              $dt = $date['0'];
              echo "<li class=\"fresh-history-item\" data-session=\"$dt\">$dt</li>";
            }
          ?>
        </ul>
      </div>
    </div>

    <!-- Fresh Detail View -->
    <div class="fresh-detail-view" id="detail-view" hidden>
      <div class="fresh-detail-header">
        <button class="fresh-back-btn" id="detail-back" aria-label="Back to History">
          <svg viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <h2 class="fresh-detail-title" id="detail-title">Session Title</h2>
      </div>
      <div id="detail-chat">
        <!-- messages will be injected here -->
      </div>
    </div>
  </main>


  <!-- fixed bottom navigation -->
  <nav class="bottom-nav">
    <button class="nav-item" aria-label="Home" onclick="location.href='index'">
      <svg viewBox="0 0 24 24" class="nav-icon"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>
      <span class="nav-label"><?= $messages[$lang]['home'] ?></span>
    </button>
    <button class="nav-item active" aria-label="AI Assistant" onclick="location.href='aichat'">《》
      <span class="nav-label"><?= $messages[$lang]['aiassistant'] ?></span>
    </button>
    <button class="nav-item" aria-label="Insight" onclick="location.href='result'">☀
      <span class="nav-label"><?= $messages[$lang]['insight'] ?></span>
    </button>
    <button class="nav-item" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
      <span class="nav-label"><?= $messages[$lang]['profile'] ?></span>
    </button>
  </nav>    
  <!-- Note: we don’t render .ai-input-container here on history-page -->

  <script>
    // Grab elements
    const historyContainer = document.getElementById('history-container');
    const detailView       = document.getElementById('detail-view');
    const detailTitle      = document.getElementById('detail-title');
    const detailChat       = document.getElementById('detail-chat');
    const backBtn          = document.getElementById('detail-back');

    // Sample transcripts; in production you'd fetch these

	
    const transcripts = {
		<?php
		$getdate = $pdo->prepare("SELECT DATE(logtime) as lt FROM tbl_chat_logs WHERE userid = ? and role in ('user','assistant') GROUP BY DATE(logtime)");
		$getdate->execute([$userid]);
		while($log1 = $getdate->fetch()) {
			$chattime = $log1['lt'];
			$date = explode(' ',$chattime);
			$dt = $date['0'];
			echo "'$dt': [";
					$getmsg = $pdo->prepare("SELECT * FROM tbl_chat_logs WHERE userid = ? and role in ('user','assistant') and logtime like '$dt%' order by logtime asc");
					$getmsg->execute([$userid]);
					while($log = $getmsg->fetch()) {
						$role = $log['role'];
						if($role == 'user') { $role = 'you'; }
						if($role == 'assistant') { $role = 'ai'; }
						$chat = $log['message'];
						//$chat = str_replace('“',"",$chat);
						//$chat = str_replace('”',"",$chat);
						echo "{ sender:'$role', text:`$chat` },";
					}
			echo "],";
		}
		?>
	    '0': [
        { sender:'you', text:"No input" }

      ]
    };

    // Helper to append a message bubble
    function appendMessage(sender, text) {
      const wrapper = document.createElement('div');
      wrapper.className = `fresh-message ${sender}`;
      wrapper.innerHTML = `
        <div class="fresh-avatar ${sender}">${sender === 'you' ? 'U' : 'AI'}</div>
        <div class="fresh-message-body">
          <div class="fresh-sender">${sender === 'you' ? 'You' : 'AI Assistant'}</div>
          <div class="fresh-text">${text.replace(/\n/g, '<br>')}</div>
        </div>`;
      detailChat.appendChild(wrapper);
      detailChat.scrollTop = detailChat.scrollHeight;
    }

    // Show the detail view for a session (hides header & tabs via body class)
    function showDetail(sessionId, title) {
      // Clear previous
      detailChat.innerHTML = '';
      detailTitle.textContent = title;

      // Inject transcript
      (transcripts[sessionId] || []).forEach(msg =>
        appendMessage(msg.sender, msg.text)
      );

      // Add .detail-mode to body → hides .ai-top & .ai-tabs
      document.body.classList.add('detail-mode');

      // Toggle views
      historyContainer.hidden = true;
      detailView.hidden       = false;
	  historyContainer.style.display = 'none';

      // Scroll up
      window.scrollTo(0, 0);
    }

    // Bind click on each history-item
    document.querySelectorAll('.fresh-history-item').forEach(item => {
      item.addEventListener('click', () => {
        const sessionId = item.dataset.session;
        const title     = item.textContent.trim();
        showDetail(sessionId, title);
      });
    });

    // Back button: remove detail-mode and swap views
    backBtn.addEventListener('click', () => {
      detailView.hidden       = true;
      historyContainer.hidden = false;
	  historyContainer.style.display = 'block';
      document.body.classList.remove('detail-mode');
    });
  </script>
</body>
</html>
