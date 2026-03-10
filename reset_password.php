<?php
include("db.php");

$token = $_GET['token'] ?? '';

$sql = "SELECT * FROM users WHERE reset_token=? AND reset_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$token);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Invalid or expired token");
}
?>

<form action="update_password.php" method="POST">

<input type="hidden" name="token" value="<?php echo $token;?>">

<input type="password" name="password" placeholder="New Password" required>

<button type="submit">Reset Password</button>

</form>