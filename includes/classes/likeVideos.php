<?php 

class LikedVideosProvider
{
    private $conn, $user;
    public function __construct($conn,$user) {
        $this->conn = $conn;
        $this->user = $user;
}

    public function getVideos()
    {
        $videos=array();
        $username=$this->user->getUsername();
        $query=$this->conn->prepare("SELECT video_id FROM likes WHERE username=:username AND comment_id=0 ORDER BY id DESC");

        $query->bindParam(":username",$username);
        
        $query->execute();

        while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $videos[] = new Video($this->conn,$row["video_id"],$this->user);
        }

        return $videos;


    }
}


?>