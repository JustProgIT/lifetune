<?php
include 'translate.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>每日总结 - 高阶班</title>
    <link rel="stylesheet" href="zhengying.css">
    <link rel="stylesheet" href="styles.css">
    <style>
    :root{
      --content-w: 1100px;            /* wider chat area */
      --nav-h: 62px;                  /* bottom nav height */
      --composer-h: 48px;             /* will update via JS */
    }

    /* Page layout */
    html, body { height: 100%; }
    body.aichat-page{
      min-height: 100dvh;
      display: flex; flex-direction: column;
    }

    /* Centered column: chat + input share same width and are aligned */
    #chat-container{
      flex: 1 1 auto;
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      width: min(var(--content-w), calc(100% - 32px));
      margin: 0 auto;
    }

    /* More room + never hide last bubble */
    #chat-box{
      padding: 16px;
      padding-bottom: calc(16px + var(--composer-h) + var(--nav-h) + env(safe-area-inset-bottom));
    }
    .message:last-child{
      scroll-margin-bottom: calc(var(--composer-h) + var(--nav-h) + 16px + env(safe-area-inset-bottom));
    }

    /* Input bar: directly below chat, same width, centered */
    .ai-input-container{
      position: sticky;
      bottom: calc(var(--nav-h) + env(safe-area-inset-bottom));
      background: transparent;
      z-index: 2;
      display: unset; justify-content: center;
      padding: 6px 0;
    }
    .ai-input{
      width: min(var(--content-w), calc(100% - 32px));
      display: flex; align-items: center; gap: 8px;
      background: #fff;
      border: 1px solid #eee;
      border-radius: 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,.06);
      padding: 6px 8px;
    }

    /* Auto-grow input; cap growth */
    .ai-input textarea{
      flex: 1 1 auto;
      border: 0; outline: 0;
      resize: none; overflow: hidden;
      font-size: 16px; line-height: 20px;
      padding: 8px 10px;
      min-height: 28px;               /* one line */
      max-height: 40vh;               /* cap for long text */
      background: transparent;
    }

    /* Send button icon always visible (even if some JS injects text) */
    #send-btn{
      width: 42px; height: 42px;
      border-radius: 999px; border: 0;
      display: grid; place-items: center;
      background: #ffbf33; cursor: pointer;
      font-size: 0;                   /* hide accidental text like “Send” */
    }
    #send-btn .send-icon{ width: 20px; height: 20px; fill: currentColor; }

    /* Bottom nav: blurred glass + aligned items */
    .bottom-nav{
      position: fixed; left: 0; right: 0; bottom: 0;
      height: var(--nav-h);
      display: flex; justify-content: space-around; align-items: center;
      background: rgba(255,255,255,.82);
      -webkit-backdrop-filter: blur(12px);
      backdrop-filter: blur(12px);
      border-top: 1px solid rgba(0,0,0,.06);
      z-index: 1;
      padding: 0 env(safe-area-inset-left) calc(env(safe-area-inset-bottom)) env(safe-area-inset-right);
    }
    .bottom-nav .nav-item{
      flex: 1 1 0;
      height: 100%;
      display: flex; flex-direction: column;
      justify-content: center; align-items: center;
      gap: 2px;
      background: transparent; border: none;
    }
    .nav-icon{ width: 22px; height: 22px; display: block; }
    .nav-label{ font-size: 12px; line-height: 1; }

    /* (optional) emphasize active */
    .bottom-nav .nav-item.active .nav-label{ font-weight: 600; }

    </style>
</head>
<body class=aichat-page>
    <!-- Header -->
    <div class="ai-top">
      <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo">
      <h1>⚙️ 每日总结 - 高阶班</h1>
      <p>深度诊断：面对结果，反思抉择，承认企图心，对照生活，做出新决定，立刻行动。</p>
    </div>
	<div class="coach-nav">
		<a href="onboarding.php">← 返回教练选择</a> 
    </div>

    <div id="chat-container">
        <div id="chat-box">

        </div>
    </div>
    <!-- Input Container -->
    <div class="ai-input-container">
        <form id="chat-form" novalidate>
            <div class="ai-input">
                <textarea id="user-input" name="user_message" placeholder="输入文字..." autocomplete="off" rows="1" required></textarea>
                <button aria-label="Send" id="send-btn" type="submit">
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
    <script>
        // Set the chatbot type
        const CHATBOT_TYPE = 'advanced-daily';
        localStorage.setItem('chatbotType', CHATBOT_TYPE);

        document.addEventListener('DOMContentLoaded', function() {
            const loading = document.getElementById('loading');
            const chatContainer = document.getElementById('chat-container');

            fetch(`/api/history/${CHATBOT_TYPE}`, { 
              credentials: 'include' 
            }).then(response => {
                    if (response && response.ok) {
                        return response.json();
                    }
                    return { history: [] };
                })
                .then(data => {
                    // Store conversation history
                    if (data.history && data.history.length > 0) {
                        localStorage.setItem('conversationHistory', JSON.stringify(data.history));
                        displayConversationHistory(data.history);
                    }

                    loading.style.display = 'none';
                    chatContainer.style.visibility = 'visible';
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading.textContent = 'Error loading coach. Please refresh the page.';
                }).finally(() => {
            loading.style.display = 'none';
            chatContainer.style.visibility = 'visible';
          });
        });

        function displayConversationHistory(history) {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = '';

            history.forEach(message => {
                if (message.role === 'user') {
                    addMessage(message.parts[0].text, 'user');
                } else if (message.role === 'model') {
                    addMessage(message.parts[0].text, 'ai');
                }
            });
        }

        function addMessage(message, sender) {
            const chatBox = document.getElementById('chat-box');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', sender);
            messageDiv.textContent = message;
            chatBox.appendChild(messageDiv);
		  // After layout
		  requestAnimationFrame(() => {
			chatBox.scrollTop = chatBox.scrollHeight;
		  });
        }
    </script>
	<script>
  // Safely handle the non-existent #loading element in your code
  // (prevents errors when calling loading.style.*)
  const safeSet = (el, fn) => { if (el) fn(el); };

  // Dynamically set --composer-h and --nav-h so padding is always correct
  document.addEventListener('DOMContentLoaded', () => {
    const textarea  = document.getElementById('user-input');
    const composer = document.querySelector('.ai-input-container');
    const bottomNav = document.querySelector('.bottom-nav');
    const root = document.documentElement;


    // Auto-grow textarea
    const grow = () => {
      textarea.style.height = 'auto';
      textarea.style.height = Math.min(textarea.scrollHeight, window.innerHeight * 0.4) + 'px';
      updateVars();
    };
    
    textarea.addEventListener('input', grow);
    grow();

    const updateVars = () => {
      root.style.setProperty('--composer-h', (composer?.offsetHeight || 0) + 'px');
      root.style.setProperty('--nav-h', (bottomNav?.offsetHeight || 0) + 'px');

    };

    
    // Observe size changes (multiline input, responsive, mobile keyboards)
    const ro = new ResizeObserver(updateVars);
    if (composer) ro.observe(composer);
    if (bottomNav) ro.observe(bottomNav);
    window.addEventListener('resize', updateVars);
    updateVars();
  });
</script>

    <!-- Chatbot related functions -->
    <script src="nodejs/frontend_chatbot.js"></script>
</body>
</html>
