<?php 
 require_once('../includes/config.php');
 require_once('../includes/classes/comment.php');
 require_once('../includes/classes/user.php');

 $username = $_SESSION["userLoggedIn"];
 $user=new User($conn,$username);
 $videoId = $_POST["videoId"];
 $commentId = $_POST["commentId"];
 $comment = new Comment($conn,$commentId,$user,$videoId);

echo $comment->like();
 

?>