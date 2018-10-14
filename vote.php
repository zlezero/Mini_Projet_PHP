<?php

require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "etudiant") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un étudiant
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

$voteFile = $fichiersVote."vote-".$_SESSION['id'].".csv"; #On définit le format du fichier de vote
$notes = array("1" => "Très mécontent", "2" => "Mécontent", "3" => "Moyen", "4" => "Satisfait", "5" => "Très satisfait"); #Les différentes propositions de vote


if ( isset($_POST['ue1']) && isset($_POST['ue2']) && isset($_POST['ue3']) && isset($_POST['ue4']) && isset($_POST['ue5'])) { #On vérifie la validité du formulaire

    if (!empty($_POST['ue1']) AND !empty($_POST['ue2']) AND !empty($_POST['ue3']) AND !empty($_POST['ue4']) AND !empty($_POST['ue5'])) {
        
        if (!file_exists($voteFile)) { #Si le fichier de vote n'existe pas

            $pointeur = fopen($voteFile, "w"); #On ouvre alors le fichier en écriture
            
            foreach($listeMatieres as $matieres => $ue) { #Pour chaque matières on écrit l'ue puis le vote associé
                $array = explode("-", $_POST[$ue]);
                $notation = array($ue, $array[1]);
                fputcsv($pointeur, $notation); 
    
            }
    
            fclose($pointeur); #On ferme le fichier
            
        }

    }

}

if (!file_exists($voteFile)) { #Si le fichier de vote n'existe pas cela veut dire que l'étudiant n'a pas encore voté

    echo '<form class="form-group" action="" method="post">';
    
    foreach($listeMatieres as $matieres => $ue) { #Pour chaque matières différentes on affiche un formulaire différent

        ?>

        <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-6">

                <fieldset>

                    <legend><?php echo $matieres ?></legend>
                    <label class="radio-inline">
                    <p>
                        Sélectionnez l'appréciation souhaitée :

                        <?php 
                        
                            foreach($notes as $note => $description) {
                                echo '<input type="radio" name="'.$ue.'" value="'.$ue.'-'.$note.'" id="'.$ue.'-'.$note.'" /> <label for="'.$ue.'-'.$note.'">'.$description.'</label>';
                            }
                        
                        ?>

                    </p>
                    </label>

                </fieldset>

                <hr>

            </div>

        </div>
    
        <?php
    }

    ?>

        <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Voter</button>
            </div>
    
    </form>

    <?php
   

}
else { #Sinon si l'étudiant a déjà voté on affiche les résultats de son vote

    ?>
    
    <div class="jumbotron">
        <h1 class="display-4">
            Votre vote - Etudiant <?php echo $_SESSION['id']; ?>
        </h1>
        <p class='lead'>
            <table cellpadding='20'>
<?php

    if (file_exists($voteFile)) { #On vérifie si le fichier existe bien
        $pointeur = fopen($voteFile, "r"); #On l'ouvre en lecture
        while ( ($data = fgetcsv($pointeur)) !== FALSE) { #On affiche toutes les données du fichier
            echo "<tr><td><b>".$listeUE[$data[0]]."</b></td><td>".$data[1]."/5</td></tr>";
        }
        fclose($pointeur);
    }

}// else

?>
            </table>
        </p>
        <form class="form-group" action="logout.php" method="post">
            <button type="submit" class="btn btn-danger">Se déconnecter</button>
        </form>
    </div>

<?php
require_once($fichiersInclude.'footer.php'); 
?>
