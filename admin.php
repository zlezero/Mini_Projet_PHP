<?php

require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "admin") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un administrateur
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

//Tableau qui va contenir les différentes UE et les notes correspondantes
$tabVoteUE = array( "ue1" => array(0, 0, 0, 0, 0),
					"ue2" => array(0, 0, 0, 0, 0),
					"ue3" => array(0, 0, 0, 0, 0),
					"ue4" => array(0, 0, 0, 0, 0),
					"ue5" => array(0, 0, 0, 0, 0));

//Tableau des totaux
$tabNbVotes = array ("ue1" => 0,
					"ue2" => 0,
					"ue3" => 0,
					"ue4" => 0,
					"ue5" => 0,
					"total" => 0);

//Tableau des moyennes
$tabMoyennes = array ("ue1" => 0,
					"ue2" => 0,
					"ue3" => 0,
					"ue4" => 0,
					"ue5" => 0) ;
					
//Tableau des écarts-types
$tabET = array ("ue1" => 0,
				"ue2" => 0,
				"ue3" => 0,
				"ue4" => 0,
				"ue5" => 0) ;
					
					
// Parccours des fichiers de vote
foreach (glob($fichiersVote."*.csv") as $filename) {
    $file = file($filename);
	
	//A chaque ligne on récupère l'ue et le vote correspondant
	for ($ligne = 0; $ligne < sizeof($file); $ligne++) {
		$ligneVote = explode(',', $file[$ligne]);
		
		$ue = $ligneVote[0] ;
		$vote = $ligneVote[1] ;
		//Ajout du vote au tableau des votes
		$tabVoteUE[$ue][intval($vote)-1] +=1  ;
		
		//Ajout au nombre de votes total et celui de l'ue
		$tabNbVotes["total"] +=1 ;
		$tabNbVotes[$ue] +=1 ;
	
		//Ajout à la moyenne
		$tabMoyennes[$ue] += intval($vote) ;
	}


	//Calcul des moyennes
	foreach($tabMoyennes as $ue => $moyenne) {
		$tabMoyennes[$ue] = $moyenne/$tabNbVotes[$ue] ;
	}

	//Calcul des écarts-types
	foreach ($tabET as $ue => $val) {
		foreach ($tabVoteUE[$ue] as $vote => $nb) {
			$val += $nb*pow(($vote +1 - $tabMoyennes[$ue]),2) ;
		}
		$val = $val/$tabNbVotes[$ue] ;
		$tabET[$ue] = sqrt($val) ;
	}
}

echo "<div class='jumbotron'><div class='texte-centre'>";


//AFFICHAGE DES EN-TETES DU TABLEAU
echo "<table class='table table-striped'><tr><thead class='thead-dark'><th scope='col'></th>";

// on affiche les critères de sélection ("très mécontent", etc.)
foreach ($notes as $n) {
	echo '<th scope="col">' . $n . '</th>';
}
echo "<th scope='col'>Totaux</th><th scope='col'>Moyenne</th><th scope='col'>Ecart-type</th></tr></thead><tbody><tr>";



//On affiche le tableau de votes pour chaque UE
foreach ($listeUE as $UE => $matiere) {
	
	echo '<th>'.$UE.'  -  '.$matiere.'</th>';


	// on affiche les votes
	foreach ($tabVoteUE[$UE] as $nbVotes) {
		echo '<td>' . $nbVotes . '<br /><font size="2">soit '. 100 * round($nbVotes / $tabNbVotes[$UE], 2).' %</font></td>';
	}
	
	//Affichage du total
	echo '<td>'.$tabNbVotes[$UE].'</td>';
	
	//Affichage de la moyenne et de l'écart-type
	echo '<td>'.round($tabMoyennes[$UE],2).'</td>' ;
	echo '<td>'.round($tabET[$UE],2).'</td></tr><tr>';

}

echo "</tr></tbody></table></div>";
?>
	<table>
		<tr>
			<td>
				<form class="form-group" action="creationPDF.php" method="post">
					<button type="submit" class="btn btn-info">Générer le PDF</button>
				</form>
			</td>
			<td>
				<form class="form-group" action="logout.php" method="post">
					<button type="submit" class="btn btn-danger" style="margin:20px;">Se déconnecter</button>
				</form>
			</td>
		</tr>
    </table>
	
</div> <!-- Jumbotron -->

<?php
require_once($fichiersInclude.'footer.php'); 
?>