<?php 
require_once("buttonProvider.php");
class CommentControls{
    private $comment,$user,$conn;
    public function __construct($conn,$comment,$user) {
        $this->comment = $comment;
        $this->user = $user;
        $this->conn = $conn;

    }
    public function create(){
        $replyButton = $this->replyButton();
        $likesCount = $this->likesCount();
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        $replySection = $this->replySection();
        return "
        <div class='controls'>
            $replyButton
            $likesCount
            $likeButton
            $dislikeButton
        </div>
        $replySection";
    }

    private function replyButton()
    {
        $text = "RESPONDER";
        $action="toggleReply(this)";

        return ButtonProvider::createButton(null,$action,$text,null);
        
    }
    private function likesCount()
    {
        $text=$this->comment->getLikes();
        if ($text==0) {
            $text="";
        }
        return "<span class='likeCount'>$text</span>";
    }
    private function replySection()
    {
        $postedBy =  $this->user->getUsername();
        $videoId =  $this->comment->getVideoId();
        $commentId =  $this->comment->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->conn,$postedBy);
        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = ButtonProvider::createButton('cancelComent',$cancelButtonAction,'Cancelar',null);

        $postButtonAction = "postComment(this, \"$postedBy\",$videoId,$commentId,\"repliesSection\")";
        $postButton = ButtonProvider::createButton('postComment',$postButtonAction,'Responder',null);

        return " 
        <div class='commentForm hidden'>
            $profileButton
            <textarea class='commentBodyClass'  placeholder='Adicione um comentário'></textarea>
            $cancelButton
            $postButton
        </div>";
        
        
    }


    private function createLikeButton()
    {
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();
        $onClick = "likeComment(this,$commentId,$videoId)";
        $img="assets/imgs/icons/thumb-up.png";

        if ($this->comment->wasLikedBy()) {
            $img="assets/imgs/icons/thumb-up-active.png";

        }
     return ButtonProvider::createButton("likeBtn","$onClick","",$img);
    }
    private function createDislikeButton()
    {
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();
        $onClick = "dislikeComment(this,$commentId,$videoId)";
        $img="assets/imgs/icons/thumb-down.png";
        if ($this->comment->wasDislekdedBy()) {
            $img="assets/imgs/icons/thumb-down-active.png";

        }
     return ButtonProvider::createButton("dislikeButton","$onClick","",$img);
    }


}

?>