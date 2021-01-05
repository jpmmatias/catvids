<?php
class videoUpdateData{
    private $videoData, $title,$description,$privacy,$category,$uploadedBy;

    public function __construct($videoData, $title,$description,$privacy,$category,$uploadedBy)
    {
        $this->videoData=$videoData;
        $this->title=$title;
        $this->description=$description;
        $this->privacy=$privacy;
        $this->category=$category;
    }

    public function updateDetails($conn,$videoId)
    {
    
        $query = $conn->prepare('UPDATE videos SET title=:title , description=:description , privacy=:privacy , category=:category WHERE id=:videoId');
        $query->bindParam(':videoId',$videoId);
        $query->bindParam(':title',$this->title);
        $query->bindParam(':privacy',$this->privacy);
        $query->bindParam(':description',$this->description);
        $query->bindParam(':category',$this->category);
        $query->execute();
        return true;
      
    }
}
?>