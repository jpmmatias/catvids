<?php 
require_once('includes/header.php');
require_once('includes/classes/searchResultsProvider.php');

if (!isset($_GET["term"]) || $_GET["term"]== "" ) {
    echo "Você precisa escrever algo para pesquisar vídeo";
    exit();
}
$videos= array();
$term =  $_GET["term"];

if (!isset($_GET["orderBy"]) || $_GET["orderBy"]== 'views') {
   $orderBy='views';
}else{
    $orderBy='created_at';
}

$searchResultsProvider = new SearchResultsProvider($conn,$user);
$videos = $searchResultsProvider->getVideos($term,$orderBy);

$videoGrid = new VideoGrid($conn,$user);
?>
<div class='largeVideoGridContainer'>
    <?php 
        if (sizeof($videos) >0) {
            
            echo $videoGrid->createLarge($videos,sizeof($videos) . " videos achados",true);

        } else{
            echo "<h3>Nenhum resultado encontrado</h3>";
        }
    ?>
</div>



<?php require_once('includes/footer.php');?>