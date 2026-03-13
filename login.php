<!-- Source :- use AI Tool(claude) -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Check user
    $sql  = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            // ── EMAIL VERIFICATION CHECK ──────────────────────────
            if (isset($user['is_verified']) && $user['is_verified'] == 0) {
                echo "<script>
                        alert('⚠️ Email Not Verified!\\n\\nYour email address has not been verified yet.\\nPlease check your inbox for the verification link.\\n\\nIf you did not receive the email, contact support.');
                        window.location='login.html';
                      </script>";
                exit();
            }
            // ─────────────────────────────────────────────────────

            // Store session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];

            // Redirect to home
            header("Location: home.php");
            exit();

        } else {
            echo "<script>alert('❌ Wrong Password! Please try again.'); window.location='login.html';</script>";
        }

    } else {
        echo "<script>alert('❌ Email not found! Please register first.'); window.location='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>