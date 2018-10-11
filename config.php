<?php
    session_start();
    require_once('fonctions.php');
    $listeFichiers = array("csv/id-admin.csv", "csv/id-student.csv", "csv/id-profs.csv");
    $listeUE = array("Mathématiques" => "ue1", "Anglais" => "ue2", "Programmation" => "ue3", "Algorithme" => "ue4", "Economie" => "ue5");
    $fichiersInclude = "include/";
    $fichiersVote = "votes/";
?>