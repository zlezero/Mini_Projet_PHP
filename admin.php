<?php

require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "admin") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un administrateur
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

$notes = array("1" => "Très mécontent", "2" => "Mécontent", "3" => "Moyen", "4" => "Satisfait", "5" => "Très satisfait"); #Les différentes propositions de vote

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

echo "<div class='jumbotron'>";

//On affiche le tableau de votes pour chaque UE
foreach ($listeUE as $UE => $matiere) {
	
	echo '<h2 class="display-6">'.$UE.'  -  '.$matiere.'</h2>';
	echo "<p class='lead'>Total des votes pour cette matière</p>";

	// AFFICHAGE DES VOTES

	echo "<table border='1' cellpadding='20'><tr>";
	// on affiche les critères de sélection ("très mécontent", etc.)
	foreach ($notes as $n) {
		echo '<td><h4 class="display-6">' . $n . '</h4></td>';
	}
	echo '<th scope="col">TOTAUX</th></tr></thead><tbody><tr>';
	
	// on affiche les votes
	foreach ($tabVoteUE[$UE] as $nbVotes) {
		echo '<td><h6 class="display-6">' . $nbVotes . '</h6></td>';
	}
	
	echo '<td><h6 class="display-6">'.$tabNbVotes[$UE].'</h6></td></tr><tr>';
	// et on affiche la proportion des votes
	foreach ($tabVoteUE[$UE] as $nbVotes) {
		echo '<td><h6 class="display-6">' . 100 * round($nbVotes / $tabNbVotes[$UE], 2) . ' %</h6></td>';
	}
	
	echo "</tr></tbody></table><br><h5>Moyenne : ".$tabMoyennes[$UE]."</h5>" ;
	echo "<h5>Ecart-type : ".$tabET[$UE]."</h5><br><br>" ;
}
?>
	
 
    <form class="form-group" action="logout.php" method="post">
        <button type="submit" class="btn btn-danger" style="margin:20px;">Se déconnecter</button>
    </form>
	
	<form class="form-group" action="creationPDF.php" method="post">
		<button type="submit" class="btn btn-pdf" style="margin:20px;">Format PDF</button>
	</form>
	
</div><!-- jumbotron -->

<?php
require_once($fichiersInclude.'footer.php'); 

?>