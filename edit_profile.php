<!-- Source :- use AI Tool(claude) -->
 
<?php
session_start();
require_once("db.php");

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// UPDATE USER
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $fullname, $email, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php?updated=1");
        exit();
    } else {
        $error = "Update failed.";
    }

    $stmt->close();
}

// GET CURRENT DATA
$stmt = $conn->prepare("SELECT fullname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Banana Math Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="section">
    <h1>Edit Profile</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <div class="profile-container">
        <div class="profile-card">

            <form method="POST">

                <label>Full Name:</label>
                <input type="text" name="fullname"
                       value="<?php echo htmlspecialchars($user['fullname']); ?>"
                       required>

                <br><br>

                <label>Email:</label>
                <input type="email" name="email"
                       value="<?php echo htmlspecialchars($user['email']); ?>"
                       required>

                <br><br>

                <button type="submit">Save Changes</button>
                <button type="button" onclick="window.location.href='profile.php'">
                    Cancel
                </button>

            </form>

        </div>
    </div>
</section>

</body>
</html>