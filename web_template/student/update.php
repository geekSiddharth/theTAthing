<?
session_start();
$conn=new mysqli("localhost","root","meetsid20","theTAthing");
$name=$_POST["name"];
$email=$_POST["email"];
$roll_no=$_POST["roll_no"];
$userid=$_SESSION["user"]["user_id"];
$conn->query("update users set name='$name' , email='$email' , roll_no='$roll_no' where user_id='$userid'");
$conn->close();
?>