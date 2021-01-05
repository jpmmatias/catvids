<?php 
require_once("profileData.php");
class ProfileGenerator{
    private $conn,$user,$profileData;
    
    public function __construct($conn,$user,$profileUsername) {
        $this->conn = $conn;
        $this->user = $user;
        $this->profileData = new ProfileData($conn,$profileUsername);
    }
    public function create()
    {
        $profileUsername=$this->profileData->getProfileUsername();
        if (!$this->profileData->userExists()) {
            return "<h3>Usuário não existe</h3>";
        }

        $coverPhotoSection = $this->createCoverPhotoSection();
        $headerSection = $this->createHeader();
        $tabSection = $this->createTabSection();
        $contentSection = $this->createContentSection();
        return "
        <div class='profileContainer'>
            $coverPhotoSection
            $headerSection
            $tabSection
            $contentSection
        </div>
        ";
    }

    public function createCoverPhotoSection()
    {
        $coverPhotoSrc = $this->profileData->getCoverPhoto();

        $name=$this->profileData->getProfileFullName();

        return "
        <div class='coverPhotoContainer'>
            <img src='$coverPhotoSrc' class='coverPhoto'>
            <span class='coverName'>
                $name
            </span>
        </div>
        ";
    }
    public function createHeader()
    {
        $btn = $this->createHeaderButton();
        $profileImage=$this->profileData->getProfilePic();
        $name=$this->profileData->getProfileFullName();
        $subCount=$this->profileData->getSubCount();
        return "<div class='profileHeader'>
                    <div class='userInfoContainer'>
                        <img class='profileImage' src='$profileImage'>
                        <div class='userInfo'>
                            <span class='title'>$name</span>
                            <span class='subCount'>$subCount inscritos</span>
                        </div>
                    </div>
                    <div class='buttonContainer'>
                        <div class='buttonItem'>
                            $btn
                        </div>
                    </div>
                </div>";
    }
    public function createTabSection()
    {
        return "
        <ul class='nav nav-tabs' role='tablist'>
        <li class='nav-item' role='presentation'>
          <a class='nav-link active' id='video-tab' data-bs-toggle='tab' href='#videos' role='tab' aria-controls='videos' aria-selected='true'>Videos</a>
        </li>
        <li class='nav-item' role='presentation'>
          <a class='nav-link' id='about-tab' data-bs-toggle='tab' href='#about' role='tab' aria-controls='about' aria-selected='false'>Sobre</a>
        </li>
      </ul>";
    }
    public function createContentSection()
    {
        $videos = $this->profileData->getUserVideos();
        $videosGridHtml='';
        
        $aboutSection = $this->createAboutSection();

        if (sizeof($videos)>0) {
            $videosGrid= new VideoGrid($this->conn,$this->user);
            $videosGridHtml= $videosGrid->create($videos,null,false);
        }else{
            $videosGridHtml="<span>Esse usuário ainda não tem vídeos</span>";
        }
        return "
        <div class='tab-content channelContent'>
            <div class='tab-pane fade show active'      id='videos' role='tabpanel'       aria-labelledby='video-tab'>

                $videosGridHtml

            </div>
            <div class='tab-pane fade' id='about'       role='tabpanel'   aria-labelledby='profile-tab'>

               $aboutSection

            </div>
        </div>";
    }
    
    private function createHeaderButton()
    {
        if ($this->user->getUsername()==$this->profileData->getProfileUsername()) {
           return "";
        }
        else{
            return ButtonProvider::createSubscriberButton($this->conn,$this->profileData->getProfileUser(),$this->user);
        }
    }

    private function createAboutSection()
    {
        $html="
        <div class='section'>
            <div class='title'>
                <span>Detalhes</span>
            </div>
            <div class='values'>

        ";
        $details= $this->profileData->getAllUserDetails();
        foreach ($details as $key => $value) {
            $html.="<span>$key: $value</span>";
        }
        $html .= "</div></div>";
    return $html;
    }
}

?>