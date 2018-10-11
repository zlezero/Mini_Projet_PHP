<?php

session_start();
require_once('fonctions.php');

$listeFichiers = array("csv/id-admin.csv", "csv/id-student.csv", "csv/id-profs.csv");
$roles = array("admin", "etudiant", "professeur")

?>