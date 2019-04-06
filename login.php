<?php
	ob_start();
	session_start();
	$usename = false;
	if($_GET['Station'] == 'null')
	{
		$usename = true;
	}
	if(($_GET['Fname'] == '' || $_GET['Lname']) == '' && $usename)
	{
		echo '<script>alert("sorry you need to enter a station or enter in a First and Last name to login");</script>';
	}
	if($usename)
	{
		$name = $_GET['Fname'].' '.$_GET['Lname'];
	}else
	{
		$name = $_GET['Station'];
	}
	$Con = mysqli_connect("localhost","webuser","","MOR");
	$query = mysqli_query($Con, "INSERT INTO users SET username = '".$name."'");
	$_SESSION['username'] = $name;
	setcookie('MorChatUserName',$name);
	if($usename)
	{
		header('location: index.php');
	}else
	{
		$query = mysqli_query($Con, "SELECT id FROM note_categories WHERE Name = '".$name."'");
		$query = mysqli_fetch_assoc($query);
		$query = $query['id'];
		header('location: index.php?category='.$query);
	}
?>