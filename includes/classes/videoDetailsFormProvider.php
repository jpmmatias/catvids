<?php 
class videoDetailsFormProvider {
    private $conn;

    public function __construct($conn)
    {
        $this->conn=$conn;
    }
    public function createUploadForm(){
        $fileInput = $this ->createFileInput();
        $titleInput = $this ->createTitleInput(null);
        $descriptionInput = $this ->createDescriptionInput(null);
        $privacyInput = $this ->createPrivacicyInput(null);
        $categoriesInput = $this->createCategoriesInput(null);
        $uploadButton= $this->createUploadButton();

        return "
        <form id='uploadVidForm' action='processing.php' method='POST' enctype='multipart/form-data'>
             $titleInput
             $descriptionInput
             $privacyInput
             $categoriesInput
             $fileInput
             $uploadButton
        </form>";
    }

    public function createEditoForm($video){
        $titleInput = $this ->createTitleInput($video->getTitle());
        $descriptionInput = $this ->createDescriptionInput($video->getDescription());
        $privacyInput = $this ->createPrivacicyInput($video->getPrivacy());
        $categoriesInput = $this->createCategoriesInput($video->getCategory());
        $saveButton= $this->createSaveButton();

        return "
        <form id='editVideoForm' method='POST'>
             $titleInput
             $descriptionInput
             $privacyInput
             $categoriesInput
             $saveButton
        </form>";
    }

    private function createFileInput(){
       return '
       <div class="custom-file inputFile d-flex justify-content-center align-items-center flex-column">
            <label class="custom-file-label mb-2" for="customFileLang">Selecionar video de gatinho</label>
            <input class="btn" name="fileInput" type="file" lang="pt-br" required>
        </div>';
    }

    private function createTitleInput($value){
        if ($value==null) {
            $value="";
        }
        return "
        <div class='form-group'>
            <label for='inputTitle'>Título</label>
            <input type='text' class='form-control inputTitle' name='inputTitle' id='inputTitle' placeholder='Titulo' value='$value' required>
        </div>
        ";
    }
    
    private function createDescriptionInput($value){
        if ($value==null) {
            $value="";
        }
        return "
        <div class='form-group'>
            <label for='inputDescription'>Descrição</label>
            <textarea class='form-control inputDescription' name='inputDescription' placeholder='Descrição' rows='3'>
                $value
            </textarea>
        </div>
        ";
    }

    private function createPrivacicyInput($value){
        if ($value==null) {
            $value="";
        }
        $privateSelected = ($value==0) ? "selected='selected'":"";
        $publicSelected = ($value==1) ? "selected='selected'":"";
        return "
        <div class='form-group'>
            <label for='privacyInput'>Selecione Privacidade</label>
            <select class='form-control privacyInput' name='privacyInput'>
                <option value='0' $privateSelected>Publico</option>
                <option value='1' $publicSelected>Privado</option>
            </select>
        </div>
        ";
    }

    private function createCategoriesInput($value){
        if ($value==null) {
            $value="";
        }
        $query= $this->conn->prepare("SELECT * FROM categorias");
        $query->execute();
        
        $html = "
        <div class='form-group'>
            <label for='categoryInput'>Selecione Categoria</label>
            <select class='form-control categorieInput' name='categoryInput'>
        ";
        while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $name=$row['name'];
            $id=$row['id'];
            $selected = ($id==$value) ? "selected='selected'":"";
           $html.= "<option $selected value='$id'>$name</option>";
          };
        $html.="</select>
           </div>";

        return $html;
      
    }

    private function createUploadButton(){
        return "<button name='uploadButton' class='btn uploadBtn' type='submit'>Upload</button>";
    }

    private function createSaveButton(){
        return "<button name='saveButton' class='btn uploadBtn' type='submit'>Salvar</button>";
    }
}