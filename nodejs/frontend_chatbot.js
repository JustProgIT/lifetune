document.addEventListener('DOMContentLoaded', () => {
  // --- DOM Elements ---
  const chatBox = document.getElementById('chat-box');
  const userInput = document.getElementById('user-input');
  const sendBtn = document.getElementById('send-btn');
  const inputContainer = document.getElementById('input-container');

  // --- State Management ---
  const conversationState = {
    historys: [],
    userPreferences: JSON.parse(localStorage.getItem('userPreferences') || '{}'),
    isAwaitingResponse: false,
    limitReached: false,
  };

  let limitResetTimer = null;

  // --- UI Helpers ---

  const formatMessage = (text) => {
    let formatted = text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Bold
      .replace(/\*(.*?)\*/g, '<em>$1</em>') // Italics
      .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>') // URLs
      .replace(/^&gt; (.*)$/gm, '<blockquote>$1</blockquote>') // Blockquotes
      .replace(/(?:^|\n)([*\-]) (.*?)(?=\n|$)/g, (_, __, p2) => `<li>${p2}</li>`)
      .replace(/(<li>.*?<\/li>)+/gs, (match) => `<ul>${match}</ul>`)
      .replace(/\n/g, '<br>');
    return formatted;
  };

  /**
 * Adds a styled chat message to the chat box.
 * @param {string} text - The message content.
 * @param {string} type - 'user-message', 'bot-message', 'system-message', 'error-message', etc.
 */
const addMessageToUI = (text, type) => {
    const chatBox = document.getElementById('chat-box');

    // Container for single message
    const messageContainer = document.createElement('div');
    messageContainer.classList.add('chat-message', type);

    // Optional: add avatar for bot/user
    const avatar = document.createElement('div');
    avatar.classList.add('chat-avatar');
    avatar.textContent = type === 'bot-message' ? '🤖' : type === 'user-message' ? '🧑' : 'ℹ️';
    messageContainer.appendChild(avatar);

    // Message bubble
    const bubble = document.createElement('div');
    bubble.classList.add('chat-bubble');
    bubble.innerHTML = formatMessage(text);
    messageContainer.appendChild(bubble);

    // Optional timestamp
    const timestamp = document.createElement('div');
    timestamp.classList.add('chat-timestamp');
    const now = new Date();
    timestamp.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    messageContainer.appendChild(timestamp);

    // Add to chat
    chatBox.appendChild(messageContainer);
    chatBox.scrollTop = chatBox.scrollHeight;
};


  const setLoadingState = (isLoading) => {
    conversationState.isAwaitingResponse = isLoading;

    if (!conversationState.limitReached) {
      userInput.disabled = isLoading;
      sendBtn.disabled = isLoading;
      sendBtn.textContent = isLoading ? '...' : 'Send';
    }
  };

  // --- Limit Handling ---

  const updateLimitTimer = (resetTime, timerElement) => {
    const now = new Date();
    const timeLeft = resetTime.getTime() - now.getTime();

    if (timeLeft <= 0) {
      clearInterval(limitResetTimer);
      limitResetTimer = null;
      timerElement?.remove();
      userInput.disabled = false;
      sendBtn.disabled = false;
      addMessageToUI('Your daily limit has been reset. You can continue chatting now.', 'system-message');
      return;
    }

    const hours = Math.floor(timeLeft / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

    timerElement.textContent = `Time until limit reset: ${hours}h ${minutes}m ${seconds}s`;
  };

  const setLimitReachedState = async (isLimitReached, userId) => {
    if (limitResetTimer) {
      clearInterval(limitResetTimer);
      limitResetTimer = null;
    }

    conversationState.limitReached = isLimitReached;
    userInput.disabled = isLimitReached;
    sendBtn.disabled = isLimitReached;

    if (!isLimitReached) {
      document.getElementById('limit-timer')?.remove();
      return;
    }

    try {
      const response = await fetch(`http://localhost:3000/limit-reset?userId=${encodeURIComponent(userId)}`, {
        credentials: 'include',
      });
      const data = await response.json();
      if (!data.resetTime) return;

      const resetTime = new Date(data.resetTime);
      let timerElement = document.getElementById('limit-timer');
      if (!timerElement) {
        timerElement = document.createElement('div');
        timerElement.id = 'limit-timer';
        timerElement.className = 'limit-timer';
        inputContainer.appendChild(timerElement);
      }

      limitResetTimer = setInterval(() => updateLimitTimer(resetTime, timerElement), 1000);
    } catch (err) {
      console.error('Error fetching limit reset time:', err);
    }
  };

  // --- API Calls ---

  // Sending a chat message via PHP proxy
const callChatAPI = async (userMessage) => {
  try {
    const response = await fetch('/api/postChat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        historys: conversationState.historys,
        userMessage: userMessage,
        userPreferences: conversationState.userPreferences
      })
    });

    if (!response.ok) {
      console.error(`❌ API call failed with status ${response.status} (${response.statusText})`);
      const errorData = await response.json().catch(() => ({ error: 'Unable to parse error response' }));
      addMessageToUI(`Error: ${errorData.error || 'Unknown error occurred'}`, 'error-message');
      return;
    }

    const data = await response.json();

    if (!data.modelMessage) {
      console.warn('⚠️ Response received but no modelMessage found', data);
      addMessageToUI('Warning: Response was empty or invalid.', 'system-message');
      return;
    }

    addMessageToUI(data.modelMessage, 'bot-message');

    // Update conversation history
    conversationState.historys = data.historys || [];

  } catch (error) {
    console.error('❌ API Call Error:', error);
    addMessageToUI(`Error: ${error.message}`, 'error-message');
  } finally {
    setLoadingState(false);
  }
};



  const sendMessage = async () => {
    const message = userInput.value.trim();
    if (!message || conversationState.isAwaitingResponse) return;

    setLoadingState(true);
    addMessageToUI(message, 'user-message');
    userInput.value = '';
    await callChatAPI(message);
  };

    const checkLimitStatus = async () => {
    try {
        const response = await fetch('/api/getUserBudget.php');

        if (!response.ok) {
            console.error(`❌ Failed to fetch user budget: ${response.status} (${response.statusText})`);
            addMessageToUI('Error: Could not retrieve budget information.', 'error-message');
            return;
        }

        const data = await response.json();

        if (data.limitExceeded && data.resetTime) {
            const resetTime = new Date(data.resetTime);
            addMessageToUI(
                `You have reached your daily usage limit of RM 0.10. Your limit will reset at ${resetTime.toLocaleString()}.`,
                'limit-message'
            );
            setLimitReachedState(true, data.userId);
        } else if (data.remaining < 0.05) {
            addMessageToUI(
                `Warning: Only RM ${data.remaining.toFixed(2)} remaining in your daily budget.`,
                'system-message'
            );
        }

    } catch (err) {
        console.error('❌ Error checking limit status:', err);
        addMessageToUI(`Error: ${err.message}`, 'error-message');
    }
};




  // --- Welcome Message ---

  const showWelcomeMessage = () => {
    const prefs = conversationState.userPreferences;
    let message = "Hello! I'm your AI life coach. How can I help you today?";

    if (prefs.coachingStyle) {
      switch (prefs.coachingStyle) {
        case 'Gentle and nurturing':
          message = "Hello! I'm here to support you in a thoughtful and caring way. What's on your mind today?";
          break;
        case 'Direct and actionable':
          message = "Hello! I'm ready to help you find clear, practical solutions. What would you like to work on today?";
          break;
        case 'Reflective and thought-provoking':
          message = "Hello! I'm here to help you explore your thoughts more deeply. What would you like to reflect on today?";
          break;
        case 'Motivational and energetic':
          message = "Hello! I'm excited to help you reach your goals with energy and enthusiasm! What are we tackling today?";
          break;
        case 'Casual and conversational':
          message = "Hey there! Let's chat about whatever's on your mind today. What's up?";
          break;
      }
    }

    addMessageToUI(message, 'bot-message');
  };

  // --- Event Listeners ---
  sendBtn.addEventListener('click', sendMessage);
  userInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
  });

  // --- Initialization ---
  showWelcomeMessage();
  checkLimitStatus();
});
