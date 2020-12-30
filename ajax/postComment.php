<?php 
   require_once('../includes/config.php');
   require_once('../includes/classes/comment.php');
   require_once('../includes/classes/user.php');
   

   if (isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoId']) ) {
    $username=$_SESSION['userLoggedIn'];
    $user=new User($conn,$username);
    $commentText = $_POST['commentText'];
       $postedBy = $_POST['postedBy'];
       $videoId = $_POST['videoId'];
       $replyTo = $_POST['responseTo'];
   
     $query = $conn->prepare('INSERT INTO comments(posted_by,video_id,response_to,body) VALUES(:postedBy,:videoId,:replyTo,:commentText)');
   
      $query->bindParam(':postedBy',$postedBy);
      $query->bindParam(':videoId',$videoId);
      $query->bindParam(':replyTo',$replyTo);
      $query->bindParam(':commentText',$commentText);
   
    $query->execute();
  
    $newComment = new Comment($conn, $conn->lastInsertId(), $user, $videoId);
    echo $newComment->create();
   
     
   
   
   }
   else{
       echo 'Um ou mais params não foram passados pelo comments.php';
   }
   
?>