<?php 
require_once('includes/header.php');
require_once('includes/classes/profileGenerator.php');
if (isset($_GET["username"])) {
    $username=$_GET["username"];
} else{
    echo "<h3>Canal não encontrado</h3>";
    exit();
}
$profileGenerator = new ProfileGenerator($conn,$user,$username);
echo $profileGenerator->create();
?>
<?php require_once('includes/footer.php');?>