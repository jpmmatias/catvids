<?php 
   require_once('../includes/config.php');
   require_once('../includes/classes/comment.php');
   require_once('../includes/classes/user.php');

   $username=$_SESSION['userLoggedIn'];
   $videoId = $_POST['videoId'];
   $commentId = $_POST['commentId'];
   $user = new User($conn,$username);
   $comment = new Comment($conn,$commentId,$user,$videoId);

   echo $comment->getReplies();
?>