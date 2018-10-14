<?php 

    require_once("config.php");

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
    
    function estConnecte() {
        
        $roles = array("admin", "etudiant", "professeur");
        if (isset($_SESSION['id']) AND isset($_SESSION['role'])) {
            
            if ( (!empty($_SESSION['id'])) AND in_array($_SESSION['role'], $roles) ) {
                return TRUE;
            }
        }
        return FALSE;
    }
?>