<?php
session_start();
include("db.php"); // ✅ make sure this is your correct DB file

// Check login
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    exit();
}

// Only allow POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Error: Invalid request method";
    exit();
}

$user_id = $_SESSION['user_id'];
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
$level = isset($_POST['level']) ? $_POST['level'] : 'Easy';

// Validate level
$allowed_levels = ['Easy', 'Medium', 'Hard'];
$level = ucfirst(strtolower($level));

if (!in_array($level, $allowed_levels)) {
    echo "Error: Invalid level";
    exit();
}

// ✅ 1️⃣ Insert into scores table
$insert = $conn->prepare("INSERT INTO scores (user_id, score, level, played_at) VALUES (?, ?, ?, NOW())");
$insert->bind_param("iis", $user_id, $score, $level);
$insert->execute();
$insert->close();

// ✅ 2️⃣ Update users table
$update = $conn->prepare("UPDATE users 
                          SET total_score = total_score + ?, 
                              current_level = ? 
                          WHERE id = ?");
$update->bind_param("isi", $score, $level, $user_id);

if ($update->execute()) {
    echo "Score saved successfully!";
} else {
    echo "Database Error: " . $conn->error;
}

$update->close();
$conn->close();
?>