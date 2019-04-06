<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  (function(seconds) {
    var refresh,       
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href = location.href;
            }, seconds * 1000);
        };
 
    $(document).on('keypress click touch input', function() { intvrefresh() });
    intvrefresh();

}(15)); // define here seconds


var PageTitleNotification = {
	Vars:{
		OriginalTitle: document.title,
		Interval: null
	},    
	On: function(notification, intervalSpeed){
		var _this = this;
		_this.Vars.Interval = setInterval(function(){
			 document.title = (_this.Vars.OriginalTitle == document.title)
								 ? notification
								 : _this.Vars.OriginalTitle;
		}, (intervalSpeed) ? intervalSpeed : 1000);
	},
	Off: function(){
		clearInterval(this.Vars.Interval);
		document.title = this.Vars.OriginalTitle;   
	}
}



 function Data(el,issue,user)
            {
				console.log('reading issue # '+issue+' by user '+user);
                el.nextSibling.classList.toggle('Hidden');
				el.classList.remove('postit');
				el.classList.add('postitread');
				PageTitleNotification.Off();
				$.ajax({
					url: 'readissue.php?user='+user+'&issue='+issue,
					type: "GET",
					success: function(result){
						console.log(result);
					},
				})
            } 
 
 function Mute(el,user,mute)
            {
				console.log('toggling mute to '+mute);
				if(mute)
				{
					el.src = 'img/Mutesound.png';
					el.setAttribute( "onclick", "Mute(this,'"+user+"',0)" );
				}else
				{
					el.src = 'img/Playsound.png';
					el.setAttribute( "onclick", "Mute(this,'"+user+"',1)" );
				}
				$.ajax({
					url: 'muteuser.php?user='+user+'&mute='+mute,
					type: "GET",
					success: function(result){
						console.log(result);
					},
				})
            } 
</script>
<link rel="stylesheet" href="css/PostIt.css">
<style type="text/css">
.Visible {
	display: inline-block;
}
.Hidden {
	display: none;
}
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  margin-top:5px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

/* Change the link color to #111 (black) on hover */
li a:hover {
  background-color: #111;
}
.unreadborder {
	border: 2px solid green;
}
</style>
</head>
<body style = "background:#c9af98"><div style = "background-color:black;height:20px;width:100%;margin:0"></div><img src = "img/Mor_Logo.png" height = "100px" width = "300px">


<?php
	function PlaySoundForMe($Con)
	{
		$mute = mysqli_query($Con, "SELECT mute FROM users WHERE username = '".$_SESSION['username']."'");
		$mute = mysqli_fetch_assoc($mute);
		$mute = $mute['mute'];
		if($mute)
		{
			$mute = false;
		}else
		{
			$mute = true;
		}
		return $mute;
	}
	function IHaveReadThisCategory($Category, $Con)
	{
		$query = mysqli_query($Con, "SELECT * FROM issues WHERE category = '".$Category."'");
		While($row = mysqli_fetch_assoc($query))
		{
			if(!IHaveReadThisIssue($row['id'],$Con))
			{
				return false;
			}
		}
		return true;
	}
	function IHaveReadThisIssue($Issue, $Con)
	{
		$didireadthis = mysqli_query($Con, "SELECT * FROM user_read_issue WHERE issue = '".$Issue."' AND user = '".$_SESSION['username']."'");
		if(mysqli_num_rows($didireadthis) > 0)
		{
			$didireadthis = true;
		}else
		{
			$didireadthis = false;
		}

		$countcomments = mysqli_query($Con, "SELECT * FROM comments WHERE issue = '".$Issue."'");
		$countcomments = mysqli_num_rows($countcomments);
		if($countcomments > 0)
		{
			$countunreadcomments = mysqli_query($Con, "SELECT * FROM user_read_comments INNER JOIN comments on comments.id = user_read_comments.comment WHERE user_read_comments.user = '".$_SESSION['username']."' AND comments.issue = '".$Issue."'");
			$countunreadcomments = mysqli_num_rows($countunreadcomments);
			if($countunreadcomments == $countcomments && $didireadthis)
			{
				$didireadthis = true;
			}else
			{
				$didireadthis = false;
			}
		}	
		return $didireadthis;
	}
	function countcomments($issue, $Con)
	{
		$countcomments = mysqli_query($Con, "SELECT * FROM comments WHERE issue = '".$issue."'");
		$countcomments = mysqli_num_rows($countcomments);
		
	}
	function SetUserName()
	{
		if(!isset($_SESSION['username']) && !isset($_COOKIE['MorChatUserName']))
		{
			return false;
		}else
		{
			if(!isset($_SESSION['username']))
			{
				$_SESSION['username'] = $_COOKIE['MorChatUserName'];
			}
		}
		return true;
	}
