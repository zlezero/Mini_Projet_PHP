<?php
    session_start();
    require_once('fonctions.php');
    $listeFichiers = array("csv/id-admin.csv", "csv/id-student.csv", "csv/id-profs.csv");
    $listeMatieres = array("Mathématiques" => "ue1", "Anglais" => "ue2", "Programmation" => "ue3", "Algorithme" => "ue4", "Economie" => "ue5");
    $listeUE = array("ue1" => "Mathématiques", "ue2" => "Anglais", "ue3" => "Programmation", "ue4" => "Algorithme", "ue5" => "Economie");
    $fichiersInclude = "include/";
    $fichiersVote = "votes/";
?>