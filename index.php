<?php require_once('includes/header.php');
if (User::isLoggedIn()) {
    echo "hello " . $user->getFullName();
}
else{
    echo " User not logged in";
}
?>
<?php require_once('includes/footer.php');?>