<?php 
require_once('includes/config.php'); 
require_once('includes/classes/account.php');
require_once('includes/classes/constants.php');
require_once('includes/classes/formSanitizer.php');

$account = new account($conn);

if (isset($_POST["submitButton"])) {
    $username= formSanitizer::sanitizeFormUsername($_POST["username"]);
    $password= formSanitizer::sanitizeFormPassword($_POST["password"]);
    $sucess=$account->login($username,$password);
    
   if ($sucess) {
        $_SESSION["userLoggedIn"] = $username;
        header("Location:index.php");
        exit();
   } 
}

function getInputValue($value)
{
    if (isset($_POST["$value"])) {
        return $_POST["$value"];
    } else{
        return "";
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <link rel='stylesheet' type="text/css" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Login - CatVids</title>
</head>

<body>

    <div class="loginContainer">
        <div class="column">
            <div class="header">
                <div class="containerHeadHeader">
                    <div class="logoContainerForm">
                        <img src="assets\imgs\logo.svg" alt='Logo do CatVids'>
                    </div>
                    <h2>Login</h2>
                </div>
            </div>
            <?php echo $account->getError(constants::$loginFail);?>
            <div class="loginForm">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input required='true' autocomplete="off" name="username"
                            value="<?php echo getInputValue("username")?>" type="text"
                            placeholder="Digite o seu username">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input value="<?php echo getInputValue("password")?>" required='true' autocomplete="off"
                            placeholder="Digite sua senha" name="password" type="password">
                    </div>
                    <input name="submitButton" type="submit">
                </form>
            </div>
            <a class="signInMessgae" href="signIn.php">Não tem uma conta? Crie aqui!</a>
        </div>
    </div>

</body>

</html>