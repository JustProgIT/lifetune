<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life.Tune - AI Process Guide</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-image: url('img/computer background LIFE.TUNE.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #333;
        }

        /* Header styles matching index.php */
        .site-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: clamp(0.75rem,5vh,1.5rem) 1rem;
            text-align: left;
        }

        .logo {
            height: 4rem !important;
        }

        /* Mobile background */
        @media (max-width: 768px) {
            body {
                background-image: url('img/phone backgroung LIFE.TUNE.png') !important;
            }
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: -1rem;
        }

        .page {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 2rem;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: left;
            width: 100%;
            max-width: 700px;
            min-height: 500px;
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .page.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: clamp(2rem, 5vw, 3rem);
            color: #333;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .page-subtitle {
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: #666;
            margin-bottom: 2rem;
        }

        .process-steps {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .process-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 205, 96, 0.1);
            border-radius: 1rem;
            border-left: 4px solid rgb(255, 205, 96);
        }

        .step-number {
            background: rgb(255, 205, 96);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
            text-align: left;
        }

        .step-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .step-description {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
        }



        .intro-text {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: left;
        }

        .process-section {
            margin: 3rem 0;
        }

        .benefits-section {
            margin: 3rem 0;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .benefit-item {
            background: rgba(255, 205, 96, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: left;
            border: 2px solid rgba(255, 205, 96, 0.3);
            transition: transform 0.3s ease;
        }

        .benefit-item:hover {
            transform: translateY(-5px);
        }

        .benefit-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .benefit-item h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .benefit-item p {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
        }

        .solution-preview {
            margin: 3rem 0;
            background: rgba(255, 205, 96, 0.05);
            border-radius: 1.5rem;
            padding: 2rem;
            border: 2px solid rgba(255, 205, 96, 0.2);
        }

        .solution-text {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .solution-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .solution-list li {
            font-size: 1rem;
            color: #333;
            line-height: 1.8;
            margin-bottom: 0.8rem;
            padding-left: 0;
        }

        .solution-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: rgba(255, 205, 96, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: left;
            border: 2px solid rgba(255, 205, 96, 0.3);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .feature-description {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
        }

        .cta-section {
            margin: 3rem 0 1rem 0;
            text-align: center;
        }

        .cta-button {
            background: rgb(255, 205, 96);
            color: white;
            border: none;
            padding: 1.2rem 3rem;
            border-radius: 2rem;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 205, 96, 0.3);
        }

        .cta-button:hover {
            background: rgb(235, 185, 76);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 205, 96, 0.4);
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 3rem;
            width: 100%;
            max-width: 700px;
        }

        .nav-btn {
            background: rgb(255, 205, 96);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(255, 205, 96, 0.3);
        }

        .nav-btn:hover {
            background: rgb(235, 185, 76);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 205, 96, 0.4);
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            color: #666;
            box-shadow: none;
        }

        .page-indicator {
            display: flex;
            gap: 0.5rem;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ccc;
        }

        .dot.active {
            background-color: rgb(255, 205, 96);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .logo {
                height: clamp(2.5rem, 8vh, 5rem);
            }

            .page {
                padding: 2rem 1.5rem;
                min-height: 400px;
            }

            .process-step {
                flex-direction: column;
                text-align: left;
            }

            .step-number {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .step-content {
                text-align: left;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .solution-features {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .cta-button {
                width: 100%;
                justify-content: center;
            }

            .navigation {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <img src="img/bird LIFE.TUNE logo.png" alt="Life.Tune logo" class="logo">
    </header>
    
    <div class="container">
        <!-- AI Process Overview -->
        <div class="page active" id="page1">
            <div class="page-header">
                <h1 class="page-title">Your AI Wellness Journey</h1>
                <p class="page-subtitle">Discover how our AI understands, guides, and supports your mental wellness</p>
            </div>

            <p class="intro-text">
                Our AI assistant is designed to be your personal wellness companion, using advanced technology to provide understanding, guidance, and practical solutions for your mental health journey.
            </p>

            <div class="process-section">
                <h2 class="section-title">How the AI Process Works</h2>
                <ol class="process-steps">
                    <li class="process-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3 class="step-title">Share Your Story</h3>
                            <p class="step-description">Start by sharing what's on your mind - your feelings, concerns, or any challenges you're facing. The AI listens and understands through natural conversation.</p>
                        </div>
                    </li>

                    <li class="process-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3 class="step-title">Deep Understanding</h3>
                            <p class="step-description">The AI analyzes your responses to understand your emotional patterns, triggers, and underlying needs, building a comprehensive picture of your situation.</p>
                        </div>
                    </li>

                    <li class="process-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3 class="step-title">Personalized Guidance</h3>
                            <p class="step-description">Based on your unique situation, the AI provides tailored advice, coping strategies, and evidence-based techniques that work for you.</p>
                        </div>
                    </li>

                    <li class="process-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3 class="step-title">Solution Delivery</h3>
                            <p class="step-description">Finally, you receive practical solutions, actionable steps, and ongoing support to help you navigate your challenges and improve your mental wellness.</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="benefits-section">
                <h2 class="section-title">What You'll Get from Our AI</h2>
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">🎯</div>
                        <h3>Personalized Support</h3>
                        <p>Tailored advice and strategies that match your specific needs and situation</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🧠</div>
                        <h3>Emotional Understanding</h3>
                        <p>Deep insights into your emotional patterns and triggers</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">💡</div>
                        <h3>Practical Solutions</h3>
                        <p>Actionable steps and techniques you can use immediately</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🤝</div>
                        <h3>Non-judgmental Space</h3>
                        <p>A safe, supportive environment to explore your thoughts and feelings</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">📈</div>
                        <h3>Progress Tracking</h3>
                        <p>Monitor your wellness journey and see your growth over time</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🔄</div>
                        <h3>Continuous Learning</h3>
                        <p>AI that adapts and improves its understanding of you with each conversation</p>
                    </div>
                </div>
            </div>

            <div class="navigation">
                <button class="nav-btn" disabled>← Back</button>
                <div class="page-indicator">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                </div>
                <button class="nav-btn" onclick="nextPage()">Next →</button>
            </div>
        </div>

        <!-- Page 2: Solution Approach -->
        <div class="page" id="page2">
            <div class="page-header">
                <h1 class="page-title">Your Personalized Solution Awaits</h1>
                <p class="page-subtitle">Discover what comprehensive solutions our AI will provide for your wellness journey</p>
            </div>

            <p class="intro-text">
                After understanding your unique situation through our intelligent process, our AI will deliver personalized solutions designed specifically for your needs and goals.
            </p>

            <div class="solution-preview">
                <h2 class="section-title">What Your Solution Will Include</h2>
                <div class="solution-features">
                    <div class="feature-card">
                        <div class="feature-icon">🎯</div>
                        <h3 class="feature-title">Targeted Strategies</h3>
                        <p class="feature-description">Specific techniques and approaches tailored to address your unique challenges and personal situation.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🧠</div>
                        <h3 class="feature-title">Cognitive Techniques</h3>
                        <p class="feature-description">Evidence-based methods to help reframe negative thoughts and develop healthier thinking patterns.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">💪</div>
                        <h3 class="feature-title">Coping Mechanisms</h3>
                        <p class="feature-description">Practical tools and strategies to help you navigate difficult situations and emotional challenges.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📋</div>
                        <h3 class="feature-title">Action Plans</h3>
                        <p class="feature-description">Step-by-step guidance with clear, actionable steps you can implement in your daily life.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🌟</div>
                        <h3 class="feature-title">Long-term Wellness</h3>
                        <p class="feature-description">Sustainable strategies for ongoing mental health improvement and personal growth.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🤝</div>
                        <h3 class="feature-title">Ongoing Support</h3>
                        <p class="feature-description">Continuous guidance and adaptation as your needs evolve throughout your wellness journey.</p>
                    </div>
                </div>
            </div>

            <div class="cta-section">
                <button class="cta-button" onclick="window.location.href='aichat'">
                    Start Your AI Journey
                </button>
            </div>

            <div class="navigation">
                <button class="nav-btn" onclick="prevPage()">← Back</button>
                <div class="page-indicator">
                    <div class="dot"></div>
                    <div class="dot active"></div>
                </div>
                <button class="nav-btn" disabled>Next →</button>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 0; // Start from 0 (first page)
        const pages = document.querySelectorAll('.page');
        const totalPages = pages.length;

        function showPage(pageIndex) {
            // Hide all pages
            pages.forEach((page, index) => {
                page.classList.remove('active');
                if (index === pageIndex) {
                    page.classList.add('active');
                }
            });
            
            // Update navigation buttons and dots
            updateNavigation(pageIndex);
            currentPage = pageIndex;
        }

        function updateNavigation(pageIndex) {
            // Update all navigation sections
            const navSections = document.querySelectorAll('.navigation');
            navSections.forEach((nav, navIndex) => {
                const backBtn = nav.querySelector('.nav-btn:first-child');
                const nextBtn = nav.querySelector('.nav-btn:last-child');
                const dots = nav.querySelectorAll('.dot');
                
                // Update back button
                if (backBtn) {
                    backBtn.disabled = pageIndex === 0;
                }
                
                // Update next button
                if (nextBtn) {
                    nextBtn.disabled = pageIndex === totalPages - 1;
                }
                
                // Update dots
                dots.forEach((dot, dotIndex) => {
                    dot.classList.remove('active');
                    if (dotIndex === pageIndex) {
                        dot.classList.add('active');
                    }
                });
            });
        }

        function nextPage() {
            if (currentPage < totalPages - 1) {
                showPage(currentPage + 1);
            }
        }

        function prevPage() {
            if (currentPage > 0) {
                showPage(currentPage - 1);
            }
        }

        // Initialize the page
        showPage(0);

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' && currentPage < totalPages - 1) {
                nextPage();
            } else if (e.key === 'ArrowLeft' && currentPage > 0) {
                prevPage();
            }
        });
    </script>
</body>
</html> 