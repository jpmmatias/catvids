<?php require_once('includes/config.php'); 
require_once('includes/classes/buttonProvider.php');
require_once('includes/classes/video.php');
require_once('includes/classes/user.php'); 
require_once('includes/classes/videoGrid.php');
require_once('includes/classes/videoGridItem.php'); 
require_once('includes/classes/navigationMenuProvider.php');
require_once('includes/classes/subscriptionsProvider.php'); 
// session_start();
$usernameLoggedIn= User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
$user = new User($conn,$usernameLoggedIn);
// session_destroy();
?>


<!DOCTYPE html>
<html>

<head>
    <link rel='stylesheet' type="text/css" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>CatVids</title>
</head>

<body>
    <div id="pageContainer">
        <header id="mastHeadContainer">
            <button class="btn menuBtn">
                Menu
            </button>
            <div class="middle">
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets\imgs\logo.svg" alt='Logo do CatVids'>
                    </a>
                </div>
                <div class="searchBarContainer">
                    <form action="search.php" method="GET">
                        <input type="search" class="searchBar" name="term" placeholder="Pesquise videos de gatinhos ^^">
                        <div class="searchBtnContainer">
                            <input role="button" aria-roledescription="Botão para pesquisar vídeos" type="image"
                                src="assets\imgs\searchIcon.svg">
                        </div>
                    </form>
                </div>
            </div>
            <?php 
            if (User::isLoggedIn()) {
                echo '<div class="rigthIcon">
                <button type="button" aria-roledescription="Botão para upload de vídeo">
                    <a href="upload.php">
                        <img src="assets\imgs\upload.svg" aria-disabled="true" alt="Icon de upload de video">
                    </a>
                </button>';
            }
            ?>
            <?php 
                    echo ButtonProvider::createUserProfileNavigationButton($conn,$user->getUsername());
               ?>
    </div>
    </header>
    <aside id="sideNavContainer">
        <?php
            $navigationProvider = new NavigationMenuProvider($conn,$user);
            echo $navigationProvider->create();
        ?>
    </aside>
    <main id="mainSectionContainer">
        <div id="mainContentContainer">