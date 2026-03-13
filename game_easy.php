<!-- Source :- use AI Tool(claude) -->

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$fullname = $_SESSION['fullname'];
$is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'];
$save_endpoint = $is_guest ? "guest_save_score.php" : "save_score.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Banana API Game - Easy</title>
    <link rel="stylesheet" href="style.css">
    <script src="gameover.js"></script>
</head>

<body>

<section class="game-section">

<h1 class="game-title">🍌 Banana Puzzle (Easy)</h1>

<div class="game-card">

    <div class="game-info">
        ⏳ Time: <span id="timer">60</span> |
        ⭐ Score: <span id="score">0</span>
    </div>

    <img id="bananaImage" width="300">

    <input type="number" id="userAnswer" placeholder="Enter your answer">

    <button onclick="checkAnswer()">Submit</button>

    <p id="result"></p>

    <button onclick="loadPuzzle()">Next Puzzle</button>

    <a href="levels.php" class="back-btn">⬅ Back</a>

</div>

</section>

<script>
let correctAnswer = null;
let score = 0;
let timeLeft = 60;
let timer;

// Load puzzle from API
function loadPuzzle() {
    fetch("https://marcconrad.com/uob/banana/api.php")
    .then(response => response.json())
    .then(data => {
        document.getElementById("bananaImage").src = data.question;
        correctAnswer = data.solution;
        document.getElementById("userAnswer").value = "";
        document.getElementById("result").innerText = "";
    });
}

// Start Timer
function startTimer(){
    timer = setInterval(() => {
        timeLeft--;
        document.getElementById("timer").innerText = timeLeft;

        if(timeLeft <= 0){
            clearInterval(timer);
            endGame();
        }
    }, 1000);
}

// Check Answer
function checkAnswer(){
    let userAnswer = document.getElementById("userAnswer").value;

    if(userAnswer == correctAnswer){
        score++;
        document.getElementById("score").innerText = score;
        animateCorrect();
        loadPuzzle();
    } else {
        document.getElementById("result").innerText = "❌ Wrong!";
        document.getElementById("result").style.color = "red";
    }
}

// Animation
function animateCorrect(){
    let card = document.querySelector(".game-card");
    card.classList.add("correct-animation");
    document.getElementById("result").innerText = "✅ Correct!";
    document.getElementById("result").style.color = "lightgreen";
    setTimeout(() => {
        card.classList.remove("correct-animation");
    }, 500);
}

// End Game
function endGame() {

    document.getElementById("result").innerText = "⏰ Time's Up!";

    showGameOver(score, "Easy", function() {
        saveScore(score);
    });

}

// Save score to localStorage & backend
function saveScore(finalScore) {
  
    let username = "<?php echo htmlspecialchars($fullname); ?>";
    let isGuest  = <?php echo $is_guest ? 'true' : 'false'; ?>;
    let endpoint = "<?php echo $save_endpoint; ?>";

    let leaderboard = JSON.parse(localStorage.getItem("leaderboard")) || [];
    leaderboard.push({ name: username, score: finalScore });
    localStorage.setItem("leaderboard", JSON.stringify(leaderboard));

    // Save to database (or guest session)
    let currentLevel = "easy"; 
    fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "score=" + finalScore + "&level=" + currentLevel
    })
    .then(res => res.text())
    .then(data => {
        console.log(data);
        if (isGuest) {
            // Show a friendly reminder to register
            setTimeout(() => {
                let reg = confirm("🎮 Great game, " + username + "!\n\nYour score (" + finalScore + ") was saved for this session only.\n\nRegister a free account to save scores permanently and appear on the Leaderboard!\n\nGo to Register page now?");
                if (reg) location.href = "login.html";
            }, 300);
        }
    })
    .catch(err => console.log("Error saving score"));
}


loadPuzzle();
startTimer();

</script>

</body>
</html>