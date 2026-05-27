<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reading IELTS</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <div class="reading-page">

        <!-- ================= SIDEBAR ================= -->

        <aside class="practice-sidebar">

            <!-- READING -->
            <div class="menu-skill active-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-book-open"></i>

                    Reading

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

            <!-- LISTENING -->
            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-headphones"></i>

                    Listening

                </div>

                <label>

                    <input type="radio">

                    Bài lẻ

                </label>

                <label>

                    <input type="radio">

                    Full đề

                </label>

            </div>

            <!-- WRITING -->
            <div class="menu-skill">

                <div class="menu-title">

                    <i class="fa-solid fa-pen"></i>

                    Writing

                </div>

                <label>

                    <input type="radio">

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

                    Speaking

                </div>

                <label>

                    <input type="radio">

                    Bài lẻ

                </label>

                <label>

                    <input type="radio">

                    Full đề

                </label>

            </div>

        </aside>

        <!-- ================= CONTENT ================= -->

        <main class="practice-content">

            <!-- SEARCH -->
            <div class="practice-top">

                <div class="practice-tabs">

                    <button>
                        Bài chưa làm
                    </button>

                    <button class="active-tab">
                        Bài đã làm
                    </button>

                </div>

            </div>

            <!-- GRID -->

            <div class="practice-grid">

                <!-- CARD 1 -->

                <div class="practice-card">

                    <div class="card-tag">

                        Reading Passage

                    </div>

                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200">

                    <div class="card-content">

                        <div class="band-tag">

                            Band 6.0

                        </div>

                        <h3>

                            Organisational Design

                        </h3>

                        <p>

                            Read the passage and answer
                            questions 14-20.

                        </p>

                        <a href="#reading-test">

                            Làm bài

                        </a>

                    </div>

                </div>

                <!-- CARD 2 -->

                <div class="practice-card">

                    <div class="card-tag">

                        Matching Heading

                    </div>

                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200">

                    <div class="card-content">

                        <div class="band-tag">

                            Band 7.0

                        </div>

                        <h3>

                            Business Management

                        </h3>

                        <p>

                            Match the correct heading
                            for each paragraph.

                        </p>

                        <a href="#reading-test">

                            Làm bài

                        </a>

                    </div>

                </div>

            </div>

            <!-- ================= TEST ================= -->

            <section class="reading-test" id="reading-test">

                <!-- LEFT -->

                <div class="reading-left">

                    <h1>

                        Early Approaches
                        to Organisational Design

                    </h1>

                    <p>

                        Determining the best type
                        of organisational structure
                        for a particular situation...

                    </p>

                    <p>

                        The Classical Approach

                    </p>

                    <p>

                        Early management writers attempted
                        to approach organisational design...

                    </p>

                </div>

                <!-- RIGHT -->

                <div class="reading-right">

                    <!-- TIMER -->

                    <div class="reading-top">

                        <div class="reading-timer">

                            00:00:01

                        </div>

                    </div>

                    <!-- QUESTION -->

                    <div class="reading-question">

                        <h2>

                            Questions 14-15

                        </h2>

                        <p>

                            Choose TWO letters A-E.

                        </p>

                        <label>

                            <input type="checkbox">

                            A. a marked ranking order

                        </label>

                        <label>

                            <input type="checkbox">

                            B. giving importance to work

                        </label>

                        <label>

                            <input type="checkbox">

                            C. advancement of workers

                        </label>

                        <label>

                            <input type="checkbox">

                            D. neutral environment

                        </label>

                    </div>

                    <!-- QUESTION NUMBER -->

                    <div class="question-number">

                        <button>14</button>
                        <button>15</button>
                        <button>16</button>
                        <button>17</button>
                        <button>18</button>

                    </div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>