<?php 
class User
{
    private $conn,$sqlData;
    public function __construct($conn,$username)
    {
        $this->conn=$conn;
        $query = $this->conn->prepare("
            SELECT * FROM users WHERE username=:username
        ");
        $query->bindParam(":username",$username);
        $query->execute();

        $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
    }

    public static function isLoggedIn(){
        return isset($_SESSION["userLoggedIn"]);

    }

    public function getUsername()
    {
        
        return is_array($this->sqlData) ? $this->sqlData["username"] : "";

    }
    public function getFirstname()
    {
        return $this->sqlData["firstName"];
    }
    public function getLastname()
    {
        return $this->sqlData["lastName"];
    }
    public function getFullName()
    {
        return  $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
    }
    public function getEmail()
    {
        return $this->sqlData["email"];
    }

    public function getProfilePic()
    {
        return is_array($this->sqlData) ? $this->sqlData["profile_pic"] : "";
    }
    public function getCreatedAt()
    {
        return $this->sqlData["created_at"];
    }

    public function isSubscribedTo($userTo)
    {
        $username = $this->getUsername();
        $userTo = $userTo->getUsername();
        $query = $this->conn->prepare("
            SELECT * FROM inscricoes WHERE userTo=:userTo AND userFrom=:username
        ");

        $query->bindParam(":userTo",$userTo);
        $query->bindParam(":username",$username);

        $query->execute();

        return $query->rowCount()>0;
    }

    public function getSubscribedCountFromUser($user)
  
    {
        $username=$user->getUsername();
        $query = $this->conn->prepare("
            SELECT * FROM inscricoes WHERE userTo=:username
        ");

        $query->bindParam(":username",$username);

        $query->execute();
        return $query->rowCount();

    }

    public function getAllSubscriptionFromUser()
    {
        $username = $this->getUsername();
        $query = $this->conn->prepare("
            SELECT userTo FROM inscricoes WHERE userFrom=:username
        ");

        $query->bindParam(":username",$username);

        $query->execute();
        $subscriptions=$query->fetch(PDO::FETCH_ASSOC);
        return $subscriptions;
    }

    public function getSubscriptions()
    {
        $username = $this->getUsername();
        $query = $this->conn->prepare("
            SELECT userTo FROM inscricoes WHERE userFrom=:username
        ");

        $query->bindParam(":username",$username);
        $query->execute();
        
        $subs=array();
        while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($this->conn,$row["userTo"]);
            array_push($subs,$user);
        }
        return $subs;
    }




}



?>