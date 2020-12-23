<?php 
require_once('includes/header.php');
require_once('includes/classes/videoPlayer.php');
require_once('includes/classes/videoInfoSection.php');
    if (!isset($_GET["id"])) {
         echo "No url passed into page";
         exit();
    } else{
        $video=new Video($conn,$_GET["id"],$user);
        $video->incrementViews();
        $videoPlayer= new VideoPlayer($video);
        $videoInfo = new VideoInfoSection($video,$user,$conn);
    }
?>
<script src='assets/js/videoPlayerActions.js'></script>
<div class="all">
    <div class="watchLeftColumn">
        <?php
    echo $videoPlayer->create(true);
    echo $videoInfo->create();
?>
    </div>
    <div class="suggestion">
    </div>
</div>


<?php require_once('includes/footer.php');?>