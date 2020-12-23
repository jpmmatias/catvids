<?php 
class videoProcessor{
    private $conn,$ffmpegPath,$ffprobePath;
    private $sizeLimit=500000000;
    private $allowedTypes = array("mp4","flv","mkv","wmv","mpeg","mpg","mov") ;
    
    public function __construct($conn)
    {
        $this->conn=$conn;
        $this->ffmpegPath= realpath("ffmpeg/ffmpeg.exe");
        $this->ffprobePath= realpath("ffmpeg/ffprobe.exe");

    }

    public function upload($videoUploadData){
        $targetDir="uploads/videos/";
        $videoData = $videoUploadData->getVideoData();

        $tempFilePath = $targetDir . uniqid() .basename($videoData["name"]);

        $tempFilePath=str_replace(" ","_",$tempFilePath);

        $isValidData = $this->processData($videoData,$tempFilePath);

        if (!$isValidData) {
            return false;
        }
        if (move_uploaded_file($videoData["tmp_name"],$tempFilePath)) {
            $finalFilePath = $targetDir . uniqid() . ".mp4";

            if (!$this->insertVideoData($videoUploadData,$finalFilePath)) {
                echo "Erro no envio do video";
                return false;
            }

            if (!$this->convertVideoToMp4($tempFilePath,$finalFilePath)) {
               echo "Erro na converção do vídeo";
               return false;
            }

            if (!$this->deleteFile($tempFilePath)) {
                echo "Erro na converção do vídeo";
                return false;
            }

            if (!$this->generateThumbnail($finalFilePath)) {
                echo "Erro na criação de thumbnail";
                return false;
            }

            return true;

            
        } 
    }

    private function processData($videoData,$filePath){
        $videoType = pathInfo($filePath, PATHINFO_EXTENSION);

        if (!$this->isValidSize($videoData)) {
            echo "Arquivo muito grande, não pode ser maior que " . $this->sizeLimit . " bytes";
            return false;
        }
        else if (!$this->isValidType($videoType)) {
            $msg="Arquivo não é de um formato valido, tente mandar nos formatos: ";
            for($i=0; $i < count($this->allowedTypes); $i++) {
                $j=$i+2;
                if ($j ==count($this->allowedTypes)){
                    $msg .= $this->allowedTypes[$i] . " ou ";
                } elseif ($this->allowedTypes[$i] =='mov') {
                    $msg .= $this->allowedTypes[$i] . ".";
                }
                else{
                    $msg .= $this->allowedTypes[$i] . ", ";
                }
            }
            echo "$msg " ;
            return false;
        }

        else if ($this->hasError($videoData)){
            echo "Erro: ". $videoData["error"];
            return false;
        }
        return true;

    }

    private function isValidSize($data){
        return $data["size"]<=$this->sizeLimit;
    }
    private function isValidType($type){
        $lowercased = strtolower($type);
        return in_array($lowercased,$this->allowedTypes);
    }
    private function hasError($data){
        return $data["error"];
    }
    private function insertVideoData($uploadData,$filePath)
    {
        $query = $this->conn->prepare("INSERT INTO videos(title,uploaded_by,description,privacy,filePath,category)
        VALUES(:title,:uploaded_by,:description,:privacy,:filePath,:category);
        ");
        $title = $uploadData->getTitle();
        $uploaded_by =$uploadData->getuploadedBy();
        $description = $uploadData->getDescription();
        $privacy = $uploadData->getPrivacy();
        $category = $uploadData->getCategory();
  
        $query->bindParam(":title", $title);
        $query->bindParam(":uploaded_by",$uploaded_by);
        $query->bindParam(":description", $description);
        $query->bindParam(":privacy",  $privacy);
        $query->bindParam(":filePath", $filePath);
        $query->bindParam(":category", $category);

        return $query->execute();
    }

    public function convertVideoToMp4($tempFilePath,$finalFilePath){
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

        $outputLog= array();
        exec($cmd,$outputLog,$returnCode);

        if ($returnCode != 0) {
            for ($j=0; $j < count($outputLog); $j++) { 
                echo $outputLog[$j] . "<br>";
            }
            return false;
        }

        return true;
    }

    private function deleteFile($filePath){
        if (!unlink($filePath)) {
            echo "Could not delete file\n";
            return false;
        }
        return true;
    }

    public function generateThumbnail($filePath){
        $thumbnailSize="210x118";
        $numThumbnails=3;
        $pathThumbail="uploads/videos/thumbnails";

        $duration = $this->getVideoDuration($filePath);
        $videoId=$this->conn->lastInsertid();
        $this->updateDuration($duration,$videoId);

        for ($i=0; $i <= $numThumbnails; $i++) { 
            $imgName=uniqid() .".jpg";
            $interval =($duration *0.8) / $numThumbnails *$i;
            $fullThumbnailPath = "$pathThumbail/$videoId-$imgName";

            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";

            $outputLog= array();
            exec($cmd,$outputLog,$returnCode);

            if ($returnCode != 0) {
                for ($j=0; $j < count($outputLog); $j++) { 
                    echo $outputLog[$j] . "<br>";
                }

            }

            $selected = $i==1 ? 1:0;

            $query = $this->conn->prepare("
                INSERT INTO thumbnails (videoId,filePath,selected) 
                VALUES (:videoId,:filePath,:selected)
                ");
            $query->bindParam(":videoId",$videoId);
            $query->bindParam(":filePath",$fullThumbnailPath);
            $query->bindParam(":selected",$selected);

            $sucess=$query->execute();

            if (!$sucess) {
                echo "Error inserting thumbnail";
                return false;
            }
        }

        return true;
    }

    private function getVideoDuration($filePath){
     return (int)shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

    private function updateDuration($duration,$videoId){
        $hours= floor($duration/3600);
        $minutes= floor($duration-($hours*3600/60));
        $seconds= floor($duration%60);

        $minutes=($minutes<10) ? "0" . $minutes : $minutes;
        $seconds=($seconds<10) ? "0" . $seconds : $seconds;
        
        $str=($hours>0) ? "$hours:$minutes:$seconds" : "$minutes:$seconds";

        $query = $this->conn->prepare("
        UPDATE videos 
        SET duration=:str
        WHERE id=:videoId;
        ");
        $query->bindParam(":str",$str);
        $query->bindParam(":videoId",$videoId);
        $query->execute();
    }
}
?>