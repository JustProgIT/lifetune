<?php
include 'config.php';
include 'translate.php';
if(empty($_SESSION['email'])) {
	//header("Location: login");
}

$userid = 'no';
if(isset($_GET['id'])) {
	$userid = $_GET['id'];
	$chatdate = $_GET['date'];
	$getid = $pdo->prepare("SELECT * FROM `tbl_chat_history_stage` WHERE userid = ? and logtime LIKE ? ORDER BY `id` DESC LIMIT 1");
	$getid->execute([$userid,$chatdate . '%']);
	$log = $getid->fetch();
	if (!$log) {
		$history = '[]'; 
		$stage = 'issue';
		$realitySummary = null;
		$msg = $messages[$lang]['conchat'];
	} else {		
		$history = $stage = (isset($log['historys']) && !empty($log['historys'])) ? $log['historys'] : '[]';
		$stage = (isset($log['stage']) && !empty($log['stage'])) ? $log['stage'] : 'issue';
		$realitySummary = $log['realitySummary'];
		$msg = 'yes';
	}
	
} else {
	if(isset($_GET['chat'])) {
		$history = '[]'; 
		$stage = 'issue';
		$realitySummary = null;
		$msg = 'yes';
	} else {
		$history = '[]'; 
		$stage = 'issue';
		$realitySummary = null;
		$msg = '';
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Life Coach</title>
    <link rel="stylesheet" href="zhengying.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Initially hide chat until preferences are confirmed */
        #chat-container {
            visibility: hidden;
        }
    </style>
</head>
<body class=aichat-page>
    <!-- Header -->
    <div class="ai-top">
      <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo">
      <h2 class="chat-stage-title" id="chat-stage-title"><?= $messages[$lang]['aichat_head'] ?></h2>
    </div>

    <div id="chat-container">
        <div id="chat-box">
            
        </div>
    </div>

    <!-- Input Container -->
    <div class="ai-input-container">
        <form id="chat-form" novalidate>
            <div class="ai-input">
                <input type="text" id="user-input" name="user_message" placeholder="Share what's on your mind..." autocomplete="off" required>
                <button aria-label="Send" id="send-btn">
                    <svg viewBox="0 0 24 24" class="send-icon">
                        <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <nav class="bottom-nav">
        <button class="nav-item active" aria-label="Home" onclick="location.href='index'">
            <svg viewBox="0 0 24 24" class="nav-icon"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>
            <span class="nav-label" id="home" ><?= $messages[$lang]['home'] ?></span>
        </button>
        <button class="nav-item" aria-label="AI Assistant" onclick="location.href='aichat'">《》
            <span class="nav-label" id="aiassistant"><?= $messages[$lang]['aiassistant'] ?></span>
        </button>
        <button class="nav-item" aria-label="Insight" onclick="location.href='result'">☀
            <span class="nav-label" id="insight"><?= $messages[$lang]['insight'] ?></span>
        </button>
        <button class="nav-item" aria-label="Profile" id="profile-btn" onclick="location.href='profile'">⛯
            <span class="nav-label" id="profile"><?= $messages[$lang]['profile'] ?></span>
        </button>
    </nav> 

    <!-- Preferences status indicator -->
    <div id="preferences-status" style="position: fixed; bottom: 10px; left: 10px; 
        background: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; 
        font-size: 12px; cursor: pointer;">
        Check Preferences
    </div>

    <!-- User preferenences check from localStorage-->
    <script>
        (async function() {
            try {
                const userId = "user123"; // replace with real session/user id
                const storedPrefs = localStorage.getItem("userPreferences");

                if (!storedPrefs) {
                // Redirect to questionnaire if no preferences
                window.location.href = "questionaire";
                } else {
                // Preferences exist, show chat
                document.getElementById("chat-container").style.visibility = "visible";

                // Keep preferences parsed in case you need them
                const preferences = JSON.parse(storedPrefs);
                console.log("Loaded preferences:", preferences);
                }

            } catch (err) {
                alert("Error checking preferences: " + err);
                console.error(err);
            }
        })();
    </script>

    <!-- Chatbot related functions -->
    <script src="nodejs/frontend_chatbot.js"></script>

    <!-- Console log user preferences from localStorage-->
    <script>
        document.getElementById('preferences-status').addEventListener('click', function() {
            try {
                const storedPrefs = localStorage.getItem("userPreferences");

                if (storedPrefs) {
                const preferences = JSON.parse(storedPrefs);
                console.log("User has preferences:", preferences);

                // Example: update UI with preferences
                // document.getElementById("someElement").textContent = JSON.stringify(preferences);

                } else {
                console.log("User has no saved preferences");
                }

            } catch (err) {
                console.error("Error checking for existing preferences:", err);
            }
        });
    </script>

    <!-- Adjust the chat stage title -->
    <script>
        const updateStageTitle = (stage) => {
            const stageTitle = document.getElementById('chat-stage-title');
            if (stageTitle) {
                switch(stage) {
                case 'issue':
                    stageTitle.textContent = 'Clarifying Issue for a Personalized Advice';
                    break;
                case 'transition':
                    stageTitle.textContent = 'Setting a Goal and Solution for a better Future';
                    break;
                case 'solution':
                    stageTitle.textContent = 'Setting a Goal and Solution for a better Future';
                    break;
                default:
                    stageTitle.textContent = 'Your AI Wellness Companion';
                    break;
                }
                // Show the title in chat mode
                if (body.classList.contains('chat-mode')) {
                stageTitle.style.opacity = '1';
                stageTitle.style.transform = 'translateY(0)';
                }
            }
        };
    </script>

</body>
</html>
