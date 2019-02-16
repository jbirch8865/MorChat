<?php
	ob_start();
	$Con = mysqli_connect("localhost","root","","MOR");
	$query = mysqli_query($Con, "INSERT INTO `user_read_issue` SET user = '".$_GET['user']."', issue = '".$_GET['issue']."'");
	echo mysqli_error($Con);
	$comments = mysqli_query($Con, "SELECT * FROM comments WHERE issue = '".$_GET['issue']."'");
	echo mysqli_error($Con);
	while($row = mysqli_fetch_assoc($comments))
	{
		mysqli_query($Con, "INSERT INTO `user_read_comments` SET comment = '".$row['id']."', user = '".$_GET['user']."'");
	}
	echo mysqli_error($Con);
?>