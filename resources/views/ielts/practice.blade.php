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

                    <i class="fa-solid fa-book-open"></i>

                    English For You

                </h3>

                <p>

                    Review đề thi thực tế

                </p>

            </div>


            <!-- READING -->
            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-book-open"></i>

                    <span>Reading</span>

                </div>

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

                <div class="menu-title">

                    <i class="fa-solid fa-headphones"></i>

                    <span>Listening</span>

                </div>

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
            <div class="menu-skill active-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-pen"></i>

                    <span>Writing</span>

                </div>

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

                <div class="menu-title">

                    <i class="fa-solid fa-microphone"></i>

                    <span>Speaking</span>

                </div>

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


            <!-- ================= GRID ================= -->
            <div class="practice-grid">

                <!-- CARD -->
                <div class="practice-card highlight-card">

                    <div class="card-tag">

                        Table

                    </div>

                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200">

                    <div class="card-content">

                        <div class="band-tag">

                            Band 5.5

                        </div>

                        <h3>

                            Milk and butter

                        </h3>

                        <p>

                            The table illustrates weekly
                            consumption by age group
                            of dairy products...

                        </p>

                        <a href="/writing">

                            Làm bài

                        </a>

                    </div>

                </div>

                <!-- CARD -->
                <div class="practice-card highlight-card">

                    <div class="card-tag">

                        Line Graph

                    </div>

                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200">

                    <div class="card-content">

                        <div class="band-tag">

                            Band 7.0

                        </div>

                        <h3>

                            Going to the cinema

                        </h3>

                        <p>

                            The graph shows
                            the percentage of people
                            visiting the cinema...

                        </p>

                        <a href="/writing">

                            Làm bài

                        </a>

                    </div>

                </div>

                <!-- CARD -->
                <div class="practice-card">

                    <div class="card-tag">

                        Bar Chart

                    </div>

                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200">

                    <div class="card-content">

                        <h3>

                            Australia-China trade

                        </h3>

                        <p>

                            The first chart below
                            shows the value of goods...

                        </p>

                        <a href="/writing">

                            Làm bài

                        </a>

                    </div>

                </div>

                <!-- CARD -->
                <div class="practice-card">

                    <div class="card-tag">

                        Map

                    </div>

                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200">

                    <div class="card-content">

                        <h3>

                            Archaeological Findings

                        </h3>

                        <p>

                            The maps show recent
                            archaeological findings...

                        </p>

                        <a href="/writing">

                            Làm bài

                        </a>

                    </div>

                </div>

            </div>

        </main>

    </div>

</body>

</html>