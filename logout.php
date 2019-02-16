<?php
	ob_start();
	session_start();
	unset($_SESSION['username']);
	setcookie('MorChatUserName');
	header('location: index.php');
?>