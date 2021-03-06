<?php 
include "constants.php";
class account
{
    
    private $conn;
    private $errorArray=array();
    
    function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($firstName,$lastName,$username,$email,$password,$password2)
    {
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password,$password2);
        
        if (empty($this->errorArray)) {
           return $this->insertUserDetails($firstName,$lastName,$username,$email,$password);
        } else{
            return false;
        }
    }

    public function insertUserDetails($firstName,$lastName,$username,$email,$password){
        $password=hash("sha256",$password);
        $profile_pic="assets/imgs/user.svg";
        $query= $this->conn->prepare(
            "INSERT INTO users (firstName,lastName,username,email,password,profile_pic)
            VALUES (:firstName,:lastName,:username,:email,:password,:profile_pic);
            "
        );
        $query->bindParam(":firstName",$firstName);
        $query->bindParam(":lastName",$lastName);
        $query->bindParam(":username",$username);
        $query->bindParam(":email",$email);
        $query->bindParam(":password",$password);
        $query->bindParam(":profile_pic",$profile_pic);
        $query->execute();
        return true;
    }

    private function validateFirstName($firstName)
    {
        if (strlen($firstName)>25 || strlen($firstName)<=1) {
          array_push($this->errorArray,constants::$firstNameCharacters);
        }

    }

    private function validateLastName($lastName)
    {
        if (strlen($lastName)>25 || strlen($lastName)<=1) {
          array_push($this->errorArray,constants::$lastNameCharacters);
        }

    }

    private function validateUsername($username)
    {
        if (strlen($username)>25 || strlen($username)<=5) {
          array_push($this->errorArray,constants::$usernameCharacters);
          return;
        }

        $query= $this->conn->prepare("SELECT username FROM users WHERE username = :username");
        $query->bindParam(":username",$username);
        $query->execute();

        if ($query->rowCount()!=0) {
            array_push($this->errorArray,constants::$usernameAlreadyExists);
        }
    }

    private function validateEmail($email)
    {
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray,constants::$emailNotValid);
            return;
        }
        $query= $this->conn->prepare("SELECT username FROM users WHERE email = :email");
        $query->bindParam(":email",$email);
        $query->execute();

        if ($query->rowCount()!=0) {
            array_push($this->errorArray,constants::$emailAlreadyExists);
        }
    }

    private function validatePassword($password,$password2)
    {
        if ($password != $password2) {
            array_push($this->errorArray,constants::$passwordDiferent);
            return;
        }
        if (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!^(){}<>%@#&*+=_-])[^\s$`,.\/\\;:'\"|]{8,40}$/",$password)) {
            array_push($this->errorArray,constants::$passwordTooWeak);
            return;
          }
        if (strlen($password)<=5) {
            array_push($this->errorArray,constants::$passwordToShort);
            return;
          }
    
    }


    public function getError($error)
    {
       if (in_array($error,$this->errorArray)) {
           return "<span class='errorMsg'>$error</span>";
       }
    }

    public function getFirstError()
    {
       if (!empty($this->errorArray)) {
           return $this->errorArray[0];
       } else{
         return "";
       }
    }

    public function login($username,$password){
        $password=hash("sha256",$password);
        $query = $this->conn->prepare("
            SELECT * FROM users
            WHERE username=:username AND password=:password
        ");
        $query->bindParam(":password",$password);
        $query->bindParam(":username",$username);
        $query->execute();
        if ($query->rowCount()==1) {
            return true;
        } else{
            array_push($this->errorArray,constants::$loginFail);
            return false;
           
        }
    }

    public function updateDetails($firstName,$lastName,$email,$username)
    {
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateNewEmail($email,$username);

        if (empty($this->errorArray)) {
            $query= $this->conn->prepare(
                "UPDATE users SET firstName=:firstName,lastName=:lastName,email=:email WHERE username=:username
                "
            );
            $query->bindParam(":firstName",$firstName);
            $query->bindParam(":lastName",$lastName);
            $query->bindParam(":email",$email);
            $query->bindParam(":username",$username);
            return $query->execute();
         } 

    }

    public function updatePassword($oldPassword,$newPassword,$newPassword2,$username)
    {
        $this->validateOldPassword($oldPassword,$username);
        $this->validatePassword($newPassword,$newPassword2);

        if (empty($this->errorArray)) {
            $newPassword=hash("sha256",$newPassword);
            $query= $this->conn->prepare(
                "UPDATE users SET password=:password WHERE username=:username
                "
            );
            $query->bindParam(":password",$newPassword);
            $query->bindParam(":username",$username);
            return $query->execute();
         } 

    }

    private function validateOldPassword($oldPassword,$username)
    {
        $query= $this->conn->prepare(
            "SELECT password as 'password' FROM users WHERE username=:username
            "
        );
        $query->bindParam(":username",$username);
        $query->execute();
        $res = $query->fetch(PDO::FETCH_ASSOC);
        $password=$res["password"];
        $oldPassword=hash("sha256",$oldPassword);
       
        if ($oldPassword != $password) {
        array_push($this->errorArray,constants::$passwordIncorrect);
        }

    }
    private function validateNewEmail($email,$username)
    {
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray,constants::$emailNotValid);
            return;
        }
        $query= $this->conn->prepare("SELECT username FROM users WHERE email = :email AND username != :username");
        $query->bindParam(":email",$email);
        $query->bindParam(":username",$username);
        $query->execute();

        if ($query->rowCount()!=0) {
            array_push($this->errorArray,constants::$emailAlreadyExists);
        }
    }

}
?>