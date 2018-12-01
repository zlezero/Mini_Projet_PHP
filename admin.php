<?php

//On inclut les fichiers nécessaires
require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "admin") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un administrateur
    header('Location: index.php'); #On redirige vers la page de connexion
    exit;
}

//RECUPERATION DES DONNEES
$tabs = calculDonneesAdmin() ;
	
//Tableau qui va contenir les différentes UE et les notes correspondantes
$tabVoteUE = $tabs["Votes"] ;

//Tableau des totaux
$tabNbVotes = $tabs["Totaux"] ;

//Tableau des moyennes
$tabMoyennes = $tabs["Moyennes"] ;
						
//Tableau des écarts-types
$tabET = $tabs["ET"] ;
						

//AFFICHAGE DE LA PAGE
echo "<div class='jumbotron'><div class='texte-centre'>";

//AFFICHAGE DES EN-TETES DU TABLEAU
echo "<table class='table table-striped'><thead class='thead-dark'><th scope='col'></th>";

//On affiche les critères de sélection ("très mécontent", etc.)
foreach ($notes as $n) {
	echo '<th scope="col">' . $n . '</th>';
}

echo "<th scope='col'>Totaux</th><th scope='col'>Moyenne</th><th scope='col'>Ecart-type</th></tr></thead><tbody><tr>";

//On affiche le tableau de votes pour chaque UE
foreach ($listeUE as $UE => $matiere) {
	
	echo '<th>'.$UE.'  -  '.$matiere.'</th>';

	// on affiche les votes
	foreach ($tabVoteUE[$UE] as $nbVotes) {
		
		if ($nbVotes > 0) { //Si il existe des votes (pour éviter la division par 0)
			echo '<td>' . $nbVotes . '<br /><div class="texte-size2">soit '. 100 * round($nbVotes / $tabNbVotes[$UE], 2).' %</div></td>';
		}	
		else {
			echo '<td>' . $nbVotes . '<br /><div class="texte-size2">soit 0 %</div></td>';
		}
	}
	
	//Affichage du total
	echo '<td>'.$tabNbVotes[$UE].'</td>';
	
	//Affichage de la moyenne et de l'écart-type
	echo '<td>'.round($tabMoyennes[$UE],2).'</td>' ;
	echo '<td>'.round($tabET[$UE],2).'</td></tr>';

}

echo "</tbody></table></div>";
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
	
</div>

<?php
require_once($fichiersInclude.'footer.php'); 
?>