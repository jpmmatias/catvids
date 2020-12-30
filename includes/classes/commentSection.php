<?php 
class CommentSection{
    private $conn,$video,$user;
    public function __construct($video,$user,$conn) {
        $this->video = $video;
        $this->user = $user;
        $this->conn = $conn;
    }
    public function create(){
      return $this->createCommentSection();
    }

    private function createCommentSection(){
        $numOfComments= $this->video->getNumOfComments();
        $postedBy =  $this->user->getUsername();
        $videoId =  $this->video->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->conn,$postedBy);
        $commentAction = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";
        $commentButton = ButtonProvider::createButton('postComment',$commentAction,'COMENTE',null);

        return "<div class='commentSection'>
                <div class='header'>
                    <span class='commentCount'>
                        $numOfComments Comentários
                    </span>
                <div class='commentForm'>
                    $profileButton
                    <textarea class='commentBodyClass' placeholder='Adicione um comentário'></textarea>
                    $commentButton
                </div>
                </div>
                    <div class='comments'></div>
                </div>";
        
    }

  

       

}


?>