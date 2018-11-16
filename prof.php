<?php

require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "professeur") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un professeur
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

// On définit l'ue du professeur, en prennant le dernier caractère de son login
// qui est un chiffre, et qui correspond à son ue (ex : prof01 => ue 1)
$ue_prof = intval(substr($_SESSION['id'], -1));
$votes = array(0, 0, 0, 0, 0);// nombre de votes pour chaque catégories

// on parcourt tous les fichiers de vote dans le répertoire 'votes'
foreach (glob($fichiersVote."*.csv") as $filename) {
    $f = file($filename);
    // on ne récupère que la ligne concernant le professeur, ie son ue
    // tout en explodant car le fichier est un csv
    $ligne = explode(',', $f[$ue_prof - 1]);
    $vote = intval($ligne[1]);// on ne prend que le vote; [0] : ue du professeur
    $votes[$vote - 1] += 1;
}

$somme = array_sum($votes);// on stocke cette valeur pour gagner du temps


echo "<div class='jumbotron'>";
echo '<h2 class="display-6">'.$_SESSION['id'].' - '. $listeUE['ue'.strval($ue_prof)] .'</h2>';
echo "<p class='lead'>Total des votes pour votre matière : </p>";

// AFFICHAGE DES VOTES



echo "<table class='table'><thead><tr>";
// on affiche les critères de sélection ("très mécontent", etc.)
foreach ($notes as $n) {
    echo '<th scope="col"  class="texte-centre">' . $n . '</th></td>';
}
echo '<th scope="col" class="texte-centre">TOTAUX</th></tr></thead><tbody><tr>';
// on affiche les votes
foreach ($votes as $v) {
    echo '<td class="texte-centre">' . $v . '</td>';
}
echo '<td class="texte-centre">'. $somme .'</td></tr><tr>';
// et on affiche la proportion des votes
foreach ($votes as $v) {
    echo '<td class="texte-centre">' . 100 * round($v / $somme, 2) . ' %</td>';
}

?>
            </tr>
        </tbody>
    </table>
    <form class="form-group" action="logout.php" method="post">
        <button type="submit" class="btn btn-danger" style="margin:20px;">Se déconnecter</button>
    </form>

</div><!-- jumbotron -->

<?php require_once($fichiersInclude.'footer.php'); ?>
