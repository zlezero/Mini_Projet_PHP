<?php 
	
	require_once("config.php");
	
	if (!estConnecte() OR $_SESSION['role'] != "admin") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un administrateur
		header('Location: index.php'); #On redirige vers la page de connexion
		exit;
	}

	define("FPDF_FONTPATH","fpdf/font/"); 
	//lien vers le dossier " font " 
	
	require("fpdf/fpdf.php"); 
	//lien vers le fichier contenant la classe FPDF
	
	
	
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
		

	$pdf = new FPDF("L","pt","A4"); 
	//création d'une instance de classe:
		//P pour portrait
		//pt pour point en unité de mesure
		//A4 pour le format
		
	// $pdf ->Open(); //indique que l'on crée un fichier PDF
	
	$pdf ->AddPage(); //permet d'ajouter une page
	
	
	//Affichage du titre et du logo
	$pdf ->SetFont('Times','B',18); //choix de la police
	$pdf ->setXY(320,30) ;
	$pdf ->setTitle("Recapitulatif"); //Nomme la page
	$pdf ->Write(100,utf8_decode("Récapitulatif des votes")); //Ecrit le titre
	$pdf ->Image("images/uvsq.jpg",680,0, 157,60); //insertion du logo
	
	
	$pdf ->SetFont('Times','B',11); //choix de la police
	//Affichage du tableau :
	foreach($notes as $num => $note) {
		$pdf ->setXY(70+intval($num)*80,140) ;
		$pdf -> Cell(80,40, utf8_decode($note),1,0, 'C') ;
	}
	$pdf -> Cell(80,40, utf8_decode("Total"),1,0, 'C') ;
	$pdf -> Cell(80,40, utf8_decode("Moyenne"),1,0, 'C') ;
	$pdf -> Cell(80,40, utf8_decode("Ecart-type"),1,0, 'C') ;
	
	
	//Affichage de la colonne des matières
	$pdf ->setXY(40,180) ;
	foreach($listeUE as $ue => $matiere) {
		$pdf -> Cell(110,60, utf8_decode($ue.' - '.$matiere),1,2, 'C') ;
	}
	
	
	$pdf ->SetFont('Times','',11);
	//Affichage des votes
	// on affiche les votes
	$pdf ->setXY(150,180) ;
	$cptr = 0 ;
	foreach ($listeUE as $UE => $matiere) {
		$pdf ->setXY(150,180+$cptr*60) ;
		foreach ($tabVoteUE[$UE] as $nbVotes) {
			$pdf ->Cell(80,60, utf8_decode($nbVotes." (". 100 * round($nbVotes / $tabNbVotes[$UE], 2)."%)"),1,0, 'C') ;
		}
		
		$pdf -> Cell(80,60, utf8_decode($tabNbVotes[$UE]),1,0, 'C') ;
		$pdf -> Cell(80,60, utf8_decode($tabMoyennes[$UE]),1,0, 'C') ;
		$pdf -> Cell(80,60, utf8_decode($tabET[$UE]),1,0, 'C') ;
		
		$cptr++ ;
	}
	
	//Affichage de la date
	$pdf ->setXY(30,30) ;
	$pdf ->Write(0,date('j/n/Y')) ;
	
	//Footer
	$pdf ->setXY(280,530) ;
	$pdf ->Write(0,utf8_decode("IUT de Vélizy - Année 2018-2019 - Département informatique")) ;
	
	$pdf ->Output(); //génère le PDF et l'affiche	

?>