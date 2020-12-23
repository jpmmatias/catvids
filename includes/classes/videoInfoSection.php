<?php 
require_once('videoInfoControls.php');
class VideoInfoSection{
    private $conn,$video,$user;
    public function __construct($video,$user,$conn) {
        $this->video = $video;
        $this->user = $user;
        $this->conn = $conn;
    }
    public function create(){
        return $this->createPrimaryInfo() .$this->createSecondaryInfo();
    }

    private function createPrimaryInfo(){
        $controls = new VideoInfoControls($this->video,$this->user);
        $controls=$controls->create();
        $title = $this->video->getTitle();
        $views = $this->video->getViews();

        return "
        <div class='videoInfo'>
        <h1>$title</h1> 
        <div class='bottomSection'>
            <span class='viewcount'>$views views</span>
            $controls
        </div>
        </div>";
    }

    private function createSecondaryInfo(){
        $description= $this->video->getDescription();
        $uploadDate= $this->video->getCreatedAt();
        $uploadedBy=$this->video->getUploadeBy();
    $profileButton=ButtonProvider::createUserProfileButton($this->conn,$uploadedBy);

        if($uploadedBy==$this->user->getUsername()){
            $actionBtn = ButtonProvider::createEditVideo($this->video->getId());
        }else{
            $userTo= new User($this->conn,$uploadedBy);
            $actionBtn=ButtonProvider::createSubscriberButton($this->conn,$userTo,$this->user);
        }
        
        return "<div class='secondaryInfo'>
                    <div class='topRow'>
                        $profileButton
                        <div class='uploadInfo'>
                            <span class='owner'><a href='profile.php?username='$uploadedBy'>$uploadedBy'</a></span>
                            <span class='date'>Publico em $uploadDate</span>
                    </div>
                    $actionBtn
                </div>";
    }

}


?>