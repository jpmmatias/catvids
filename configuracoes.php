<?php 
require_once("includes/header.php");
require_once("includes/classes/account.php");
require_once("includes/classes/formSanitizer.php");
require_once("includes/classes/settingsFormProvider.php");
require_once("includes/classes/constants.php");
if (!User::isLoggedIn()) {
    header("Location:login.php");
}

$datailsMessage="";
$passwordMessage="";

$formProvider=new SettingsFormProvider();

if (isset($_POST["saveDetailsButton"])) {
   $account= new account($conn);

   $firstName=formSanitizer::sanitizeFormName($_POST["firstName"]);
   $lastName=formSanitizer::sanitizeFormName($_POST["lastName"]);
   $email=formSanitizer::sanitizeFormEmail($_POST["email"]);

   if ($account->updateDetails($firstName,$lastName,$email,$user->getUsername())) {
    $datailsMessage="<div class='alert alert-success'>
        Detalhes atualizados com sucesso!!
    </div>";
    } else{
        $errorMsg=$account->getFirstError();

        if ($errorMsg=="") {
            $errorMsg="Algum problema ocorreu, por favor novamente tente mais tarde";
        }

        $datailsMessage="<div class='alert alert-danger'>
        $errorMsg
    </div>";
    }
}

if (isset($_POST["savePasswordButton"])) {
    $account= new account($conn);

    $oldPassword=formSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    $newPassword=formSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPassword2=formSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    if ($account->updatePassword($oldPassword,$newPassword,$newPassword2,$user->getUsername())) {
        $datailsMessage="<div class='alert alert-success'>
            Senha atualizada com sucesso!!
        </div>";
        } else{
            $errorMsg=$account->getFirstError();
    
            if ($errorMsg=="") {
                $errorMsg="Algum problema ocorreu, por favor tente novamente mais tarde";
            }
    
            $datailsMessage="<div class='alert alert-danger'>
            $errorMsg
        </div>";
        }

}
?>

<div class="settingsContainer column">
    <div class="formSection">
        <div class="message">
            <?php echo $datailsMessage ?>
        </div>
        <?php echo $formProvider->createUserDetailsForm(
            isset($_POST["firstName"]) ? $_POST["firstName"] : $user->getFirstname(),
            isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastname(),
            isset($_POST["email"]) ? $_POST["email"] : $user->getEmail()
        );?>
    </div>
    <div class="formSection">
        <?php echo $formProvider->createPassowrdForm();?>
    </div>

</div>

<?php require_once("includes/footer.php");?>