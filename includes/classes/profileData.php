<?php 
class ProfileData{
    private $conn,$profileUser;
    
    public function __construct($conn,$profileUsername) {
        $this->conn = $conn;
        $this->profileUser = new User($conn,$profileUsername);
    }
    
    public function getProfileUsername()
    {
        return $this->profileUser->getUsername();
    }
    public function userExists()
    {
        $query=$this->conn->prepare("SELECT * FROM users WHERE username=:username");
        $profileusername=$this->getProfileUsername();
        $query->bindParam(":username",$profileusername);

        $query->execute();

        return $query->rowCount() != 0 ? true : false;

    }

    public function getCoverPhoto()
    {
        return "assets/imgs/covers/cover.png";
    }

    public function getProfileFullName()
    {
        return $this->profileUser->getFullName();
    }

    public function getProfilePic()
    {
        return $this->profileUser->getProfilePic();
    }

    public function getSubCount()
    {
        return $this->profileUser->getSubscribedCountFromUser($this->profileUser);
    }
    public function getProfileUser()
    {
        return $this->profileUser;
    }
    public function getUserVideos()
    {
        $username=$this->getProfileUsername();
        $query=$this->conn->prepare("SELECT * FROM videos WHERE uploaded_by=:uploadedBy ORDER BY created_at DESC");
        $query->bindParam(":uploadedBy",$username);
        $query->execute();

        $videos=array();

        while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $videos[]=new Video($this->conn,$row,$this->getProfileUser());
        }

        return $videos;
    }

    public function getAllUserDetails()
    {
        return array(
            "Nome"=>$this->getProfileFullName(),
            "Username"=>$this->getProfileUsername(),
            "Inscritos"=>$this->getSubCount(),
            "Total de visualizações"=>$this->getTotalViews(),
            "Data de criação"=>$this->getCreatedAt()
        );
    }
    private function getTotalViews()
    {
        $username=$this->getProfileUsername();
        $query=$this->conn->prepare("
            SELECT sum(views) FROM videos WHERE uploaded_by=:username
        ");
        $query->bindParam(":username",$username);
        $query->execute();

        return $query->fetchColumn();
    }
    private function getCreatedAt()
    {

        $date = $this->profileUser->getCreatedAt();
        return date("j/m/Y", strtotime($date));
    }

}

?>