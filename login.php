<?php


if ( (isset($_POST['id']) && isset($_POST['pwd'])) AND !(empty($_POST['id']) && empty($_POST['pwd'])) ) {

    $listeFichiers = array("csv/id-admin.csv", "csv/id-student.csv", "csv/id-profs.csv");

    foreach($listeFichiers as $fichier) {

        if (file_exists($fichier)) {
            $pointeur = fopen($fichier, "r");

            while ( ($data = fgetcsv($pointeur)) !== FALSE) {
                
                if ( $_POST['id'] == $data[0] && $_POST['pwd'] == $data[1] ) {

                    if ($fichier == $listeFichiers[0]) {
                        header('Location: admin.php');
                        exit;
                    }
                    else if ($fichier == $listeFichiers[1]) {
                        header('Location: vote.php');
                        exit;
                    }
                    else {
                        header('Location: prof.php');
                        exit;
                    }

                }
                
            }

        }

    }

    header('Location: index.php?erreur='.sha1("C'est une erreur !"));
    exit;

}
else {
    header('Location: index.php');
    exit;
}



?>