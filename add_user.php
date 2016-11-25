<?
session_start();
$conn=new mysqli("localhost","root","meetsid20","theTAthing");
$name=$_POST["name"];
$password=$_POST["password"];
$phone=$_POST["phone"];
$email=$_POST["email"];
$roll_no=$_POST["roll_no"];
$isTA=$_POST["isTA"];
$conn->query("insert into users values (null,'$name','dp','$email','$password','$roll_no','$isTA')");
$result=$conn->query("select * from users where email='$email'");
$user=$result->fetch_assoc();
$_SESSION["user"]=$user;
$conn->close();
header("Location: dashboard.php");
exit(0);
?>