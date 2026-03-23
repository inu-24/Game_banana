<!-- Source: AI tool -->
 
<?php
session_start();

if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']) {
    http_response_code(403);
    echo "Not a guest session";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Method not allowed";
    exit();
}

$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
$level = isset($_POST['level']) ? ucfirst(strtolower($_POST['level'])) : 'Easy';

$allowed_levels = ['Easy', 'Medium', 'Hard'];
if (!in_array($level, $allowed_levels)) {
    echo "Invalid level";
    exit();
}

// Store in session
if (!isset($_SESSION['guest_scores'])) {
    $_SESSION['guest_scores'] = [];
}

$_SESSION['guest_scores'][] = [
    'score' => $score,
    'level' => $level,
    'played_at' => date('Y-m-d H:i:s')
];

// Keep a running total
$_SESSION['guest_total_score'] = ($_SESSION['guest_total_score'] ?? 0) + $score;

echo "Guest score saved (session only)";
?>