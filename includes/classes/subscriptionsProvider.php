<?php 
class SubscriptionsProvider 
{
    private $conn, $user;
    public function __construct($conn,$user) {
        $this->conn = $conn;
        $this->user = $user;
    }

    public function getVideos()
    {
        $videos=array();
        $subscriptions=$this->user->getSubscriptions();

        if (sizeof($subscriptions)>0) {
            $condition="";
            $i=0;

            while ($i<sizeof($subscriptions)) {
                if ($i==0) {
                    $condition .= "WHERE uploadedBy=?";
                }else{
                    $condition .= "OR uploadedBy=?";
                }
                $i++;
            }

            $videoSql = "SELECT * FROM videos $condition ORDER BY created_at DESC";
            $videoquery=$this->conn->prepare($videoSql);

            $i=1;

            foreach ($subscriptions as $sub) {
                $subUsername = $sub->getUsername();
                $videoquery->bindValue($i,$subUsername);
                $i++;
            }
            $videoquery->execute();
            while ($row = $videoquery->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->conn,$row,$this->user);
                array_push($videos,$video);
            }


        }
        return $videos;
    }
}


?>