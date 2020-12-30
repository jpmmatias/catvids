<?php 
require_once("ButtonProvider.php");
class Comment{
    
    private $conn,$sqlData,$user,$videoId;

    public function __construct($conn,$input,$user,$videoId)
    {
        if (!is_array($input)) {
           $query = $conn->prepare('SELECT * FROM comments WHERE id=:id');
           $query->bindParam(':id',$input);
           $query->execute();
           $input = $query->fetch(PDO::FETCH_ASSOC);
           if(!$input) var_dump($query->errorInfo());
        }
        $this->sqlData=$input;
        $this->conn=$conn;
        $this->user=$user;
        $this->videoId=$videoId;
    }

    public function create(){
        $body= $this->sqlData["body"];
        $postedBy=$this->sqlData["posted_by"];
        $profileButton = ButtonProvider::createUserProfileButton($this->conn,$postedBy);
        
        $timespan = ""; // TODO: get timespan

        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profileButton

                        <div class='mainContainer'>

                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timestamp'>$timespan</span>
                            </div>

                            <div class='body'>
                                $body
                            </div>
                        </div>

                    </div>
                </div>";


    }
}

?>