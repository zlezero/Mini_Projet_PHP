<?php

require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "etudiant") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un étudiant
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

$voteFile = $fichiersVote."vote-".$_SESSION['id'].".csv"; #On définit le format du fichier de vote

// écriture dans le fichier de vote
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
        else {
            $erreur = True;
        }

    }
    else {
        $erreur = True;
    }

}

// vote de l'étudiant
if (!file_exists($voteFile)) { #Si le fichier de vote n'existe pas cela veut dire que l'étudiant n'a pas encore voté
?>
    <div class="jumbotron">

        <h3>Sélectionnez l'appréciation souhaitée pour chaque matière</h3>
        <?php if (isset($erreur)) { afficherErreur("Votre formulaire est incomplet !"); } ?>
        <form class="form-group" action="" method="post">
        <table class='table table-striped text-center'>
            <thead>
                <tr class="table-primary">
                    <th scope="col"><h4 class="display-6">Matières</h4></th>
                    <th  scope="col"><h4 class="display-6">Très mécontent</h4></th>
                    <th  scope="col"><h4 class="display-6">Mécontent</h4></th>
                    <th  scope="col"><h4 class="display-6">Moyen</h4></th>
                    <th  scope="col"><h4 class="display-6">Satisfait</h4></th>
                    <th  scope="col"><h4 class="display-6">Très satisfait</h4></th>
                </tr>
            </thead>
<?php  

    foreach($listeMatieres as $matieres => $ue) { #Pour chaque matières différentes on affiche un formulaire différent
?>
                <tr>
                <td><h4 class="display-6"><?php echo $matieres ?></h4></td>
                <label class="radio-inline">
                <?php 
                    foreach($notes as $note => $description) {
                        echo '<td><input type="radio" name="'.$ue.'" value="'.$ue.'-'.$note.'" id="'.$ue.'-'.$note.'" required /> <label for="'.$ue.'-'.$note.'"></label></td>';
                    }
                ?>
                </tr>
                </label>
    
<?php
    }
?>
        </table>
            <hr class="my-4">
            <button type="submit" class="btn btn-primary">Voter</button>
        </form>
        <form class="form-group" action="logout.php" method="post">
            <button type="submit" class="btn btn-danger" style="float: right;">Se déconnecter</button>
        </form>
    </div>   

    <?php
   

}
else { #Sinon si l'étudiant a déjà voté on affiche les résultats de son vote

    ?>
    
    <div class="jumbotron">
        <h1 class="display-4">
            Votre vote - Etudiant <?php echo $_SESSION['id']; ?>
        </h1><br>
        
        <table class='table table-striped text-center' style="margin:0 auto;width:50%" >
            <thead>
                <tr>
                    <th class="table-dark" scope="col">Matières</th>
                    <th  class="table-dark" scope="col">Notes</th>
                </tr>
            </thead>
<?php

    if (file_exists($voteFile)) { #On vérifie si le fichier existe bien
        $pointeur = fopen($voteFile, "r"); #On l'ouvre en lecture
        while ( ($data = fgetcsv($pointeur)) !== FALSE) { #On affiche toutes les données du fichier
            echo "<tr><td>".$listeUE[$data[0]]."</td><td>".$data[1]."/5</td></tr>";
        }
        fclose($pointeur);
    }
    echo '</table><br><br>
        <form class="form-group" action="logout.php" method="post">
            <button type="submit" class="btn btn-danger" style="float: right;">Se déconnecter</button>
        </form>
    </div>';

}// else

?>




<?php
require_once($fichiersInclude.'footer.php'); 
?>