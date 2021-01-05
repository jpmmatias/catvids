<?php 
class ButtonProvider {
    
    public static $signInFunc="notSignedIn()";

    public static function createLink($link){
        return User::isLoggedIn() ? $link : ButtonProvider::$signInFunc;
    }
    
    public static function createButton($class,$onclick,$text,$img){
        $image = $img==null ? "" : "<img src='$img' aria-disabled='true' >";
        $onclick= ButtonProvider::createLink($onclick);
        return "
        <button class='$class' onClick='$onclick'>
            $image
            <span class='text'>$text</span>
        </button>
     ";
    }

    public static function createUserProfileButton($conn,$username){
        $userObj = new User($conn,$username);
        $profilePic = $userObj->getProfilePic();
        $link = "profile.php?username=$username";

        return "
                <a href='$link'>
                    <img src='$profilePic' class='profilePic'>
                 </a>
     ";
    }

    public static function createEditVideo($videoId){
       $href="editVideo.php?videoId=$videoId";

       $button=ButtonProvider::createHyperlinkBtn("edit button",$href,"EDITAR VÍDEO",null);
        return "
            <div class='edtiVideoBtnContainer'>
                $button
            </div>
     ";
    }

    public static function createHyperlinkBtn($class,$href,$text,$img){
        $image = $img==null ? "" : "<img src='$img' aria-disabled='true' >";
        return "
        <a href='$href'>
            <button class='$class'>
                $image
                <span class='text'>$text</span>
            </button>
        </a>
     ";
    }

    public static function createSubscriberButton($conn,$userto,$user){
        $isSubscribedTo=$user->isSubscribedTo($userto);
        $buttonText = $isSubscribedTo ? "INSNCRITO" : "INSCREVA-SE";
        $buttonText = $buttonText . " " . $userto->getSubscribedCountFromUser($userto);
        $class= $isSubscribedTo ? 'unsub button' : 'sub button';
        $userto=$userto->getUsername();
        $user=$user->getUsername();
        $onClick= "subscribe(\"$userto\",\"$user\",this)";

       $button= ButtonProvider::createButton($class,$onClick,$buttonText,null);

        return "
        <div class='subscribeButtonContainer'>
            $button
        </div>";
    }

    public static function createUserProfileNavigationButton($conn,$username)
    {
        if (User::isLoggedIn()) {
            return ButtonProvider::createUserProfileButton($conn,$username);
        } else{
            return "
            <div class='notLoged'>
                <a href='signIn.php'>
                 <span class='signInLink'>
                     CRIAR CONTA
                 </span>
                </a>
                <a href='login.php'>
                    <span class='signInLink'>
                        FAZER LOGIN
                    </span>
                </a>
            </div>
            ";
        }
    }

    
}


?>