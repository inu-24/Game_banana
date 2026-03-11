<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Banana API Game - Hard</title>
    <link rel="stylesheet" href="style.css">
    <script src="gameover.js"></script>
</head>

<body>

<section class="game-section">

<h1 class="game-title">🍌 Banana Puzzle (Hard)</h1>

<div class="game-card">

    <div class="game-info">
        ⏳ Time: <span id="timer">30</span> |
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
let timeLeft = 30;
let timer;

// Load puzzle
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

// Start timer
function startTimer(){
    timer = setInterval(() => {

        timeLeft--;
        document.getElementById("timer").innerText = timeLeft;

        if(timeLeft <= 0){
            clearInterval(timer);
            endGame();
        }

    },1000);
}

// Check answer
function checkAnswer(){

    let userAnswer = document.getElementById("userAnswer").value;

    if(userAnswer == correctAnswer){

        score++;
        document.getElementById("score").innerText = score;

        animateCorrect();
        loadPuzzle();

    }else{

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
    },500);

}

// End Game
function endGame() {

    document.getElementById("result").innerText = "⏰ Time's Up!";

    showGameOver(score, "Hard", function() {
        saveScore(score);
    });

}

// Save score
function saveScore(finalScore){

    let currentLevel = "Hard";

    fetch("save_score.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"score="+finalScore+"&level="+currentLevel
    })
    .then(res => res.text())
    .then(data => {

        console.log(data);

        // Go to leaderboard
        window.location.href = "leaderboard.php";

    })
    .catch(err => console.log("Error saving score"));

}

// Start game
loadPuzzle();
startTimer();

</script>

</body>
</html>