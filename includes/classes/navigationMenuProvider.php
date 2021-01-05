<?php 
class   NavigationMenuProvider
{
    private $conn, $user;
    
    public function __construct($conn,$user)
    {
        $this->conn=$conn;
        $this->user=$user;
    }

    public function create()
    {
        $menuHtml = $this->createNavItem("Home","assets/imgs/icons/home.png","index.php");
        $menuHtml .= $this->createNavItem("Populares","assets/imgs/icons/trending.png","populares.php");
        $menuHtml .= $this->createNavItem("Incrições","assets/imgs/icons/subscriptions.png","inscricoes.php");
        $menuHtml .= $this->createNavItem("Videos curtidos","assets/imgs/icons/thumb-up.png","videosCurtidos.php");


        if (User::isLoggedIn()) {
            $menuHtml .= $this->createNavItem("Configurações","assets/imgs/icons/settings.png","configuracoes.php");
            $menuHtml .= $this->createNavItem("Sair","assets/imgs/icons/logout.png","logout.php");
            $menuHtml .= $this->createSubSection();
        }


        return "<div class = 'navvigationItems'>
                    $menuHtml
                </div>";
        
    }

    private function createNavItem($text,$icon,$link)
    {
        return "<div class='navigationItem'>
                    <a href='$link'>
                        <img src='$icon'>
                        <span>$text</span>
                    </a>
                </div>
        ";
    }

    private function createSubSection()
    {
        $subscriptions= $this->user->getSubscriptions();

        $html = "<span class='heading'>
                    Inscrições
                </span>
                ";
        foreach ($subscriptions as $sub) {
            $subUsername=$sub->getUsername();
            $html .= $this->createNavItem($subUsername,$sub->getProfilePic,"profile.php?username=$subUsername");
        }
        return $html;
    }
}





?>