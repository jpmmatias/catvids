<?php 
require_once("includes/header.php");
require_once("includes/classes/videoDetailsFormProvider.php");
require_once("includes/classes/videoUploadData.php");
require_once("includes/classes/videoUpdateData.php");
require_once("includes/classes/videoPlayer.php");
require_once("includes/classes/selectThumbnail.php");
if (!User::isLoggedIn()) {
    header("Location: login.php");
}
if (!isset($_GET["videoId"])) {
   echo '<h3>Nenhum video selecionado</h3>';
   exit();
}
$videoId=$_GET["videoId"];
$video= new Video($conn,$_GET["videoId"],$user);

if ($video->getUploadeBy() != $user->getUsername()) {
    echo "Esse não é seu video";
    exit();
}
$datailsMessage='';
if (isset($_POST["saveButton"])) {
    $videoData=new VideoUpdateData(
        null,
        $_POST['inputTitle'],
        $_POST['inputDescription'],
        $_POST['privacyInput'],
        $_POST['categoryInput'],
        $user->getUsername()
    );

    if ($videoData->updateDetails($conn,$videoId)) {
        $datailsMessage="<div class='alert alert-success'>
            Detalhes atualizados com sucesso!!
        </div>";
        $video= new Video($conn,$_GET["videoId"],$user);
        } else{
            $datailsMessage="<div class='alert alert-danger'>
            Algo deu errado, tente novamente mais tarde
        </div>";
        }
}
?>
<div class='editVideoContainer column'>
    <div class="topSection">
        <?php $videoPlayer = new VideoPlayer($video); 
        $selectThumbnail = new SelectThumbanil($conn,$video);

        echo $videoPlayer->create(false);
        echo $selectThumbnail->create();
        ?>
    </div>
    <div class="bottomSection">
        <?php 
       
        echo $datailsMessage;
            $formProvider= new videoDetailsFormProvider($conn);

            echo $formProvider->createEditoForm($video);
        ?>
    </div>
</div>
<script src="assets/js/editVideoActions.js"></script>
<?php require_once("includes/footer.php")?>