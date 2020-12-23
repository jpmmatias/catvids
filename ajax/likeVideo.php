<?php 
 require_once('../includes/config.php');
 require_once('../includes/classes/video.php');
 require_once('../includes/classes/user.php');

 $username = $_SESSION["userLoggedIn"];
 $user=new User($conn,$username);
 $videoId = $_POST["videoId"];
 $video = new Video($conn,$videoId,$user);

echo $video->like();
 

?>