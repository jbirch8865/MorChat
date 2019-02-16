<?php
	ob_start();
	session_start();
	$date = $_POST['date'];
	$Con = mysqli_connect("localhost","root","","MOR");
	$description = str_replace("'", "\'", $_POST['Description']);
	$name = str_replace("'", "\'", $_POST['Name']);
	$query = mysqli_query($Con, "INSERT INTO `Issues` SET TimeStamp = '".$date."', Name = '".$name."', Description = '".$description."', user = '".$_SESSION['username']."', category = '".$_POST['category']."'");
	$issueid = mysqli_insert_id($Con);
	echo mysqli_error($Con);
	$query = mysqli_query($Con, "INSERT INTO `user_read_issue` SET user = '".$_SESSION['username']."', issue = '".$issueid."'");
	echo mysqli_error($Con);
	$comments = mysqli_query($Con, "SELECT * FROM comments WHERE issue = '".$issueid."'");
	echo mysqli_error($Con);
	while($row = mysqli_fetch_assoc($comments))
	{
		mysqli_query($Con, "INSERT INTO `user_read_comments` SET comment = '".$row['id']."', user = '".$_SESSION['username']."'");
	}
	if($_POST['boardcategory'] != 'null')
	{
		header('location: index.php?category='.$_POST['boardcategory']);
	}else
	{
		header('location: index.php');		
	}
?>