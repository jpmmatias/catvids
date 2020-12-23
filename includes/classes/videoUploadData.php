<?php
class videoUploadData{
    private $videoData, $title,$description,$privacy,$category,$uploadedBy;

    public function __construct($videoData, $title,$description,$privacy,$category,$uploadedBy)
    {
        $this->videoData=$videoData;
        $this->title=$title;
        $this->description=$description;
        $this->privacy=$privacy;
        $this->category=$category;
        $this->uploadedBy=$uploadedBy;
    }

    public function getVideoData(){
        return $this->videoData;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getPrivacy(){
        return $this->privacy;
    }

    public function getCategory(){
        return $this->category;
    }

    public function getuploadedBy(){
        return $this->uploadedBy;
    }

}