<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Listening IELTS</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <div class="reading-page">

        <!-- SIDEBAR -->

        <aside class="practice-sidebar">

            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-book-open"></i>

                    Reading

                </div>

                <label><input type="radio"> Bài lẻ</label>
                <label><input type="radio"> Full đề</label>

            </div>

            <div class="menu-skill active-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-headphones"></i>

                    Listening

                </div>

                <label><input type="radio" checked> Bài lẻ</label>
                <label><input type="radio"> Full đề</label>

            </div>

            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-pen"></i>

                    Writing

                </div>

                <label><input type="radio"> Bài lẻ</label>
                <label><input type="radio"> Full đề</label>

            </div>

            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-microphone"></i>

                    Speaking

                </div>

                <label><input type="radio"> Bài lẻ</label>
                <label><input type="radio"> Full đề</label>

            </div>

        </aside>

        <!-- CONTENT -->

        <main class="practice-content">

            <div class="practice-top">

                <div class="practice-tabs">

                    <button>Bài chưa làm</button>

                    <button class="active-tab">
                        Bài đã làm
                    </button>

                </div>

            </div>

            <!-- CARD -->

            <div class="practice-grid">

                <div class="practice-card">

                    <div class="card-tag">

                        Listening

                    </div>

                    <img src="https://images.unsplash.com/photo-1478737270239-2f02b77fc618?q=80&w=1200">

                    <div class="card-content">

                        <div class="band-tag">

                            Band 6.5

                        </div>

                        <h3>

                            Hotel Booking

                        </h3>

                        <p>

                            Listen and answer
                            questions 1-10.

                        </p>

                        <a href="#listening-test">

                            Làm bài

                        </a>

                    </div>

                </div>

            </div>

            <!-- TEST -->

            <section class="reading-test" id="listening-test">

                <!-- LEFT -->

                <div class="reading-left">

                    <h1>

                        Listening Audio

                    </h1>

                    <audio controls style="width:100%;margin:30px 0;">

                        <source src="" type="audio/mp3">

                    </audio>

                    <p>

                        Listen carefully and choose
                        the correct answer.

                    </p>

                </div>

                <!-- RIGHT -->

                <div class="reading-right">

                    <div class="reading-top">

                        <div class="reading-timer">

                            00:30:00

                        </div>

                    </div>

                    <div class="reading-question">

                        <h2>

                            Question 1

                        </h2>

                        <p>

                            What does the speaker want?

                        </p>

                        <label>

                            <input type="radio" name="l1">

                            A. Buy ticket

                        </label>

                        <label>

                            <input type="radio" name="l1">

                            B. Book room

                        </label>

                        <label>

                            <input type="radio" name="l1">

                            C. Find taxi

                        </label>

                        <label>

                            <input type="radio" name="l1">

                            D. Order food

                        </label>

                    </div>

                    <div class="question-number">

                        <button>1</button>
                        <button>2</button>
                        <button>3</button>
                        <button>4</button>
                        <button>5</button>

                    </div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>