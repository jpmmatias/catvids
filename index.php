<?php require_once('includes/header.php');?>
<div class="videoSection">
    <?php 
        $subscriptionsProvider= new SubscriptionsProvider($conn,$user);

        $subscriptionsVideos=$subscriptionsProvider->getVideos();

        $videoGrid = new VideoGrid($conn,$user->getUsername());

        if (User::isLoggedIn() && sizeof($subscriptionsVideos)>0) {
            echo $videoGrid->create($subscriptionsVideos,"Sugestões",false);
        }

        echo $videoGrid->create(null,"Sugestões",false);
        ?>
</div>
<?php require_once('includes/footer.php');?>