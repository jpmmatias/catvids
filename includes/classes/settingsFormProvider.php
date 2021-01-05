<?php 
class SettingsFormProvider {

    public function createUserDetailsForm($firstName,$lastName,$email){
        $firstNameInput = $this ->createFirstNameInput($firstName);
        $lastNameInput = $this ->createLastNameInput($lastName);
        $emailInput = $this ->createEmailInput($email);
        $saveButton = $this ->createSaveUserDetailsButton();
      

        return "
        <form action='configuracoes.php' method='POST' enctype='multipart/form-data'>
            <span class='title'>Detalhes do usuário</span>
           $firstNameInput
           $lastNameInput
           $emailInput
           $saveButton
        </form>";
    }

    public function createPassowrdForm(){
        $oldPassword = $this ->createPassword("oldPassword","Senha atual");
        $newPassword = $this ->createPassword("newPassword","Senha nova");
        $confirmationPassword = $this ->createPassword("newPassword2","Confirmar nova senha");
        $saveButton = $this ->createSavePasswordButton();
      

        return "
        <form action='configuracoes.php' method='POST' enctype='multipart/form-data'>
            <span class='title'>Senha</span>
           $oldPassword
           $newPassword
           $confirmationPassword
           $saveButton
        </form>";
    }



    private function createFirstNameInput($value){
        if ($value==null) {
            $value="";
        }
        return "
        <div class='form-group'>
            <label for='inputTitle'>Nome</label>
            <input type='text' class='form-control inputTitle' name='firstName' value='$value' placeholder='Nome' required >
        </div>
        ";
    }
    
    private function createLastNameInput($value){
        if ($value==null) {
            $value="";
        }
        return "
        <div class='form-group'>
            <label for='inputTitle'>Sobrenome</label>
            <input type='text' class='form-control inputTitle' name='lastName' value='$value' placeholder='Sobrenome' required >
        </div>
        ";
    }

    private function createPassword($name,$label){
        return "
        <div class='form-group'>
            <label for='inputTitle'>$label</label>
            <input type='password' class='form-control inputTitle' name='$name' placeholder='$label' required >
        </div>
        ";
    }
    

    private function createSaveUserDetailsButton(){
        return "<button name='saveDetailsButton' class='btn uploadBtn' type='submit'>Salvar</button>";
    }

    
    private function createEmailInput($value){
        if ($value==null) {
            $value="";
        }
        return "
        <div class='form-group'>
            <label for='inputTitle'>Email</label>
            <input type='email' class='form-control inputTitle' name='email' value='$value' placeholder='exemplo@gmail.com' required >
        </div>
        ";
    }
    

    private function createSavePasswordButton(){
        return "<button name='savePasswordButton' class='btn uploadBtn' type='submit'>Salvar</button>";
    }
}