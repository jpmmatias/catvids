<?php 
require_once("../includes/config.php");

if (isset($_POST['videoId']) && isset($_POST['thumbId']) ) {
    $videoId=$_POST['videoId'];
    $thumbId=$_SESSION['thumbId'];

    $query = $conn->prepare('UPDATE thumbnails SET selected=0 WHERE video_id=:videoId');
    $query->bindParam(':videoId',$videoId);
    $query->execute();
  
    $query = $conn->prepare('UPDATE thumbnails SET selected=1 WHERE video_id=:videoId AND id=:thumbId');
    $query->bindParam(':videoId',$videoId);
    $query->bindParam(':thumbId',$thumbId);
    $query->execute();
   
   
   }
   

?>