document.addEventListener('DOMContentLoaded', () => {
    // --- DOM Elements ---
    const chatBox = document.getElementById('chat-box');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const inputContainer = document.getElementById('input-container');

    let limitResetTimer = null;
    
    // --- State Management ---
    let conversationState = {
        historys: [],
        userPreferences: JSON.parse(localStorage.getItem('userPreferences') || '{}'), // Get user preferences
        isAwaitingResponse: false, // Prevents sending multiple messages at once
        limitReached: false
    };

    // --- Core Functions ---

    /* Sends a message to the backend and handles the response. */
    const sendMessage = async () => {
        const userMessageText = userInput.value.trim();
        if (userMessageText === '' || conversationState.isAwaitingResponse) {
            return;
        }

        // Lock the UI while waiting for a response
        setLoadingState(true);

        // Display the user's message in the chat
        addMessageToUI(userMessageText, 'user-message');
        userInput.value = '';

        // Call the backend and update the state
        await callChatAPI(userMessageText);
        setLoadingState(false);
    };

    /**
     * Main API call logic.
     * @param {string} userMessage - The text from the user.
     */
    const callChatAPI = async (userMessage) => {
        try {
            const response = await fetch('http://localhost:3000/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    historys: conversationState.historys,
                    userMessage: userMessage,
                    userPreferences: conversationState.userPreferences // Include user preferences

                }),
                credentials: 'include' // Add this line to include cookies

            });

            if (!response.ok) {
                const errorData = await response.json();

                // Check if this is a limit exceeded error
                if (errorData.limitExceeded) {
                    // Get the reset time from the server
                    const resetTime = new Date(data.resetTime + "Z"); // force UTC
                    const formattedTime = resetTime.toLocaleString();
                    
                    // Format error message with reset time
                    const limitMessage = `${errorData.error} Your limit will reset at ${formattedTime}.`;
                    addMessageToUI(limitMessage, 'limit-message');
                    
                    // Set UI to limit reached state
                    setLimitReachedState(true, resetTime);
                } else {
                    throw new Error(errorData.error || 'Server responded with an error');
                }
                return;
            }

            const data = await response.json();
            
            // Update state with the response from the backend
            addMessageToUI(data.modelMessage, 'bot-message');

            // Update conversation history
            conversationState.historys = data.historys || [];

        } catch (error) {
            console.error('API Call Error:', error);
            addMessageToUI(`Error: ${error.message}`, 'error-message');
            setLoadingState(false); // Unlock UI on error
        }
    };

    function updateLimitTimer(resetTime, timerElement) {
        const now = new Date();
        const timeLeft = resetTime.getTime() - now.getTime();

        if (timeLeft <= 0) {
            clearInterval(limitResetTimer);
            limitResetTimer = null;

            if (timerElement) timerElement.remove();

            // Re-enable input
            userInput.disabled = false;
            sendBtn.disabled = false;

            addMessageToUI("Your daily limit has been reset. You can continue chatting now.", "system-message");
            return;
        }

        const hours = Math.floor(timeLeft / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        timerElement.textContent = `Time until limit reset: ${hours}h ${minutes}m ${seconds}s`;
    }
    
    // Add a new function to handle limit reached state
    async function setLimitReachedState(isLimitReached, userId) {
        // Clear previous timer if any
        if (limitResetTimer) {
            clearInterval(limitResetTimer);
            limitResetTimer = null;
        }

        userInput.disabled = isLimitReached;
        sendBtn.disabled = isLimitReached;

        if (!isLimitReached) {
            // Remove timer UI if present
            const existingTimer = document.getElementById('limit-timer');
            if (existingTimer) existingTimer.remove();
            return;
        }

        // Fetch reset time from backend
        try {
            const response = await fetch(`http://localhost:3000/limit-reset?userId=${encodeURIComponent(userId)}`, {
                credentials: 'include'
            });
            const data = await response.json();
            if (!data.resetTime) return;

            const resetTime = new Date(data.resetTime);

            // Create timer element
            let timerElement = document.getElementById('limit-timer');
            if (!timerElement) {
                timerElement = document.createElement('div');
                timerElement.id = 'limit-timer';
                timerElement.className = 'limit-timer';
                inputContainer.appendChild(timerElement);
            }

            // Start countdown interval
            limitResetTimer = setInterval(() => updateLimitTimer(resetTime, timerElement), 1000);

        } catch (err) {
            console.error("Error fetching limit reset time:", err);
        }
    }

    // --- UI Helper Functions ---
    
    /**
     * Formats message text for display: bold (**text**), italics (*text*), line breaks (\n), lists, blockquotes, and URLs.
     * @param {string} text - The raw message content.
     * @returns {string} - HTML-formatted message.
     */
    const formatMessage = (text) => {
        // Escape HTML special characters first
        let formatted = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        // Bold: **text**
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Italics: *text*
        formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // URLs: http(s)://...
        formatted = formatted.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
        // Blockquotes: > text
        formatted = formatted.replace(/^&gt; (.*)$/gm, '<blockquote>$1</blockquote>');
        // Lists: lines starting with * or -
        formatted = formatted.replace(/(?:^|\n)([*\-]) (.*?)(?=\n|$)/g, function(match, p1, p2) {
            return `<li>${p2}</li>`;
        });
        // Wrap consecutive <li> in <ul>
        formatted = formatted.replace(/(<li>.*?<\/li>)+/gs, function(match) {
            return `<ul>${match}</ul>`;
        });
        // Line breaks: \n
        formatted = formatted.replace(/\n/g, '<br>');
        return formatted;
    };

    /**
     * Adds a message bubble to the chat window.
     * @param {string} text - The message content.
     * @param {string} className - The CSS class for styling ('user-message', 'bot-message', etc.).
     */
    const addMessageToUI = (text, className) => {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', className);
        messageElement.innerHTML = formatMessage(text); // Use innerHTML for formatted text
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to bottom
    };

    /**
     * Sets the loading state of the UI.
     * @param {boolean} isLoading - Whether the UI should be in a loading state.
     */
    const setLoadingState = (isLoading) => {
        conversationState.isAwaitingResponse = isLoading;

        // Only change UI state if limit hasn't been reached
        if (!conversationState.limitReached) {
            userInput.disabled = isLoading;
            sendBtn.disabled = isLoading;
            sendBtn.textContent = isLoading ? '...' : 'Send';
        }

    };


    const userPreferences = conversationState.userPreferences;
    let welcomeMessage = "Hello! I'm your AI life coach. How can I help you today?";
    
    if (Object.keys(userPreferences).length > 0) {
        // Personalize greeting if we have user preferences
        const name = userPreferences.name || "";
        const coachingStyle = userPreferences.coachingStyle || "";
        
        if (coachingStyle) {
            switch (coachingStyle) {
                case 'Gentle and nurturing':
                    welcomeMessage = `Hello! I'm here to support you in a thoughtful and caring way. What's on your mind today?`;
                    break;
                case 'Direct and actionable':
                    welcomeMessage = `Hello! I'm ready to help you find clear, practical solutions. What would you like to work on today?`;
                    break;
                case 'Reflective and thought-provoking':
                    welcomeMessage = `Hello! I'm here to help you explore your thoughts more deeply. What would you like to reflect on today?`;
                    break;
                case 'Motivational and energetic':
                    welcomeMessage = `Hello! I'm excited to help you reach your goals with energy and enthusiasm! What are we tackling today?`;
                    break;
                case 'Casual and conversational':
                    welcomeMessage = `Hey there! Let's chat about whatever's on your mind today. What's up?`;
                    break;
                default:
                    break;
            }
        }
    }
    
    addMessageToUI(welcomeMessage, 'bot-message');

    // Check if user has already hit limits
    const checkLimitStatus = async () => {
        try {
            const response = await fetch('http://localhost:3000/user-budget', {
                credentials: 'include'
            });
            
            if (response.ok) {
                const data = await response.json();
                
                // If user has no budget remaining, show limit message
                if (data.limitExceeded && data.resetTime) {
                    const resetTime = new Date(data.resetTime + "Z"); // force UTC
                    const formattedTime = resetTime.toLocaleString();
                    
                    const limitMessage = `You have reached your daily usage limit of RM 0.10. Your limit will reset at ${formattedTime}.`;
                    addMessageToUI(limitMessage, 'limit-message');
                    
                    // Set UI to limit reached state
                    setLimitReachedState(true, resetTime);
                } else if (data.remaining < 0.05) {
                    // Optional: Show warning when budget is low
                    addMessageToUI(`Warning: You have only RM ${data.remaining.toFixed(2)} remaining in your daily budget.`, "system-message");
                }
            }
        } catch (error) {
            console.error('Error checking limit status:', error);
        }
    };

    // Call the check function
    checkLimitStatus();

    // --- Event Listeners ---
    sendBtn.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});


