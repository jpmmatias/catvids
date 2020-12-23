<?php require_once('includes/config.php'); 
require_once('includes/classes/formSanitizer.php');
require_once('includes/classes/account.php');
$account = new account($conn);
require_once('includes/classes/constants.php');

if (isset($_POST["submitButton"])) {
    $firstName= formSanitizer::sanitizeFormName($_POST["firstName"]);
    $lastName= formSanitizer::sanitizeFormName($_POST["lastName"]);
    $password= formSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2= formSanitizer::sanitizeFormPassword($_POST["password2"]);
    $username= formSanitizer::sanitizeFormUsername($_POST["username"]);
    $email= formSanitizer::sanitizeFormEmail($_POST["email"]);
    
   $sucess= $account->register($firstName,$lastName,$username,$email,$password,$password2);
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
    <title>Criar Conta - CatVids</title>
</head>

<body>

    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <div class="containerHeadHeader">
                    <div class="logoContainerForm">
                        <img src="assets\imgs\logo.svg" alt='Logo do CatVids'>
                    </div>
                    <h2>Criar Conta</h2>
                </div>
                <h3>Para ver os melhores videos de gatinhos</h3>
            </div>
            <div class="loginForm">
                <form action="signIn.php" method="POST">
                    <?php echo $account->getError(constants::$firstNameCharacters);?>
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input required='true' autocomplete="off" placeholder="Digite o seu nome"
                            value="<?php echo getInputValue("firstName"); ?>" name="firstName" type="text">
                    </div>
                    <?php echo $account->getError(constants::$lastNameCharacters);?>
                    <div class="form-group">
                        <label for="lastName">Sobrenome</label>
                        <input required='true' autocomplete="off" name="lastName"
                            value="<?php echo getInputValue("lastName"); ?>" type="text"
                            placeholder="Digite o seu sobrenome">
                    </div>
                    <?php echo $account->getError(constants::$usernameAlreadyExists);?>
                    <?php echo $account->getError(constants::$usernameCharacters);?>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input value="<?php echo getInputValue("username"); ?>" required='true' autocomplete="off"
                            name="username" type="text" placeholder="Digite o seu username">
                    </div>
                    <?php echo $account->getError(constants::$emailAlreadyExists);?>
                    <?php echo $account->getError(constants::$emailNotValid);?>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input required='true' placeholder="Digite seu email" name="email"
                            value="<?php echo getInputValue("email"); ?>" type="email">
                    </div>
                    <?php echo $account->getError(constants::$passwordToShort);?>
                    <?php echo $account->getError(constants::$passwordTooWeak);?>
                    <?php echo $account->getError(constants::$passwordDiferent);?>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input required='true' autocomplete="off" placeholder="Digite sua senha" name="password"
                            type="password" value="<?php echo getInputValue("password"); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirmar Senha</label>
                        <input required='true' value="<?php echo getInputValue("password2"); ?>"
                            placeholder="Digite sua senha novamente" name="password2" type="password">
                    </div>
                    <input type="submit" name='submitButton'>
                </form>
            </div>
            <a class="signInMessgae" href="login.php">Ja tem uma conta? Faça o login aqui!</a>
        </div>
    </div>

</body>

</html>