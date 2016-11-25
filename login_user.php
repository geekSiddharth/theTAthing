<?
session_start();
$conn=new mysqli("localhost","root","meetsid20","theTAthing");
$email=$_POST["email"];
$password=$_POST["password"];
$result=$conn->query("select * from users where email='$email' and password='$password'");
if($result->num_rows==0)
{
	header("Location: login.html");
	$conn->close();
	exit(0);
}
else
{
	$user=$result->fetch_assoc();
	$_SESSION["user"]=$user;
	$conn->close();
	header("Location: dashboard.php");
	exit(0);
}
?>