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

        <main class="practice-content">

            <section class="reading-test" id="listening-test">

                <div class="reading-left">

                    <h1>Listening Audio</h1>

                    <audio controls style="width:100%;margin:30px 0;">
                        <source src="" type="audio/mp3">
                    </audio>

                    <p>Listen carefully and choose the correct answer.</p>

                </div>

                <div class="reading-right">

                    <div class="reading-top">

                        <div class="reading-timer">

                            00:30:00

                        </div>

                    </div>

                    <div class="reading-question">

                        <h1>Question 1</h1>

                        <p>What does the speaker want?</p>

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