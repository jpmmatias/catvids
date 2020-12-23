<?php 
    class Video  
    {
        private $conn,$sqlData,$user;
        public function __construct($conn,$input,$user)
        {
            $this->conn=$conn;
            $this->user=$user;
            if (is_array($input)) {
                $this->sqlData = $input;
            } else{
                $query = $this->conn->prepare("
                SELECT * FROM videos WHERE id=:input
            ");
            $query->bindParam(":input",$input);
            $query->execute();
    
            $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
            }
        }
    
        public function getId()
        {
            return $this->sqlData["id"];
        }
        public function getUploadeBy()
        {
            return $this->sqlData["uploaded_by"];
        }
        public function getTitle()
        {
            return $this->sqlData["title"];
        }
        public function getDescription()
        {
            return  $this->sqlData["description"];
        }
        public function getPrivacy()
        {
            return $this->sqlData["privacy"];
        }
    
        public function getFilePath()
        {
            return $this->sqlData["filePath"];
        }
        public function getCategory()
        {
            return $this->sqlData["category"];
        }
        public function getViews()
        {
            return $this->sqlData["views"];
        }
        public function getDuration()
        {
            return $this->sqlData["duration"];
        }
        public function getCreatedAt()
        {
            $date=$this->sqlData["created_at"];
            return date("j/m/Y",strtotime($date));
        }
        public function incrementViews()
        {
            $videoId = $this->getId();
            $query = $this->conn->prepare("
            UPDATE videos SET views=views+1 WHERE id=:id
        ");
        $query->bindParam(":id",$videoId);
        $query->execute();
        $this->sqlData["views"]=$this->sqlData["views"]++;
        }

        public function getLikes(){
            $videoId = $this->getId();
            $query = $this->conn->prepare("
                SELECT COUNT(*) AS 'count' FROM likes WHERE video_id=:id
            ");
            $query->bindParam(":id",$videoId);
            $query->execute();
            $query= $query->fetch(PDO::FETCH_ASSOC);
            $likes=$query['count'];
            return $likes;
        }
        public function getDislikes(){
            $videoId = $this->getId();
            $query = $this->conn->prepare("
                SELECT COUNT(*) AS 'count' FROM dislikes WHERE video_id=:id
            ");
            $query->bindParam(":id",$videoId);
            $query->execute();
            $query= $query->fetch(PDO::FETCH_ASSOC);
            $likes=$query['count'];
            return $likes;
        }

        public function like(){
            $id=$this->getId();
            $username=$this->user->getUsername();

            if ($this->wasLikedBy()) {
                $undoingLike=$this->conn->prepare("DELETE FROM likes WHERE username=:username AND video_id=:id");

                $undoingLike->bindParam(":id",$id);
                $undoingLike->bindParam(":username",$username);

                $undoingLike->execute();

                $res = array(
                    "likes"=> -1,
                    "dislikes"=>0
                );
                return json_encode($res);
              
            }else{
                $undoingDislike=$this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND video_id=:id");

                $undoingDislike->bindParam(":id",$id);
                $undoingDislike->bindParam(":username",$username);

                $undoingDislike->execute();

                $count = $undoingDislike->rowCount();


                $insertLike=$this->conn->prepare("INSERT INTO likes (username,video_id) VALUES(:username,:id)");

                $insertLike->bindParam(":id",$id);
                $insertLike->bindParam(":username",$username);

                $insertLike->execute();

                $res = array(
                    "likes"=> +1,
                    "dislikes"=>0-$count
                );
                return json_encode($res);

            }

        }

        public function dislike(){
            $id=$this->getId();
            $username=$this->user->getUsername();

            if ($this->wasDislekdedBy()) {
                $undoingDislike=$this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND video_id=:id");

                $undoingDislike->bindParam(":id",$id);
                $undoingDislike->bindParam(":username",$username);

                $undoingDislike->execute();

                $res = array(
                    "likes"=> 0,
                    "dislikes"=>-1
                );
                return json_encode($res);
              
            }else{
                $undoingLike=$this->conn->prepare("DELETE FROM likes WHERE username=:username AND video_id=:id");

                $undoingLike->bindParam(":id",$id);
                $undoingLike->bindParam(":username",$username);

                $undoingLike->execute();

                $count = $undoingLike->rowCount();


                $insertDislike=$this->conn->prepare("INSERT INTO dislikes (username,video_id) VALUES(:username,:id)");

                $insertDislike->bindParam(":id",$id);
                $insertDislike->bindParam(":username",$username);

                $insertDislike->execute();

                $res = array(
                    "likes"=> 0-$count,
                    "dislikes"=>+1
                );
                return json_encode($res);

            }

        }

        public function wasLikedBy(){
            $id=$this->getId();
            $username=$this->user->getUsername();
            
            $query=$this->conn->prepare("SELECT * FROM likes WHERE video_id=:id AND username=:username");

            $query->bindParam(":id",$id);
            $query->bindParam(":username",$username);

            $query->execute();

            return $query->rowCount()>0;
            
        }

        public function wasDislekdedBy(){
            $id=$this->getId();
            $username=$this->user->getUsername();
            
            $query=$this->conn->prepare("SELECT * FROM dislikes WHERE video_id=:id AND username=:username");

            $query->bindParam(":id",$id);
            $query->bindParam(":username",$username);

            $query->execute();

            return $query->rowCount()>0;
            
        }
    }
    

?>