$Con = mysqli_connect("localhost","webuser","","MOR");

	session_start();
	$categories = mysqli_query($Con, "SELECT * FROM note_categories");
	$note_categories = array();
	While($row = mysqli_fetch_assoc($categories))
	{
		$note_categories[$row['id']] = $row['Name'];
	}

	if(!SetUserName())
	{
		echo '<div style = "margin:10%;height:200px;width:250px;">';
		echo '<h2>Please pick your station or enter your name to post as your self.</h2>
			<form action = "login.php" method = "get">
			Station:<SELECT name = "Station"><option value = "null"></option>';
		ForEach($note_categories as $key => $value)
		{
			echo '<option value = "'.$value.'">'.$value.'</option>';
		}	
		echo '</SELECT><br>';
		echo 'First Name:<input type = "text" name = "Fname"><br>
				Last Name:<input type = "text" name = "Lname">
				<br>
				<input type = "submit">
			</form>
		
		</div>';
		exit();
	}
	if(!isset($_GET['category']))
	{
		$boardcategory = 'null';
		$query = mysqli_query($Con, "SELECT * FROM `issues` WHERE DATE(`timestamp`) = CURDATE()");
	}else
	{
		$boardcategory = $_GET['category'];
		$query = mysqli_query($Con, "SELECT * FROM `issues` WHERE DATE(`timestamp`) = CURDATE() AND category = '".$_GET['category']."'");		
	}
		$play_sound = false;
	echo '<ul>';
	ForEach($note_categories as $key => $value)
	{
		if(IHaveReadThisCategory($key, $Con))
		{
			echo '<li><a href="index.php?category='.$key.'">'.$value.'</a></li>';
		}else
		{
			echo '<li class = "unreadborder"><a href="index.php?category='.$key.'">'.$value.'</a></li>';
		}
	}
	echo '<li><a href="index.php">Show All</a></li>';
	echo '<li style = "float:right;"><a href="logout.php">LOGOUT</a></li>';
	if(PlaySoundForMe($Con))
	{
		echo '<li style = "float:right;background-color:grey;"><img onclick = "Mute(this,\''.$_SESSION['username'].'\',1)" src = "img/Playsound.png" height = "50px;" width = "50px;"></li>';
	}else
	{
		echo '<li style = "float:right;background-color:grey;"><img onclick = "Mute(this,\''.$_SESSION['username'].'\',0)" src = "img/Mutesound.png" height = "50px;" width = "50px;"></li>';
	}
	echo '</ul>';
	echo '<h2>Welcome '.$_SESSION['username'].'</h2>';
	While($row = mysqli_fetch_assoc($query))
	{
		$didireadthis = IHaveReadThisIssue($row['id'], $Con);
		
		if($didireadthis)
		{
			$class = "postitread";
		}else
		{
			$class = "postit";
			$play_sound = true;
		}
		echo '<div name = "test" class = "'.$class.'" onclick = "Data(this, \''.$row['id'].'\', \''.$_SESSION['username'].'\')"><span style = "position:absolute;top:5px;left:5px">'.$row['user'].'</span><h2>'.$row['Name'].'</h2><div style = "margin-left:15%;font-size:12pt">'.$row['Description'].'</div><span style = "position:absolute;bottom:5px;right:5px">'.countcomments($row['id'], $Con).'</span></div>';
		$subquery = mysqli_query($Con, "SELECT * FROM `comments` WHERE issue = '".$row['id']."'");
		echo '<div class = "Hidden Visible">';
		while($row2 = mysqli_fetch_assoc($subquery))
		{
			echo '<div class = "postit"><span style = "position:absolute;top:5px;left:5px">'.$row2['user'].'</span><br>'.$row2['comment'].'</div>';
		}
		echo '<div class = "postit"><form action = "newcomment.php" method = "POST"><input type = "text" name = "Comment" style = "opacity: .33"><input type = "hidden" name = "issue" value = "'.$row['id'].'"><input class = "button" type = "submit"></form></div>';
		echo '</div>';
	}
	if($play_sound)
	{

		echo "<script>";
		if(PlaySoundForMe($Con))
		{
			echo "var audio = new Audio('sounds/dixie-horn_daniel-simion.mp3');
			audio.play();";
		}
		echo "var PageTitleNotification = {
				Vars:{
					OriginalTitle: document.title,
					Interval: null
				},    
				On: function(notification, intervalSpeed){
					var _this = this;
					_this.Vars.Interval = setInterval(function(){
						 document.title = (_this.Vars.OriginalTitle == document.title)
											 ? notification
											 : _this.Vars.OriginalTitle;
					}, (intervalSpeed) ? intervalSpeed : 1000);
				},
				Off: function(){
					clearInterval(this.Vars.Interval);
					document.title = this.Vars.OriginalTitle;   
				}
			}
			PageTitleNotification.On('New Message!');
			</script>";
	}
	echo '
	<br><hr><br>
	<form action = "newissue.php" method = "POST" class = "postitread" ><input type = "hidden" name = "boardcategory" value = "'.$boardcategory.'">Type of Post:<select name = "category">';
		ForEach($note_categories as $key => $value)
		{
			echo '<option value = "'.$key.'">'.$value.'</option>';
		}
	echo '</select><br>Note Title:<input style = "opacity:.5" type = "text" name = "Name" placeholder = "New Issue..."><br>Description:<br><textarea style = "opacity:.5;" rows = 3 cols = 20 name = "Description"></textarea><br>date to post:<br><input type = "Date" name = "date" value = "'.date('Y-m-d').'"><br><input type = "submit" class = "button"></form>';
?>
</body>
</html>