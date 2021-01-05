<?php 
require_once('includes/header.php');
require_once('includes/classes/subscriptionsProvider.php');
$subscriptionsProvider = new SubscriptionsProvider($conn,$user);
$videos=$subscriptionsProvider->getVideos();
$videoGrid=new VideoGrid($conn,$user);
if (!User::isLoggedIn()) {
    header('Location:login.php');
}
?>
<div class="largeVideoGridContainer">
    <?php 
        if (sizeof($videos)>0) {
            echo $videoGrid->createLarge($videos,'Suas inscrições',false);
        }else{
            echo '<h4>Sem videos para mostrar<?h4>';
        }
    ?>
</div>

<?php require_once('includes/footer.php');?>