<?php 
require_once('includes/header.php');
require_once('includes/classes/comment.php');
require_once('includes/classes/videoPlayer.php');
require_once('includes/classes/videoInfoSection.php');
require_once('includes/classes/commentSection.php');
    if (!isset($_GET["id"])) {
         echo "No url passed into page";
         exit();
    } else{
        $video=new Video($conn,$_GET["id"],$user);
        $video->incrementViews();
        $videoPlayer= new VideoPlayer($video);
        $videoInfo = new VideoInfoSection($video,$user,$conn);
        $commentSection = new CommentSection($video,$user,$conn);
    }
?>
<script src='assets/js/videoPlayerActions.js'></script>
<script src='assets/js/commentActions.js'></script>
<div class="all">
    <div class="watchLeftColumn">
        <?php
    echo $videoPlayer->create(true);
    echo $videoInfo->create();

?>
    </div>
    <?php echo $commentSection->create(); ?>
</div>
<div class="suggestion">
    <?php 
            $videoGrid= new VideoGrid($conn,$user);
            echo $videoGrid->create(null,null,false);
        ?>
</div>


<?php require_once('includes/footer.php');?>