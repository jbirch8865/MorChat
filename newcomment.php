<?php
	ob_start();
	session_start();
	$Con = mysqli_connect("localhost","root","","MOR");
	$description = str_replace("'", "\'", $_POST['Comment']);
	$query = mysqli_query($Con, "INSERT INTO `comments` SET comment = '".$description."', issue = '".$_POST['issue']."', user = '".$_SESSION['username']."'");
	echo mysqli_error($Con);
	header('location: index.php');
?>
