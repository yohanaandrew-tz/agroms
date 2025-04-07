<?php
include '../includes/connect.php';
$user_id = $_SESSION['user_id'];


$name = htmlspecialchars($_POST['name']);
$username = htmlspecialchars($_POST['username']);
if(!empty($_POST['password'])){
$password =  htmlspecialchars(password_hash($_POST['password'], PASSWORD_DEFAULT));
$password = ", `password` = '{$password}'";
}
$phone = $_POST['phone'];
$email = htmlspecialchars($_POST['email']);
$address = htmlspecialchars($_POST['address']);
$sql = "UPDATE users SET name = '$name', username = '$username', contact=$phone, email='$email', address='$address' {$password} WHERE id = $user_id;";
if($con->query($sql)==true){
	$_SESSION['name'] = $name;
	$wallet = $con->query("SELECT id FROM `wallet` where customer_id = '{$user_id}' ");
	if($wallet->num_rows > 0){
		$wallet_id =  $wallet->fetch_array()[0];
		$number = htmlspecialchars($_POST['number']);
		$cvv = htmlspecialchars($_POST['cvv']);
		$wallet_d_sql = "UPDATE `wallet_details` set `number` = '{$number}',`cvv` = '{$cvv}' where wallet_id = '{$wallet_id}' ";
		$con->query($wallet_d_sql);
	}
}
header("location: ../details.php");
?>