<?php 
  require_once('includes/header.php');
  require_once('includes/classes/videoUploadData.php');
  require_once('includes/classes/videoProcessor.php');

  if(isset($_POST["fileInput"])){
    echo "oi";
  }

  if (!isset($_POST["uploadButton"])) {
      echo 'Nenhum arquivo enviado no input';
      exit();
  }



  $videoUpload = new videoUploadData($_FILES["fileInput"],$_POST["inputTitle"],$_POST["inputDescription"],$_POST["privacyInput"],$_POST["categoryInput"],$user->getUsername());

  $videoProcessor = new videoProcessor($conn);
  $wasSucessuful = $videoProcessor->upload($videoUpload);

  if ($wasSucessuful) {
    echo "upload sucessuful";
  }
?>