<?php
  session_start();
  include 'translate.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Life Coach - Choose Your Coach</title>
  <style>
      .coach-selector {
          max-width: 800px;
          margin: 50px auto;
          padding: 20px;
          text-align: center;
      }
      
      .coach-selector h1 {
          color: #333;
          margin-bottom: 20px;
      }
      
      .coach-selector p {
          color: #666;
          margin-bottom: 40px;
          font-size: 18px;
      }
      
      .coach-options {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
      }
      
      .coach-card {
          background: white;
          border: 2px solid #e0e0e0;
          border-radius: 10px;
          padding: 30px;
          cursor: pointer;
          transition: all 0.3s ease;
          text-decoration: none;
          color: inherit;
      }
      
      .coach-card:hover {
          border-color: #4CAF50;
          transform: translateY(-5px);
          box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      }
      
      .coach-card h3 {
          color: #4CAF50;
          margin-bottom: 15px;
          font-size: 22px;
      }
      
      .coach-card p {
          color: #666;
          font-size: 14px;
          line-height: 1.5;
          margin-bottom: 0;
      }
      
      .coach-icon {
          font-size: 48px;
          margin-bottom: 15px;
          display: block;
      }
      
      .loading {
          display: none;
          text-align: center;
          color: #666;
          font-style: italic;
      }
      
      .error {
          background-color: #ffebee;
          color: #c62828;
          padding: 15px;
          border-radius: 5px;
          margin: 20px 0;
          display: none;
      }
      
      .preferences-info {
          background-color: #f5f5f5;
          padding: 15px;
          border-radius: 5px;
          margin-bottom: 20px;
          font-size: 14px;
          color: #666;
      }
      
      .preferences-info a {
          color: #4CAF50;
          text-decoration: none;
      }
      
      .preferences-info a:hover {
          text-decoration: underline;
      }
  </style>
</head>
<body>
    <div class="coach-selector">
        <h1>Choose Your AI Life Coach</h1>
        <p>Select the type of coaching that best fits your current needs</p>
        
        <div class="preferences-info">
            <strong>📝 Your preferences are set!</strong> 
            Each coach will use your personal preferences to provide tailored guidance.
            <a href="questionnaire.html">Update preferences</a>
        </div>
        
        <div class="error" id="error-message"></div>
        <div class="loading" id="loading">Checking your preferences...</div>
        
        <div class="coach-options" id="coach-options" style="display: none;">
            <a href="basic-daily.html" class="coach-card">
                <span class="coach-icon">🗓️</span>
                <h3>每日总结 - 基础班</h3>
                <p>为基础班学员特制的每日总结，帮助你回顾和反思今天的学习与成长。</p>
            </a>
            <a href="advanced-daily.html" class="coach-card">
                <span class="coach-icon">⚙️</span>
                <h3>每日总结 - 高阶班</h3>
                <p>针对高阶班学员的结构化深入总结，帮助你分析目标、决策、行为模式、杠杆点和次日执行。</p>
            </a>
            <a href="outcome-reflection.html" class="coach-card">
                <span class="coach-icon">🔍</span>
                <h3>誓约执行验证</h3>
                <p>验收及反思誓约成果</p>
            </a>
            <a href="decision-making.html" class="coach-card">
                <span class="coach-icon">🤔</span>
                <h3>面对进退两难的结果，你能如何做出决定？</h3>
                <p>明确决策，评估选项的标准和风险，选择并承诺。</p>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loading = document.getElementById('loading');
            const errorMessage = document.getElementById('error-message');
            const coachOptions = document.getElementById('coach-options');
            
            loading.style.display = 'block';
            
            // Check if user preferences exist
            fetch('/user-preferences', { credentials: 'include' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Server responded with status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    loading.style.display = 'none';
                    
                    if (!data.hasPreferences) {
                        // Redirect to the questionnaire if preferences are missing
                        window.location.href = 'questionnaire.html';
                    } else {
                        // Store preferences in localStorage for use by chat scripts
                        localStorage.setItem('userPreferences', JSON.stringify(data.preferences));
                        // Show coach options
                        coachOptions.style.display = 'grid';
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    console.error('Error checking user preferences:', error);
                    
                    errorMessage.textContent = 'An error occurred while checking your preferences. Please try again.';
                    errorMessage.style.display = 'block';
                    
                    // Show coach options anyway after 3 seconds
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                        coachOptions.style.display = 'grid';
                    }, 3000);
                });
        });
  </script>
</body>
</html>
