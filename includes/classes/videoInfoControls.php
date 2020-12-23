<?php 
require_once("buttonProvider.php");
class VideoInfoControls{
    private $video,$user;
    public function __construct($video,$user) {
        $this->video = $video;
        $this->user = $user;

    }
    public function create(){
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        return "
        <div class='controls'>
            $likeButton
            $dislikeButton
        </div>";
    }

    private function createLikeButton()
    {
        $text=$this->video->getLikes();
        $videoId = $this->video->getId();
        $onClick = "likeVideo(this,$videoId)";
        $img="assets/imgs/icons/thumb-up.png";

        if ($this->video->wasLikedBy()) {
            $img="assets/imgs/icons/thumb-up-active.png";

        }
     return ButtonProvider::createButton("likeBtn","$onClick","$text",$img);
    }
    private function createDislikeButton()
    {
        $text=$this->video->getDislikes();
        $videoId = $this->video->getId();
        $onClick = "dislikeVideo(this,$videoId)";
        $img="assets/imgs/icons/thumb-down.png";
        if ($this->video->wasDislekdedBy()) {
            $img="assets/imgs/icons/thumb-down-active.png";

        }
     return ButtonProvider::createButton("dislikeButton","$onClick","$text",$img);
    }





}


?>