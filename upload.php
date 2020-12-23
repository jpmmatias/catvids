<?php 
  require_once('includes/header.php');
  require_once('includes/classes/videoDetailsFormProvider.php')
?>
<div class="column uploadForm">
    <?php 
      $formProvider = new videoDetailsFormProvider($conn);
      echo $formProvider -> createUploadForm();
    ?>
</div>
<div id='loadingModal' class="modal-bg">
    <div class="modal">
        <h2>Carregando, por favor espere um pouquinho</h2>
        <div class="spinner-border text-primary spinner" role="status">
        </div>
    </div>
</div>



<script src="assets/js/modalUpload.js"></script>
<?php require_once('includes/footer.php');?>