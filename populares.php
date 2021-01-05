<?php 
require_once('includes/header.php');
require_once('includes/classes/trendingProvider.php');
$tredingProvider = new TrendingProvider($conn,$user);
$videos=$tredingProvider->getVideos();
$videoGrid=new VideoGrid($conn,$user);
?>
<div class="largeVideoGridContainer">
    <?php 
        if (sizeof($videos)>0) {
            echo $videoGrid->createLarge($videos,'Videos em destaque',false);
        }else{
            echo '<h4>Sem videos em destaque<?h4>';
        }
    ?>
</div>

<?php require_once('includes/footer.php');?>