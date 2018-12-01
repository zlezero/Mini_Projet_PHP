<?php

require_once('config.php');

if ( (isset($_POST['id']) && isset($_POST['pwd'])) AND !(empty($_POST['id']) && empty($_POST['pwd'])) ) { #On vérifie la validité du formulaire

    foreach($listeFichiers as $fichier) { //Pour tout les .csv dans le dossier /csv

        if (file_exists($fichier)) { //Si le fichier existe bien

            $pointeur = fopen($fichier, "r"); //Alors on l'ouvre
			
            while ( ($data = fgetcsv($pointeur)) !== FALSE) { //Tant qu'il y a des données dans le fichier
                
                if ( $_POST['id'] == $data[0] && $_POST['pwd'] == $data[1] ) { //Si le login et le mot de passe correspondent à une entrée dans le fichier
                    
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

    #Si le login/mdp n'est pas présent dans les fichiers
    header('Location: index.php?erreur='.sha1("C'est une erreur !"));
    exit;
}
else { #Si l'envoi du formulaire est incorrect ou que l'on accède à la page d'une autre façon

    if (estConnecte()) { #Si on est déjà connecté lorsque on accède à la page
        redirigerBonnePage();
    }
    
    #Sinon on renvoie à la page de connexion
    header('Location: index.php?erreur='.sha1("C'est une erreur !"));
    exit;

}
?>