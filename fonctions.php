<?php 

session_start();

function afficherErreur($erreur) {
    ?>
    
    <div class="form-control-feedback alert alert-danger alert-dismissible fade show" role="alert">

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>

    <span class="text-danger align-middle">
    <i class="fa fa-close"></i><strong> Erreur :</strong> <?php echo $erreur; ?>
    </span>

    </div> 

    <?php
}

?>