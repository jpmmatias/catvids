<?php 
require_once("ButtonProvider.php");
require_once("commentControls.php");
class Comment{
    
    private $conn,$sqlData,$user,$videoId,$commentId;

    public function __construct($conn,$input,$user,$videoId)
    {
        if (!is_array($input)) {
            $this->commentId=$input;
           $query = $conn->prepare('SELECT * FROM comments WHERE id=:id');
           $query->bindParam(':id',$input);
           $query->execute();
           $input = $query->fetch(PDO::FETCH_ASSOC);
           if(!$input) var_dump($query->errorInfo());
        }
        $this->sqlData=$input;
        $this->conn=$conn;
        $this->user=$user;
        $this->videoId=$videoId;
    }

    public function create(){
        $body= $this->sqlData["body"];
        $postedBy=$this->sqlData["posted_by"];
        $profileButton = ButtonProvider::createUserProfileButton($this->conn,$postedBy);

        $id = $this->sqlData['id'];
        $videoId = $this->getVideoId();
        
        $timespan =$this->time_elapsed_string($this->sqlData["created_at"]);

        $commentControlsClass= new CommentControls($this->conn,$this,$this->user);
        $controls=$commentControlsClass->create();

        $numResponsens=$this->getNumberOfReplies();

        $viewRepliesText ="";
        if ($numResponsens>1) {
            $viewRepliesText="
            <span class='repliesSection viewReplies' onClick='getReplies($id,this,$videoId)'>
                Veja todas as $numResponsens respostas
            </span>";
        } elseif ($numResponsens==1) {
            $viewRepliesText="
            <span class='repliesSection viewReplies' onClick='getReplies($id,this,$videoId)'>
                Veja $numResponsens resposta
            </span>";
        }else{
            $viewRepliesText="
            <div class='repliesSection'></div>
            ";
        }

        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profileButton

                        <div class='mainContainer'>

                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timestamp'>$timespan</span>
                            </div>

                            <div class='body'>
                                $body
                            </div>
                        </div>

                    </div>
                    $controls
                    $viewRepliesText
                </div>";


    }

    public function getNumberOfReplies()
    {
        $id=$this->getId();
        $query=$this->conn->prepare("SELECT COUNT(*) as 'count' FROM comments WHERE response_to=:responseTo");
        $query->bindParam(':responseTo',$id);   
        $query->execute();

        $query = $query->fetch(PDO::FETCH_ASSOC);

        return $query['count'];
    }

    public function getReplies()
    {
        $commentId = $this->getId();
        $videoId = $this->getVideoId();
        $query = $this->conn->prepare("
            SELECT * FROM comments WHERE  response_to=:id
            ORDER BY created_at ASC
        ");
        $query->bindParam(":id",$commentId);
        $query->execute();
        $comments="";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->conn,$row,$this->user,$videoId);
           $comments .=$comment->create();
        }
        return $comments;
    
    }

    public function getLikes()
    {
        $query=$this->conn->prepare('SELECT COUNT(*) as count FROM likes WHERE comment_id=:id');
        $query->bindParam(':id',$this->commentId);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numLikes=$data["count"];

        $query=$this->conn->prepare('SELECT COUNT(*) as count FROM dislikes WHERE comment_id=:id');
        $query->bindParam(':id',$this->commentId);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numDislikes=$data["count"];

        return $numLikes-$numDislikes;
        
    }

    public function getId()
    {
        return $this->sqlData["id"];
    }

    public function getVideoId()
    {
        return $this->videoId;
    }

    public function wasLikedBy(){
        $id=$this->getId();
        $username=$this->user->getUsername();
        
        $query=$this->conn->prepare("SELECT * FROM likes WHERE comment_id=:id AND username=:username");

        $query->bindParam(":id",$id);
        $query->bindParam(":username",$username);

        $query->execute();

        return $query->rowCount()>0;
        
    }

    public function wasDislekdedBy(){
        $id=$this->getId();
        $username=$this->user->getUsername();
        
        $query=$this->conn->prepare("SELECT * FROM dislikes WHERE comment_id=:id AND username=:username");

        $query->bindParam(":id",$id);
        $query->bindParam(":username",$username);

        $query->execute();

        return $query->rowCount()>0;
        
    }

    private function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' atrás' : 'quase agora';
    }

    public function like()
    {
        $id=$this->getId();
        $username=$this->user->getUsername();

        if ($this->wasLikedBy()) {
            $undoingLike=$this->conn->prepare("DELETE FROM likes WHERE username=:username AND comment_id=:id");

            $undoingLike->bindParam(":id",$id);
            $undoingLike->bindParam(":username",$username);

            $undoingLike->execute();

           return -1;
          
        }else{
            $undoingDislike=$this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND comment_id=:id");

            $undoingDislike->bindParam(":id",$id);
            $undoingDislike->bindParam(":username",$username);

            $undoingDislike->execute();

            $count = $undoingDislike->rowCount();


            $insertLike=$this->conn->prepare("INSERT INTO likes (username,comment_id) VALUES(:username,:id)");

            $insertLike->bindParam(":id",$id);
            $insertLike->bindParam(":username",$username);

            $insertLike->execute();

            return 1+$count;

        }

    }
    public function dislike(){
        $id=$this->getId();
        $username=$this->user->getUsername();

        if ($this->wasDislekdedBy()) {
            $undoingDislike=$this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND comment_id=:id");

            $undoingDislike->bindParam(":id",$id);
            $undoingDislike->bindParam(":username",$username);

            $undoingDislike->execute();

            return 1;
          
        }else{
            $undoingLike=$this->conn->prepare("DELETE FROM likes WHERE username=:username AND comment_id=:id");

            $undoingLike->bindParam(":id",$id);
            $undoingLike->bindParam(":username",$username);

            $undoingLike->execute();

            $count = $undoingLike->rowCount();


            $insertDislike=$this->conn->prepare("INSERT INTO dislikes (username,comment_id) VALUES(:username,:id)");

            $insertDislike->bindParam(":id",$id);
            $insertDislike->bindParam(":username",$username);

            $insertDislike->execute();

            return -1-$count;

        }

    }

}

?>