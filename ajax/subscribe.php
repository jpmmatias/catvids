<?php 
require_once('../includes/config.php');
if (isset($_POST['userTo']) && isset($_POST['userFrom']) ) {
    $userTo = $_POST['userTo'];
    $userFrom = $_POST['userFrom'];

   $query = $conn->prepare('SELECT * FROM inscricoes WHERE userTo=:userTo AND userFrom=:userFrom');

   $query->bindParam(':userTo',$userTo);
   $query->bindParam(':userFrom',$userFrom);

   $query->execute();

   if ($query->rowCount() == 0) {
    $query = $conn->prepare('INSERT INTO inscricoes (userTo,userFrom) VALUES (:userTo,:userFrom)');

    $query->bindParam(':userTo',$userTo);
    $query->bindParam(':userFrom',$userFrom);
 
    $query->execute();
   }else{
    $query = $conn->prepare('DELETE FROM inscricoes WHERE userTo=:userTo AND userFrom=:userFrom');

    $query->bindParam(':userTo',$userTo);
    $query->bindParam(':userFrom',$userFrom);
 
    $query->execute();
   }

   $query = $conn->prepare('SELECT * FROM inscricoes WHERE userTo=:userTo');
   $query->bindParam(':userTo',$userTo);
   $query->execute();

   echo $query->rowCount();


}
else{
    echo 'Um ou mais params não foram passados pelo subscribe.php';
}

?>