<?php
	ob_start();
	$Con = mysqli_connect("localhost","root","","MOR");
	$query = mysqli_query($Con, "UPDATE users SET mute = '".$_GET['mute']."' WHERE username = '".$_GET['user']."'");
	echo mysqli_error($Con);
	echo 'tried to set '.$_GET['user'].' to mute status - '.$_GET['mute'];
?>