<?php 
class videoDetailsFormProvider {
    private $conn;

    public function __construct($conn)
    {
        $this->conn=$conn;
    }
    public function createUploadForm(){
        $fileInput = $this ->createFileInput();
        $titleInput = $this ->createTitleInput();
        $descriptionInput = $this ->createDescriptionInput();
        $privacyInput = $this ->createPrivacicyInput();
        $categoriesInput = $this->createCategoriesInput();
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

    private function createFileInput(){
       return '
       <div class="custom-file inputFile d-flex justify-content-center align-items-center flex-column">
            <label class="custom-file-label mb-2" for="customFileLang">Selecionar video de gatinho</label>
            <input class="btn" name="fileInput" type="file" lang="pt-br" required>
        </div>';
    }

    private function createTitleInput(){
        return '
        <div class="form-group">
            <label for="inputTitle">Título</label>
            <input type="text" class="form-control inputTitle" name="inputTitle" id="inputTitle" placeholder="Titulo" required>
        </div>
        ';
    }
    
    private function createDescriptionInput(){
        return '
        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <textarea class="form-control inputDescription" name="inputDescription" placeholder="Descrição" " rows="3"></textarea>
        </div>
        ';
    }

    private function createPrivacicyInput(){
        return '
        <div class="form-group">
            <label for="privacyInput">Selecione Privacidade</label>
            <select class="form-control privacyInput" name="privacyInput">
                <option value="0">Publico</option>
                <option value="1">Privado</option>
            </select>
        </div>
        ';
    }

    private function createCategoriesInput(){
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
           $html.= "<option value='$id'>$name</option>";
          };
        $html.="</select>
           </div>";

        return $html;
      
    }

    private function createUploadButton(){
        return "<button name='uploadButton' class='btn uploadBtn' type='submit'>Upload</button>";
    }
}