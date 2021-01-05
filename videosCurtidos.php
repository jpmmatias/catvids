<?php 
require_once('includes/header.php');
require_once('includes/classes/likeVideos.php');
$likeVdieosProvider = new LikedVideosProvider($conn,$user);
$videos=$likeVdieosProvider->getVideos();
$videoGrid=new VideoGrid($conn,$user);
if (!User::isLoggedIn()) {
    header('Location:login.php');
}
?>
<div class="largeVideoGridContainer">
    <?php 
        if (sizeof($videos)>0) {
            echo $videoGrid->createLarge($videos,'Vídeos curtidos',false);
        }else{
            echo '<h4>Nenhum video curtido<?h4>';
        }
    ?>
</div>

<?php require_once('includes/footer.php');?>