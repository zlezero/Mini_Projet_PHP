<?php
require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "etudiant") {
    header('Location: index.php');
    exit;
}

$voteFile = $fichiersVote."vote-".$_SESSION['id'];
$notes = array("1" => "Très mécontent", "2" => "Mécontent", "3" => "Moyen", "4" => "Satisfait", "5" => "Très satisfait");


if ( isset($_POST['ue1']) && isset($_POST['ue2']) && isset($_POST['ue3']) && isset($_POST['ue4']) && isset($_POST['ue5'])) {


    if (!file_exists($voteFile)) {

        $pointeur = fopen($voteFile, "w");
        
        foreach($listeMatieres as $matieres => $ue) {
            
            $notation = array($ue, explode("-", $_POST[$ue])[1]);
            fputcsv($pointeur, $notation); 

        }

        fclose($pointeur);
        
    }


}


if (!file_exists($voteFile)) {

    echo '<form action="" method="post">';
    
    foreach($listeMatieres as $matieres => $ue) {

        ?>
        
        <fieldset>

            <legend><?php echo $matieres ?></legend>

            <p>
                Entrez l'appréciation souhaitée :

                <?php 
                
                    foreach($notes as $note => $description) {
                        echo '<input type="radio" name="'.$ue.'" value="'.$ue.'-'.$note.'" id="'.$ue.'-'.$note.'" /> <label for="'.$ue.'-'.$note.'">'.$description.'</label>';
                    }
                
                ?>

            </p>
                        
       </fieldset>
    
    
        <?php
    }

    echo '<input type="submit" value="Voter">';
    echo '</form>';

   

}
else {

    echo "<h1>Votre vote</h1>";

    if (file_exists($voteFile)) {

        $pointeur = fopen($voteFile, "r");

        while ( ($data = fgetcsv($pointeur)) !== FALSE) {
            echo $listeUE[$data[0]]." : ".$data[1]."/5</br>";
        }

    }

}

?>

<form class="form-group" action="logout.php" method="post">
    <button type="submit" class="btn">Se déconnecter</button>
</form>

<?php

require_once($fichiersInclude.'footer.php'); 
?>