<!-- Source :- use AI Tool(claude) -->
<?php
include("db.php");

if($_SERVER["REQUEST_METHOD"]=="POST"){

$token=$_POST['token'];
$password=$_POST['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql="UPDATE users 
SET password=?, reset_token=NULL, reset_expiry=NULL 
WHERE reset_token=?";

$stmt=$conn->prepare($sql);
$stmt->bind_param("ss",$hashed,$token);

if($stmt->execute()){
echo "<script>alert('Password updated successfully'); window.location='login.html';</script>";
}else{
echo "Error updating password";
}

}
?>