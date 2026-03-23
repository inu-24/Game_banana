<!-- Source: AI-assisted (Claude AI) — used to generate this file's logic -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Challenge - Banana Math Puzzle</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Daily challenge badge */
        .daily-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f7971e, #ffd200);
            color: #1a1a1a;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        /* Progress dots */
        .progress-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 15px 0;
        }

        .dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            border: 2px solid rgba(255,255,255,0.5);
            transition: 0.3s;
        }

        .dot.done {
            background: #ffd200;
            border-color: #ffd200;
            box-shadow: 0 0 8px rgba(255,210,0,0.7);
        }

        .dot.current {
            background: rgba(255,255,255,0.7);
            border-color: #fff;
        }

        /* Result screen */
        .result-screen {
            display: none;
            text-align: center;
            padding: 10px;
        }

        .result-screen h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .final-score {
            font-size: 55px;
            font-weight: 700;
            color: #ffd200;
            text-shadow: 0 0 20px rgba(255,210,0,0.6);
        }

        .result-msg {
            font-size: 16px;
            margin: 10px 0 20px;
            color: rgba(255,255,255,0.85);
        }
    </style>
</head>

<body>

<section class="game-section">

    <h1 class="game-title">🌟 Daily Challenge</h1>

    <div class="game-card">

        <!-- Game area -->
        <div id="gameArea">
            <div class="daily-badge">⭐ Daily Challenge — 5 Puzzles</div>

            <!-- Progress dots showing 5 puzzles -->
            <div class="progress-dots">
                <div class="dot current" id="dot0"></div>
                <div class="dot" id="dot1"></div>
                <div class="dot" id="dot2"></div>
                <div class="dot" id="dot3"></div>
                <div class="dot" id="dot4"></div>
            </div>

            <div class="game-info">
                ❓ Question: <span id="questionNum">1</span> / 5 &nbsp;|&nbsp;
                ⭐ Score: <span id="score">0</span>
            </div>

            <img id="bananaImage" width="300">

            <input type="number" id="userAnswer" placeholder="Enter your answer">

            <button onclick="checkAnswer()">Submit ✅</button>

            <p id="result"></p>

            <a href="home.php" class="back-btn">⬅ Back to Home</a>
        </div>

        <!-- Result screen shown after all 5 puzzles -->
        <div class="result-screen" id="resultScreen">
            <h2>🎉 Challenge Complete!</h2>
            <div class="final-score" id="finalScore">0/5</div>
            <p class="result-msg" id="resultMsg"></p>
            <button onclick="location.href='home.php'">🏠 Back to Home</button>
            <button onclick="location.href='leaderboard.php'">🏆 Leaderboard</button>
        </div>

    </div>

</section>

<script>
let correctAnswer = null;
let score = 0;
let currentQuestion = 0;
const totalQuestions = 5;

// Load puzzle from Banana API
function loadPuzzle() {
    document.getElementById("userAnswer").value = "";
    document.getElementById("result").innerText = "";

    fetch("https://marcconrad.com/uob/banana/api.php")
    .then(response => response.json())
    .then(data => {
        document.getElementById("bananaImage").src = data.question;
        correctAnswer = data.solution;
    });
}

// Update progress dots
function updateDots() {
    for (let i = 0; i < totalQuestions; i++) {
        let dot = document.getElementById("dot" + i);
        dot.classList.remove("done", "current");
        if (i < currentQuestion) {
            dot.classList.add("done");
        } else if (i === currentQuestion) {
            dot.classList.add("current");
        }
    }
}

// Check the user's answer
function checkAnswer() {
    let userAnswer = document.getElementById("userAnswer").value;

    if (userAnswer === "") return;

    if (parseInt(userAnswer) == correctAnswer) {
        score++;
        document.getElementById("score").innerText = score;
        document.getElementById("result").innerText = "✅ Correct!";
        document.getElementById("result").style.color = "lightgreen";

        // Mark dot as done
        document.getElementById("dot" + currentQuestion).classList.remove("current");
        document.getElementById("dot" + currentQuestion).classList.add("done");
    } else {
        document.getElementById("result").innerText = "❌ Wrong! Answer was: " + correctAnswer;
        document.getElementById("result").style.color = "#ff6b6b";

        // Mark dot as done (wrong)
        document.getElementById("dot" + currentQuestion).classList.remove("current");
        document.getElementById("dot" + currentQuestion).classList.add("done");
    }

    currentQuestion++;

    if (currentQuestion < totalQuestions) {
        // Move to next question after short delay
        document.getElementById("questionNum").innerText = currentQuestion + 1;
        if (currentQuestion < totalQuestions) {
            document.getElementById("dot" + currentQuestion).classList.add("current");
        }
        setTimeout(() => {
            loadPuzzle();
        }, 1000);
    } else {
        // All 5 questions done
        setTimeout(() => {
            endChallenge();
        }, 1000);
    }
}

// End the daily challenge
function endChallenge() {
    // Save today's date so button disables on home page
    localStorage.setItem("dailyChallengeDate", new Date().toDateString());

    // Show result screen
    document.getElementById("gameArea").style.display = "none";
    document.getElementById("resultScreen").style.display = "block";
    document.getElementById("finalScore").innerText = score + " / 5";

    // Result message based on score
    let msg = "";
    if (score === 5) msg = "🏆 Perfect score! You're a Banana genius!";
    else if (score >= 3) msg = "😊 Great job! Come back tomorrow for more!";
    else msg = "💪 Keep practicing! You'll do better tomorrow!";
    document.getElementById("resultMsg").innerText = msg;

    // Save score to database
    fetch("save_score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "score=" + score + "&level=Daily"
    })
    .then(res => res.text())
    .then(data => console.log("Score saved:", data))
    .catch(err => console.log("Error saving score"));
}

// Start the challenge
loadPuzzle();
</script>

</body>
</html>