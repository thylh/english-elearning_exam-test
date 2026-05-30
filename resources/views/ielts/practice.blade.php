<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Practice IELTS</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <div class="practice-layout">

        <!-- ================= SIDEBAR ================= -->
        <aside class="practice-sidebar">

            <!-- FILTER -->
            <div class="filter-icon">

                <i class="fa-solid fa-filter"></i>

            </div>

            <!-- YOUPASS -->
            <div class="promo-card">

                <h3>

                    <i class="fa-solid fa-graduation-cap"></i>

                    English For You

                </h3>

                <p>

                    Review đề thi thực tế

                </p>

            </div>


            <!-- READING -->
            <div class="menu-skill active-skill">

                <button type="button" class="menu-title" data-skill="reading">

                    <i class="fa-solid fa-book-open"></i>

                    <span>Reading</span>

                </button>

                <label>

                    <input type="radio" name="reading">

                    Bài lẻ

                </label>

                <label>

                    <input type="radio" name="reading">

                    Full đề

                </label>

            </div>

            <!-- LISTENING -->
            <div class="menu-skill">

                <button type="button" class="menu-title" data-skill="listening">

                    <i class="fa-solid fa-headphones"></i>

                    <span>Listening</span>

                </button>

                <label>

                    <input type="radio" name="listening">

                    Bài lẻ

                </label>

                <label>

                    <input type="radio" name="listening">

                    Full đề

                </label>

            </div>

            <!-- WRITING -->
            <div class="menu-skill">

                <button type="button" class="menu-title" data-skill="writing">

                    <i class="fa-solid fa-pen"></i>

                    <span>Writing</span>

                </button>

                <label>

                    <input type="radio" checked>

                    Bài lẻ

                </label>

                <label>

                    <input type="radio">

                    Full đề

                </label>

            </div>

            <!-- SPEAKING -->
            <div class="menu-skill">

                <button type="button" class="menu-title" data-skill="speaking">

                    <i class="fa-solid fa-microphone"></i>

                    <span>Speaking</span>

                </button>

                <label>

                    <input type="radio" name="speaking">

                    Bài lẻ

                </label>

                <label>

                    <input type="radio" name="speaking">

                    Full đề

                </label>

            </div>

        </aside>

        <!-- ================= CONTENT ================= -->
        <main class="practice-content">

            <div class="skill-panel active" data-panel="reading">
                <div class="practice-grid">
                    <div class="practice-card highlight-card">
                        <div class="card-tag">Table</div>
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200">
                        <div class="card-content">
                            <div class="band-tag">Band 5.5</div>
                            <h3>Milk and butter</h3>
                            <p>The table illustrates weekly consumption by age group of dairy products...</p>
                            <a href="/reading">Làm bài</a>
                        </div>
                    </div>
                    <div class="practice-card highlight-card">
                        <div class="card-tag">Line Graph</div>
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200">
                        <div class="card-content">
                            <div class="band-tag">Band 7.0</div>
                            <h3>Going to the cinema</h3>
                            <p>The graph shows the percentage of people visiting the cinema...</p>
                            <a href="/reading">Làm bài</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="skill-panel" data-panel="listening">
                <div class="practice-grid">
                    <div class="practice-card highlight-card">
                        <div class="card-tag">Conversation</div>
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200">
                        <div class="card-content">
                            <div class="band-tag">Band 6.0</div>
                            <h3>Airport announcements</h3>
                            <p>Listen to the announcements and choose the correct answers...</p>
                            <a href="/listening">Làm bài</a>
                        </div>
                    </div>
                    <div class="practice-card">
                        <div class="card-tag">Form completion</div>
                        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1200">
                        <div class="card-content">
                            <h3>Train schedule</h3>
                            <p>Complete the schedule while listening to the speaker...</p>
                            <a href="/listening">Làm bài</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="skill-panel" data-panel="writing">
                <div class="practice-grid">
                    <div class="practice-card highlight-card">
                        <div class="card-tag">Table</div>
                        <img src="https://images.unsplash.com/photo-1444384851175-0d3b0dbcede6?q=80&w=1200">
                        <div class="card-content">
                            <div class="band-tag">Band 5.5</div>
                            <h3>Milk and butter</h3>
                            <p>The table illustrates weekly consumption by age group of dairy products...</p>
                            <a href="/writing">Làm bài</a>
                        </div>
                    </div>
                    <div class="practice-card">
                        <div class="card-tag">Map</div>
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200">
                        <div class="card-content">
                            <h3>Archaeological Findings</h3>
                            <p>The maps show recent archaeological findings...</p>
                            <a href="/writing">Làm bài</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="skill-panel" data-panel="speaking">
                <div class="practice-grid">
                    <div class="practice-card highlight-card">
                        <div class="card-tag">Interview</div>
                        <img src="https://images.unsplash.com/photo-1519340333755-0bdbfb2a36e6?q=80&w=1200">
                        <div class="card-content">
                            <div class="band-tag">Practice</div>
                            <h3>Holiday plans</h3>
                            <p>Talk about your holiday plans and answer the follow-up questions...</p>
                            <a href="/speaking">Làm bài</a>
                        </div>
                    </div>
                    <div class="practice-card">
                        <div class="card-tag">Opinion</div>
                        <img src="https://images.unsplash.com/photo-1516637090014-cb1ab78511f5?q=80&w=1200">
                        <div class="card-content">
                            <h3>Technology in education</h3>
                            <p>Discuss the advantages and disadvantages of technology in education...</p>
                            <a href="/speaking">Làm bài</a>
                        </div>
                    </div>
                </div>
            </div>

</body>

</html>