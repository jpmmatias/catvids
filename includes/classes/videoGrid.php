<?php
class VideoGrid
{
    private $conn,$user;
    private $largeMode = false;
    private $gridClass='videoGrid';
    public function __construct($conn,$user) {
        $this->conn=$conn;
        $this->user=$user;
    }

    public function create($videos,$title,$showFilter){
        if ($videos==null) {
           $gridItems=$this->generateItems();
        }else{
            $gridItems=$this->generateItemsFromVideo($videos);
        }

        $header="";

        if ($title !=null) {
            $header = $this->createGridHeader($title,$showFilter);
        }

        return "
        $header
        <div class='$this->gridClass'>
            $gridItems
        </div>";
    }

    public function generateItems(){
        $query=$this->conn->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
        $query->execute();

        $elementsHtml="";
        
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->conn,$row,$this->user);
            $item = new VideoGridItem($video,$this->largeMode);

            $elementsHtml.=$item->create();
        }

        return $elementsHtml;
        
    }

    public function generateItemsFromVideo($videos){
        $elementsHtml="";

        foreach ($videos as $video) {
            $item = new VideoGridItem($video,$this->largeMode);
            $elementsHtml .= $item->create();
        }

        return $elementsHtml;
    }

    public function createGridHeader($title,$showFilter){
        $filter ="";
        if ($showFilter) {
            $link ="https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url = parse_url($link);
            //var_dump($url);
            $query=$url["query"];
            parse_str($query,$params);

            unset($params["orderBy"]);
            $newQuery=http_build_query($params);
            $newUrl=basename($_SERVER["PHP_SELF"]) . "?" .$newQuery;

            $filter="<div class='right'>
                <span>Ordenar por:</span>
                <a href='$newUrl&orderBy=created_at'>Data de upload</a>
                <a href='$newUrl&orderBy=views'>Mais visualizado</a>
            </div>";
        }
        $header="<div class='videoGridHeader'>
                    <div class='left'>
                        $title
                    </div>
                    $filter
                </div>";
        return $header;
    }

    public function createLarge($videos,$title,$showFilter)
    {
        $this->gridClass .= "large";
        $this->largeMode=true;
        return $this->create($videos,$title,$showFilter);
    }

}
?>