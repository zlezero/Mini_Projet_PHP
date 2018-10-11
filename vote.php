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

    echo '<form class="form-group" action="" method="post">';
    
    foreach($listeMatieres as $matieres => $ue) {

        ?>

        <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-6">

                <fieldset>

                    <legend><?php echo $matieres ?></legend>

                    <p>
                        Sélectionnez l'appréciation souhaitée :

                        <?php 
                        
                            foreach($notes as $note => $description) {
                                echo '<input type="radio" name="'.$ue.'" value="'.$ue.'-'.$note.'" id="'.$ue.'-'.$note.'" /> <label for="'.$ue.'-'.$note.'">'.$description.'</label>';
                            }
                        
                        ?>

                    </p>
                                
                </fieldset>

                <hr>

            </div>

        </div>
    
        <?php
    }

    ?>

        <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Voter</button>
            </div>
    
    </form>

    <?php
   

}
else {

    ?>
    
    <div class="row">

        <div class="col-md-3"></div>
        
        <div class="col-md-6">
            <h1>Votre vote</h1>
        </div>

    </div>

    <?php
    if (file_exists($voteFile)) {

        $pointeur = fopen($voteFile, "r");

        while ( ($data = fgetcsv($pointeur)) !== FALSE) {
            echo $listeUE[$data[0]]." : ".$data[1]."/5</br>";
        }

    }

}

?>

<form class="form-group" action="logout.php" method="post">
    <button type="submit" class="btn btn-danger">Se déconnecter</button>
</form>

</div>

<?php
require_once($fichiersInclude.'footer.php'); 
?>