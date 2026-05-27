<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Speaking IELTS</title>

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

            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-headphones"></i>

                    Listening

                </div>

                <label><input type="radio"> Bài lẻ</label>
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

            <div class="menu-skill active-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-microphone"></i>

                    Speaking

                </div>

                <label><input type="radio" checked> Bài lẻ</label>
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

                        Speaking

                    </div>

                    <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?q=80&w=1200">

                    <div class="card-content">

                        <div class="band-tag">

                            Band 7.0

                        </div>

                        <h3>

                            Describe a person

                        </h3>

                        <p>

                            Describe someone who
                            inspired you.

                        </p>

                        <a href="#speaking-test">

                            Làm bài

                        </a>

                    </div>

                </div>

            </div>

            <!-- TEST -->

            <section class="reading-test" id="speaking-test">

                <!-- LEFT -->

                <div class="reading-left">

                    <h1>

                        Speaking Part 2

                    </h1>

                    <p>

                        Describe a person
                        who inspired you.

                    </p>

                    <ul style="font-size:22px;line-height:2;">

                        <li>
                            Who the person is
                        </li>

                        <li>
                            Why you admire them
                        </li>

                        <li>
                            How they inspired you
                        </li>

                    </ul>

                </div>

                <!-- RIGHT -->

                <div class="reading-right">

                    <div class="reading-top">

                        <div class="reading-timer">

                            00:15:00

                        </div>

                    </div>

                    <div class="reading-question">

                        <h2>

                            Choose answer

                        </h2>

                        <p>

                            Which person do you
                            want to describe?

                        </p>

                        <label>

                            <input type="radio" name="s1">

                            A. Teacher

                        </label>

                        <label>

                            <input type="radio" name="s1">

                            B. Parent

                        </label>

                        <label>

                            <input type="radio" name="s1">

                            C. Friend

                        </label>

                        <label>

                            <input type="radio" name="s1">

                            D. Celebrity

                        </label>

                    </div>

                    <button class="record-btn">

                        🎤 Record Answer

                    </button>

                </div>

            </section>

        </main>

    </div>

</body>

</html>