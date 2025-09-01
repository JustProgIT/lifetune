<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Before We Begin - Tell Us About Yourself</title>
    <meta name="description" content="Questionnaire to personalize your AI coaching experience. Select your preferences for age group, occupation, coaching style and more.">
    <style>
        :root {
            --primary-color: #007bff;
            --hover-color: #0069d9;
            --background-color: #f4f4f9;
            --card-bg: white;
            --text-color: #333;
            --border-color: #ccc;
        }

        body {
            font-family: sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .questionnaire-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }

        .category-title {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 30px 0 15px;
        }

        .question {
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .question-text {
            font-weight: 600;
            margin-bottom: 15px;
        }

        .options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .option-btn {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 8px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .option-btn:hover {
            background-color: #f0f5ff;
            border-color: var(--primary-color);
        }

        .option-btn.selected {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .submit-container {
            text-align: center;
            margin-top: 30px;
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: var(--hover-color);
        }

        .submit-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .progress-container {
            margin-bottom: 20px;
        }

        .progress-bar {
            height: 8px;
            background-color: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: var(--primary-color);
            width: 0%;
            transition: width 0.3s ease;
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            
            .questionnaire-card {
                padding: 15px;
            }
        }

        /* Focus styles for keyboard navigation */
        .option-btn:focus {
            outline: 3px solid #4a86e8;
            outline-offset: 2px;
        }
        
        /* Better contrast */
        .option-btn {
            /* existing styles */
            color: #333; /* Darker text for better contrast */
        }
        
        /* Skip to content link for keyboard users */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #007bff;
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s;
        }
        
        .skip-link:focus {
            top: 0;
        }

        /* ARIA live region for screen readers */
        .sr-feedback {
            border: 0;
            clip: rect(0, 0, 0, 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }
    </style>
</head>
<body>
    <!-- Skip to content link -->
    <a href="#questionnaire" class="skip-link">Skip to questionnaire</a>
    
    <!-- ARIA live region for dynamic updates -->
    <div class="sr-feedback" aria-live="polite" id="screenReaderFeedback"></div>
    
    <div class="container">
        <div class="questionnaire-card" id="questionnaire">
            <h1 id="questionnaire-title">Before We Begin</h1>
            <p style="text-align: center; margin-bottom: 30px;">Please tell us a bit about yourself so we can personalize your experience.</p>
            
            <div class="progress-container" aria-hidden="true">

                <!-- Hidden from screen readers but visible for sighted users -->
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <p style="text-align: right; font-size: 14px;"><span id="completedQuestions">0</span>/8 Questions</p>
            </div>

            <!-- Screen reader only progress info -->
            <p class="sr-only" aria-live="polite" id="progressText">0 of 8 questions completed</p>

            <div class="category-title" id="demographic-section">📌 Demographic & Life Context</div>
            
            <div class="question" data-question="ageGroup" role="group" aria-labelledby="age-question">
                <div class="question-text" id="age-question">1. Age Group</div>
                <div class="options">
                    <button class="option-btn" data-value="Teen" aria-pressed="false">Teen</button>
                    <button class="option-btn" data-value="Young Adult" aria-pressed="false">Young Adult</button>
                    <button class="option-btn" data-value="Adult" aria-pressed="false">Adult</button>
                    <button class="option-btn" data-value="Middle-age" aria-pressed="false">Middle-age</button>
                    <button class="option-btn" data-value="Senior" aria-pressed="false">Senior</button>
                </div>
            </div>
            
            <div class="question" data-question="occupation" role="group" aria-labelledby="occupation-question">
                <div class="question-text" id="occupation-question">2. Occupation or Professional Role</div>
                <div class="options">
                    <button class="option-btn" data-value="Entrepreneur" aria-pressed="false">Entrepreneur</button>
                    <button class="option-btn" data-value="Student" aria-pressed="false">Student</button>
                    <button class="option-btn" data-value="Homemaker" aria-pressed="false">Homemaker</button>
                    <button class="option-btn" data-value="Corporate Professional" aria-pressed="false">Corporate Professional</button>
                    <button class="option-btn" data-value="Freelancer" aria-pressed="false">Freelancer</button>
                    <button class="option-btn" data-value="Other" aria-pressed="false">Other</button>
                </div>
            </div>

            <div class="question" data-question="livingSituation" role="group" aria-labelledby="living-situation-question">
                <div class="question-text" id="living-situation-question">3. Living Situation</div>
                <div class="options">
                    <button class="option-btn" data-value="Living alone" aria-pressed="false">Living alone</button>
                    <button class="option-btn" data-value="With family" aria-pressed="false">With family</button>
                    <button class="option-btn" data-value="With roommates" aria-pressed="false">With roommates</button>
                    <button class="option-btn" data-value="With partner/spouse" aria-pressed="false">With partner/spouse</button>
                </div>
            </div>

            <div class="question" data-question="relationshipStatus" role="group" aria-labelledby="relationship-status-question">
                <div class="question-text" id="relationship-status-question">4. Relationship Status</div>
                <div class="options">
                    <button class="option-btn" data-value="Single" aria-pressed="false">Single</button>
                    <button class="option-btn" data-value="Married" aria-pressed="false">Married</button>
                    <button class="option-btn" data-value="In a relationship" aria-pressed="false">In a relationship</button>
                    <button class="option-btn" data-value="Other" aria-pressed="false">Other</button>
                </div>
            </div>

            <div class="category-title" id="personality-communication-style">📌 Personality & Communication Style</div>

            <div class="question" data-question="personalityType" role="group" aria-labelledby="personality-type-question">
                <div class="question-text" id="personality-type-question">5. Personality Type</div>
                <div class="options">
                    <button class="option-btn" data-value="Introvert" aria-pressed="false">Introvert</button>
                    <button class="option-btn" data-value="Extrovert" aria-pressed="false">Extrovert</button>
                    <button class="option-btn" data-value="Neutral" aria-pressed="false">Neutral/Ambivert</button>
                </div>
            </div>
            
            <div class="question" data-question="coachingStyle" role="group" aria-labelledby="coaching-style-question">
                <div class="question-text" id="coaching-style-question">6. Preferred Coaching Style/Tone</div>
                <div class="options">
                    <button class="option-btn" data-value="Gentle and nurturing" aria-pressed="false">Gentle and nurturing</button>
                    <button class="option-btn" data-value="Direct and actionable" aria-pressed="false">Direct and actionable</button>
                    <button class="option-btn" data-value="Reflective and thought-provoking" aria-pressed="false">Reflective and thought-provoking</button>
                    <button class="option-btn" data-value="Motivational and energetic" aria-pressed="false">Motivational and energetic</button>
                    <button class="option-btn" data-value="Casual and conversational" aria-pressed="false">Casual and conversational</button>
                </div>
            </div>
            
            <div class="category-title" id="goals-preferences">📌 Goals & Preferences</div>
            
            <div class="question" data-question="stressRelievers" role="group" aria-labelledby="stress-relievers-question">
                <div class="question-text" id="stress-relievers-question">7. Preferred Relaxation Methods or Stress Relievers</div>
                <div class="options">
                    <button class="option-btn" data-value="Exercise" aria-pressed="false">Exercise</button>
                    <button class="option-btn" data-value="Socializing" aria-pressed="false">Socializing</button>
                    <button class="option-btn" data-value="Reading/learning" aria-pressed="false">Reading/learning</button>
                    <button class="option-btn" data-value="Meditation/spirituality" aria-pressed="false">Meditation/spirituality</button>
                    <button class="option-btn" data-value="Entertainment" aria-pressed="false">Entertainment (movies, music, etc.)</button>
                </div>
            </div>
            
            <div class="question" data-question="problemSolvingMethod" role="group" aria-labelledby="problem-solving-method-question">
                <div class="question-text" id="problem-solving-method-question">8. Preferred Problem-solving Method/Approach</div>
                <div class="options">
                    <button class="option-btn" data-value="Step-by-step action guidance" aria-pressed="false">Step-by-step action guidance</button>
                    <button class="option-btn" data-value="Open-ended questions" aria-pressed="false">Open-ended questions (self-reflection)</button>
                    <button class="option-btn" data-value="Practical advice" aria-pressed="false">Practical advice (immediate solutions)</button>
                </div>
            </div>
            
            <div class="submit-container">
                <button id="submitBtn" class="submit-btn" disabled aria-label="Start Conversation - Currently disabled until all questions are answered">Start Conversation</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const optionButtons = document.querySelectorAll('.option-btn');
            const submitButton = document.getElementById('submitBtn');
            const progressFill = document.getElementById('progressFill');
            const completedQuestionsEl = document.getElementById('completedQuestions');
            const progressTextEl = document.getElementById('progressText');
            const screenReaderFeedback = document.getElementById('screenReaderFeedback');
            const totalQuestions = 8;
            
            // Store user selections
            const userPreferences = {};
            let completedQuestions = 0;
            
            // Check if the user already has preferences stored from database
            // (async function() {
            //     try {
            //         const userId = "user123"; // replace with real session/user id
            //         const response = await fetch(`http://localhost:3000/user-preferences?userId=${encodeURIComponent(userId)}`, {
            //             credentials: "include"
            //         });

            //         const data = await response.json();

            //         if (data.hasPreferences) {
            //             console.log("Welcome back user:", data.preferences);
            //             localStorage.setItem('userPreferences', JSON.stringify(data.preferences));

            //             // Show "Welcome Back" card
            //             const questionnaireCard = document.querySelector('.questionnaire-card');
            //             const existingPrefsMsg = document.createElement('div');
            //             existingPrefsMsg.className = 'existing-prefs-message';
            //             existingPrefsMsg.innerHTML = `
            //                 <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            //                     <h3 style="margin-top: 0;">Welcome Back!</h3>
            //                     <p>We've found your previous preferences.</p>
            //                     <div style="display: flex; gap: 10px; justify-content: center;">
            //                         <button id="useExistingBtn" style="background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Use Existing Preferences</button>
            //                         <button id="startNewBtn" style="background-color: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Start New Questionnaire</button>
            //                     </div>
            //                 </div>
            //             `;

            //             questionnaireCard.prepend(existingPrefsMsg);

            //             document.getElementById('useExistingBtn').addEventListener('click', function() {
            //                 screenReaderFeedback.textContent = "Using existing preferences. Redirecting to onboarding page...";
            //                 window.location.href = 'onboarding';
            //             });

            //             document.getElementById('startNewBtn').addEventListener('click', function() {
            //                 existingPrefsMsg.remove();
            //                 screenReaderFeedback.textContent = "Starting new questionnaire. Please answer all questions.";
            //             });

            //         } else {
            //             console.log("Welcome new user:", userId);
            //         }

            //     } catch (err) {
            //         console.error("Error checking for existing preferences:", err);
            //     }
            // })();

            // Check if the user already has preferences stored from localStorage
            (async function() {
                try {
                    const userId = "user123"; // replace with real session/user id
                    const storedPrefs = localStorage.getItem("userPreferences");

                    if (storedPrefs) {
                    const preferences = JSON.parse(storedPrefs);
                    console.log("Welcome back user:", preferences);

                    // Show "Welcome Back" card
                    const questionnaireCard = document.querySelector(".questionnaire-card");
                    const existingPrefsMsg = document.createElement("div");
                    existingPrefsMsg.className = "existing-prefs-message";
                    existingPrefsMsg.innerHTML = `
                        <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                        <h3 style="margin-top: 0;">Welcome Back!</h3>
                        <p>We've found your previous preferences.</p>
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button id="useExistingBtn" style="background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Use Existing Preferences</button>
                            <button id="startNewBtn" style="background-color: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Start New Questionnaire</button>
                        </div>
                        </div>
                    `;

                    questionnaireCard.prepend(existingPrefsMsg);

                    document.getElementById("useExistingBtn").addEventListener("click", function() {
                        screenReaderFeedback.textContent = "Using existing preferences. Redirecting to onboarding page...";
                        window.location.href = "onboarding"; // go to onboarding with stored prefs
                    });

                    document.getElementById("startNewBtn").addEventListener("click", function() {
                        existingPrefsMsg.remove();
                        localStorage.removeItem("userPreferences"); // wipe old data if starting new
                        screenReaderFeedback.textContent = "Starting new questionnaire. Please answer all questions.";
                    });

                    } else {
                    console.log("Welcome new user:", userId);
                    }

                } catch (err) {
                    console.error("Error checking for existing preferences:", err);
                }
            })();


            // Handle option button clicks
            optionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const question = this.parentElement.parentElement.dataset.question;
                    const questionText = this.parentElement.parentElement.querySelector('.question-text').textContent;
                    const value = this.dataset.value;
                    
                    // Deselect other options in the same question
                    const siblingButtons = this.parentElement.querySelectorAll('.option-btn');
                    siblingButtons.forEach(btn => {
                        btn.classList.remove('selected');
                        btn.setAttribute('aria-pressed', 'false');
                    });

                    // Select this option
                    this.classList.add('selected');
                    this.setAttribute('aria-pressed', 'true');

                    // Update screen reader
                    screenReaderFeedback.textContent = `Selected ${value} for ${questionText}`;

                    // Store the selection
                    if (!userPreferences[question]) {
                        // This is a new answer
                        completedQuestions++;
                        updateProgress();
                    }
                    
                    userPreferences[question] = value;
                    
                    // Enable submit button if all questions are answered
                    if (completedQuestions === totalQuestions) {
                        submitButton.disabled = false;
                        submitButton.setAttribute('aria-label', 'Start Conversation - Ready to submit');
                    }
                });
            });
            
            // Update progress bar
            function updateProgress() {
                const percentage = (completedQuestions / totalQuestions) * 100;
                progressFill.style.width = `${percentage}%`;
                completedQuestionsEl.textContent = completedQuestions;
                progressTextEl.textContent = `${completedQuestions} of ${totalQuestions} questions completed`;

                // Update submit button aria label
                if (completedQuestions < totalQuestions) {
                    const remaining = totalQuestions - completedQuestions;
                    submitButton.setAttribute('aria-label', `Start Conversation - ${remaining} questions remaining`);
                }
            }
            
            // Handle submitting and saving preferences
            submitButton.addEventListener('click', async function() {
                try {
                    screenReaderFeedback.textContent = "Saving your preferences...";
                    
                    // Save preferences to database
                    // await fetch('http://localhost:3000/save-preferences', {
                    //     method: 'POST',
                    //     headers: { 'Content-Type': 'application/json' },
                    //     body: JSON.stringify({ userId: "user123", preferences: userPreferences }),
                    //     credentials: 'include'
                    // });
           
                    // Also store in localStorage for immediate use
                    localStorage.setItem('userPreferences', JSON.stringify(userPreferences));
                    
                    screenReaderFeedback.textContent = "Preferences saved. Redirecting to onboarding...";

                    // Redirect to onboarding
                    window.location.href = 'onboarding';
                } catch (err) {
                    console.error("Error saving preferences:", err);
                    screenReaderFeedback.textContent = "Error saving preferences. Please try again.";
                    alert("There was an error saving your preferences. Please try again.");
                }
            });
            
            // Add keyboard navigation for the questionnaire
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' && document.activeElement.classList.contains('option-btn')) {
                    document.activeElement.click();
                }
            });
        });
    </script>
</body>
</html>