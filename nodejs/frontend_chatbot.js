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

  // Determine chatbot type (stored when user selected a coach)
  const chatbotType = localStorage.getItem('chatbotType');

  // Welcome messages for each chatbot type
  const welcomePrompts = {
      'basic-daily': '今天发生了什么结果？只说事实（你跟谁的结果怎样、你的事业目标进展如何）。',
      'advanced-daily': '今天发生了什么结果（你跟谁的结果怎样、你的事业目标进展如何）？只要焦点在外，就能容易看清真相...',
      'outcome-reflection': '你的誓言是什么？**你是谁？**',
      'decision-making': '你现在有什么选择困扰吗？告诉我发生了什么事情，让你那么纠结。'
  };

  // Choose welcome message
  const initialWelcome = welcomePrompts[chatbotType];

  // Seed history with the initial system / model greeting
  conversationState.historys.push({
      role: 'model',
      parts: [{ text: initialWelcome }]
  });

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

    const chatbotType = localStorage.getItem('chatbotType') || 'basic-daily';

    const response = await fetch('/api/postChat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        historys: conversationState.historys,
        userMessage: userMessage,
        userPreferences: conversationState.userPreferences,
        chatbotType  
      })
    });

    if (!response.ok) {
        const errorData = await response.json();
        // Check if this is a limit exceeded error
        if (errorData.limitExceeded) {
            const resetTime = new Date(errorData.resetTime);
            const formattedTime = resetTime.toLocaleString();
            const limitMessage = `${errorData.error} Your limit will reset at ${formattedTime}.`;
            addMessageToUI(limitMessage, 'limit-message');
            setLimitReachedState(true, resetTime);
        } else {
            throw new Error(errorData.error || 'Server responded with an error');
        }
        return; // stop further processing
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
    setLoadingState(false);
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
                `You have reached your daily usage limit. Your limit will reset at ${resetTime.toLocaleString()}.`,
                'limit-message'
            );
            setLimitReachedState(true, data.userId);
        } else if (data.remaining < 0.05) {
            addMessageToUI(
                `Warning: You can only ask 1 more question.`,
                'system-message'
            );
        }

    } catch (err) {
        console.error('❌ Error checking limit status:', err);
        addMessageToUI(`Error: ${err.message}`, 'error-message');
    }
};




  // --- Welcome Message ---

  addMessageToUI(initialWelcome, 'bot-message');

  // --- Event Listeners ---
  sendBtn.addEventListener('click', sendMessage);
  userInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
  });

  // --- Initialization ---
  checkLimitStatus();
});
