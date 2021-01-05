<?php 

class TrendingProvider
{
    private $conn, $user;
    public function __construct($conn,$user) {
        $this->conn = $conn;
        $this->user = $user;
}

    public function getVideos()
    {
        $videos=array();

        $query=$this->conn->prepare("SELECT * FROM videos WHERE created_at>=now() - INTERVAL 7 DAY ORDER BY views DESC LIMIT 15");

        $query->execute();

        while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($$this->conn,$row,$this->user);
        }

        return $videos;


    }
}


?>