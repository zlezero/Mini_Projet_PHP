<?php

require_once("config.php");

if (!estConnecte()) {
    header('Location: index.php');
    exit;
}

?>