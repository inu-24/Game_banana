<?php
include("db.php");

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=$_POST['email'];

// check email exists
$sql="SELECT * FROM users WHERE email=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("s",$email);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows == 0){
    echo "Email not found!";
    exit();
}

$token=bin2hex(random_bytes(50));
$expiry=date("Y-m-d H:i:s",strtotime("+1 hour"));

$sql="UPDATE users SET reset_token=?, reset_expiry=? WHERE email=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("sss",$token,$expiry,$email);
$stmt->execute();

$link="http://localhost/Game_SE - Copy/reset_password.php?token=".$token;

echo "Click this link to reset password:<br>";
echo "<a href='$link'>$link</a>";

}
?>