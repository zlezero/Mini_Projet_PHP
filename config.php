<?php

    session_start(); //Permet de démarrer les sessions sur toutes les pages
    require_once('fonctions.php');
	
	//Variables globales
	
    $listeFichiers = array("csv/id-admin.csv", "csv/id-student.csv", "csv/id-profs.csv");
    $listeMatieres = array("Mathématiques" => "ue1", "Anglais" => "ue2", "Programmation" => "ue3", "Algorithme" => "ue4", "Economie" => "ue5");
    $listeUE = array("ue1" => "Mathématiques", "ue2" => "Anglais", "ue3" => "Programmation", "ue4" => "Algorithme", "ue5" => "Economie");
    $notes = array("1" => "Très mécontent", "2" => "Mécontent", "3" => "Moyen", "4" => "Satisfait", "5" => "Très satisfait"); #Les différentes propositions de vote
	$fichiersInclude = "include/";
    $fichiersVote = "votes/";

?>