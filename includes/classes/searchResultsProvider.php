<?php 
class SearchResultsProvider
{
    private $conn, $user;
    public function __construct($conn,$user) {
        $this->conn = $conn;
        $this->user = $user;
    }

    public function getVideos($term,$orderBy)
    {
        $query=$this->conn->prepare("
            SELECT * FROM videos WHERE title LIKE  CONCAT('%', :term, '%')
            OR uploaded_by LIKE  CONCAT('%', :term, '%') 
            ORDER BY :orderby DESC
            "
        );

        $query->bindParam(":term",$term);
        $query->bindParam(":orderby",$orderBy);
        $query->execute();

        $videos = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->conn,$row,$this->user);
            array_push($videos,$video);
        }
        return $videos;
    }
}



?>