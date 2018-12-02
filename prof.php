<?php

//On inclut les fichiers nécessaires
require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "professeur") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un professeur
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

//On définit l'ue du professeur, en prennant le dernier caractère de son login
//qui est un chiffre, et qui correspond à son ue (ex : prof01 => ue 1)

$ue_prof = intval(substr($_SESSION['id'], -1));
$votes = array(0, 0, 0, 0, 0); //Nombre de votes pour chaque catégories

//On parcourt tous les fichiers de vote dans le répertoire 'votes' qui ont un nom correct d'un fichier de vote
foreach (glob($fichiersVote."vote-e????.csv") as $filename) {
	
	$pointeur = fopen($filename, "r"); //On ouvre le fichier
	
	while ( ($data = fgetcsv($pointeur)) !== FALSE) {
		
		if (count($data) == 2) { //Si il y n'y a que l'ue et le vote sur une ligne	
			if ($data[0] == ("ue".($ue_prof)) ) { //On regarde si il s'agit de l'ue du prof connecté
				
				$vote = intval($data[1]); //On prend alors le vote
				
				if(intval($vote)<=5 && intval($vote)>=1) { //Si il est correct
					$votes[$vote - 1] += 1; //On l'ajoute
				}
				
			}
			
		}

	}
}

$somme = array_sum($votes);//On stocke cette valeur pour gagner du temps

echo "<div class='jumbotron'>";
echo '<h2 class="display-6">'.$_SESSION['id'].' - '. $listeUE['ue'.strval($ue_prof)] .'</h2>';
echo "<p class='lead'>Total des votes pour votre matière : </p>";

//AFFICHAGE DES VOTES

echo "<table class='table text-center table-striped'><thead class='thead-dark'><tr class='table-primary'>";

//On affiche les critères de sélection ("très mécontent", etc.)
foreach ($notes as $n) {
    echo '<th scope="col">' . $n . '</th>';
}

echo '<th scope="col">TOTAL</th></tr></thead><tbody><tr>';

//On affiche les votes
foreach ($votes as $v) {
    echo '<td>' . $v . '</td>';
}

echo '<td>'. $somme .'</td></tr><tr>';

//Et on affiche la proportion des votes
foreach ($votes as $v) { //Pour tout les votes
	
	if ($v > 0) { //Si il existe des votes (pour éviter la division par 0)
		echo '<td>' . 100 * round($v / $somme, 2) . ' %</td>';
	}
	else {
		echo '<td>0%</td>';
	}
}

?>
            <td></td></tr>
        </tbody>
    </table>
    <form class="form-group" action="logout.php" method="post">
        <button type="submit" class="btn btn-danger" style="margin:20px;">Se déconnecter</button>
    </form>

</div>

<?php require_once($fichiersInclude.'footer.php'); ?>
