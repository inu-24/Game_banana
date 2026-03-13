<!-- Source :- use AI Tool(claude) -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

$token   = isset($_GET['token']) ? trim($_GET['token']) : '';
$status  = '';   // 'success' | 'expired' | 'already' | 'invalid'
$message = '';

if (empty($token)) {
    $status  = 'invalid';
    $message = 'No verification token provided.';

} else {

    // Look up the token in the database
    $stmt = $conn->prepare(
        "SELECT id, fullname, is_verified, token_expiry FROM users WHERE verification_token = ?"
    );
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $status  = 'invalid';
        $message = 'Invalid or already used verification link.';

    } else {
        $user = $result->fetch_assoc();

        if ($user['is_verified'] == 1) {
            $status  = 'already';
            $message = 'Your email is already verified. You can log in!';

        } elseif (strtotime($user['token_expiry']) < time()) {
            $status  = 'expired';
            $message = 'This verification link has expired. Please register again or contact support.';

        } else {
            // Mark as verified — clear the token so it cannot be reused
            $update = $conn->prepare(
                "UPDATE users SET is_verified = 1, verification_token = NULL, token_expiry = NULL WHERE id = ?"
            );
            $update->bind_param("i", $user['id']);

            if ($update->execute()) {
                $status  = 'success';
                $message = 'Your email has been verified! You can now log in and play 🍌';
            } else {
                $status  = 'invalid';
                $message = 'Something went wrong. Please try again.';
            }
            $update->close();
        }
    }
    $stmt->close();
}
$conn->close();

//  Colour & icon per status 
$icon    = ['success'=>'✅','expired'=>'⏰','already'=>'ℹ️','invalid'=>'❌'][$status] ?? '❌';
$colour  = ['success'=>'#4CAF50','expired'=>'#FF9800','already'=>'#2196F3','invalid'=>'#F44336'][$status] ?? '#F44336';
$heading = ['success'=>'Email Verified!','expired'=>'Link Expired','already'=>'Already Verified','invalid'=>'Invalid Link'][$status] ?? 'Error';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification - Banana Puzzle Game</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .verify-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .verify-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 48px 52px;
            text-align: center;
            max-width: 460px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .verify-icon {
            font-size: 64px;
            margin-bottom: 14px;
        }
        .verify-card h2 {
            color: <?php echo $colour; ?>;
            font-size: 24px;
            margin-bottom: 14px;
        }
        .verify-card p {
            color: rgba(255,255,255,0.85);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .verify-btn {
            display: inline-block;
            padding: 13px 38px;
            background: linear-gradient(135deg, #f7971e, #ffd200);
            color: #1a1a1a;
            font-weight: 700;
            font-size: 15px;
            border-radius: 50px;
            text-decoration: none;
            letter-spacing: 0.5px;
            transition: opacity 0.2s, transform 0.2s;
        }
        .verify-btn:hover {
            opacity: 0.88;
            transform: scale(1.04);
        }
    </style>
</head>
<body>

<div class="verify-wrapper">
    <div class="verify-card">
        <div class="verify-icon"><?php echo $icon; ?></div>
        <h2><?php echo htmlspecialchars($heading); ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>

        <?php if ($status === 'success' || $status === 'already'): ?>
            <a href="login.html" class="verify-btn">🍌 Go to Login</a>
        <?php else: ?>
            <a href="login.html" class="verify-btn">← Back to Home</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>