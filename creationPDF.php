<?php 
	
	require_once("config.php");	
	require("fpdf/fpdf.php"); //lien vers le fichier contenant la classe FPDF
	require_once("fonctions.php") ; //On inclue la fonction de calcul des tableaux
	
	if (!estConnecte() OR $_SESSION['role'] != "admin") { #Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un administrateur
		header('Location: index.php'); #On redirige vers la page de connexion
		exit;
	}

	define("FPDF_FONTPATH","fpdf/font/"); 
	//lien vers le dossier " font " 
	
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
						

	// CREATION DU PDF :
	$pdf = new FPDF("L","pt","A4"); 
	//création d'une instance de classe:
		//L = Landscape (orientation paysage)
		//pt pour point en unité de mesure
		//A4 pour le format
	
	$pdf ->AddPage(); //permet d'ajouter une page
	
	
	$pdf ->SetFont('Times','',12); //choix de la police
	
	//Affichage de la date de création du PDF (date courante)
	$pdf ->setXY(30,30) ;
	$pdf ->Write(0,date('j/n/Y')) ;
	
	
	$pdf ->SetFont('Times','B',18); //choix de la police
	
	//Affichage du titre et du logo
	$pdf ->setXY(320,30) ;
	$pdf ->setTitle("Recapitulatif"); //Nomme la page
	$pdf ->Write(100,utf8_decode("Récapitulatif des votes")); //Ecrit le titre
	$pdf ->Image("images/uvsq.jpg",680,0, 157,60); //insertion du logo
	
	
	$pdf ->SetFont('Times','B',11); //choix de la police
	$pdf -> SetFillColor('150','150','150') ; // Choix de la couleur de remplissage
	
	//Affichage du tableau :
	foreach($notes as $num => $note) {
		$pdf ->setXY(70+intval($num)*80,140) ;
		$pdf -> Cell(80,40, utf8_decode($note),1,0, 'C',true) ;
	}
	$pdf -> Cell(80,40, utf8_decode("Total"),1,0, 'C',true) ;
	$pdf -> Cell(80,40, utf8_decode("Moyenne"),1,0, 'C',true) ;
	$pdf -> Cell(80,40, utf8_decode("Ecart-type"),1,0, 'C',true) ;
	
	
	//Affichage de la colonne des matières
	$pdf ->setXY(40,180) ;
	foreach($listeUE as $ue => $matiere) {
		$pdf -> Cell(110,60, utf8_decode($ue.' - '.$matiere),1,2, 'C', true) ;
	}
	
	
	$pdf ->SetFont('Times','',11); //On modifie la police (plus en gras, car contenu)
	//Affichage des votes
	// on affiche les votes
	$pdf ->setXY(150,180) ;
	$cptr = 0 ;
	foreach ($listeUE as $UE => $matiere) {
		$pdf ->setXY(150,180+$cptr*60) ;
		foreach ($tabVoteUE[$UE] as $nbVotes) {
			$pdf ->Cell(80,60, utf8_decode($nbVotes." (". 100 * round($nbVotes / $tabNbVotes[$UE], 2)."%)"),1,0, 'C') ;
		}
		
		//Affichage de la moyenne, de l'écart-type et du nombre de votes
		$pdf -> Cell(80,60, utf8_decode($tabNbVotes[$UE]),1,0, 'C') ;
		$pdf -> Cell(80,60, utf8_decode(round($tabMoyennes[$UE],2)),1,0, 'C') ;
		$pdf -> Cell(80,60, utf8_decode(round($tabET[$UE],2)),1,0, 'C') ;
		
		$cptr++ ;
	}
	
	//Pied de page : Informations diverses
	$pdf ->setXY(275,520) ;
	$pdf ->Write(0,utf8_decode("IUT de Vélizy - Année 2018-2019 - Département informatique")) ;
	$pdf ->setXY(250,535) ;
	$pdf ->SetFont('Times','',8); //choix de la police
	$pdf ->Write(0,utf8_decode("LEVESQUE Yanis - VATHONNE Thomas - REPAIN Paul - HARDY Raphaël - PREVOT Carmen")) ;
	$pdf ->Output(); //génère le PDF et l'affiche	

?>