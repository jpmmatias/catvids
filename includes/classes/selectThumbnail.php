<?php 
class SelectThumbanil{
    private $conn,$video;

    public function __construct($conn,$video) {
        $this->video = $video;
        $this->conn = $conn;
    }

    public function create()
    {
       $thumbanailData = $this->getThumbnailData();
       $html="";
       foreach ($thumbanailData as $data) {
          $html .= $this->createThumbnailItem($data);
       }
       return "<div class='thumbnailItemsContainer'>$html</div>";

    }

    private function createThumbnailItem($data)
    {
        $id=$data["id"];
        $url=$data["filePath"];
        $videoId=$data["videoId"];
        $selected=$data["selected"] == 1 ? "selected" : "";
        echo $data["selected"];
        return "<div class='thumbnailItem $selected' onClick='setNewThumbnail($id,$videoId,this)'>
                <img src='$url'>
        </div>";
    }

    private function getThumbnailData()
    {
        $data = array();
        $videoId=$this->video->getId();
        $query=$this->conn->prepare("
            SELECT * FROM thumbnails WHERE videoId=:videoId
        ");
        $query->bindParam(":videoId",$videoId);
        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[]=$row;
        }

        return $data;


    }
}

?>