<?php
require_once('config.php');
if ( (isset($_POST['id']) && isset($_POST['pwd'])) AND !(empty($_POST['id']) && empty($_POST['pwd'])) ) {
    foreach($listeFichiers as $fichier) {
        if (file_exists($fichier)) {
            $pointeur = fopen($fichier, "r");
            while ( ($data = fgetcsv($pointeur)) !== FALSE) {
                
                if ( $_POST['id'] == $data[0] && $_POST['pwd'] == $data[1] ) {
                    
                    $_SESSION['id'] = $data[0];
                    if ($fichier == $listeFichiers[0]) { #Si il s'agit d'un admin
                        $_SESSION['role'] = "admin";
                        header('Location: admin.php');
                        exit;
                    }
                    else if ($fichier == $listeFichiers[1]) { #Si il s'agit d'un étudiant
                        $_SESSION['role'] = "etudiant";
                        header('Location: vote.php');
                        exit;
                    }
                    else { #Sinon il s'agit d'un prof
                        $_SESSION['role'] = "professeur"; 
                        header('Location: prof.php');
                        exit;
                    }
                }
                
            }
            fclose($pointeur);
        }
    }
    header('Location: index.php?erreur='.sha1("C'est une erreur !"));
    exit;
}
else {
    header('Location: index.php?erreur='.sha1("C'est une erreur !"));
    exit;
}
?>