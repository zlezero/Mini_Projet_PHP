<?php

//On inclut les fichiers nécessaires
require_once("config.php");
require_once($fichiersInclude.'head.php');

if (!estConnecte() OR $_SESSION['role'] != "etudiant") { //Si on arrive sur cette page alors que l'on est pas connecté / ou que l'on n'est pas un étudiant
	header('Location: index.php'); //On redirige vers la page de connexion
	exit;
}

$voteFile = $fichiersVote."vote-".$_SESSION['id'].".csv"; #On définit le format du fichier de vote

//Écriture dans le fichier de vote
if ( isset($_POST['ue1']) && isset($_POST['ue2']) && isset($_POST['ue3']) && isset($_POST['ue4']) && isset($_POST['ue5'])) { #On vérifie la validité du formulaire

	if (!empty($_POST['ue1']) AND !empty($_POST['ue2']) AND !empty($_POST['ue3']) AND !empty($_POST['ue4']) AND !empty($_POST['ue5'])) {
		
		for ($i=1;$i!=6;$i++) { #On regarde si les votes passés par POST ont le bon format
			if (!preg_match("#ue[1-5]-[1-5]#", $_POST['ue'.$i])) { #Si ce n'est pas le cas on déclanche une erreur
				$erreur = True;
				break;
			}
			else {
				$erreur = False;	
			}
		}
		
		if (!file_exists($voteFile) AND !$erreur) { //Si le fichier de vote n'existe pas

			$pointeur = fopen($voteFile, "w"); //On ouvre alors le fichier en écriture
			
			foreach($listeMatieres as $matieres => $ue) { //Pour chaque matières on écrit l'ue puis le vote associé
				$array = explode("-", $_POST[$ue]);
				$notation = array($ue, $array[1]);
				fputcsv($pointeur, $notation); 
	
			}
	
			fclose($pointeur); //On ferme le fichier
			
		}
		else {
			$erreur = True;
		}

	}
	else { //Si le formulaire est incorrect
		$erreur = True;
	}

}

//Vote de l'étudiant
if (!file_exists($voteFile)) { //Si le fichier de vote n'existe pas cela veut dire que l'étudiant n'a pas encore voté
?>
	<div class="jumbotron">

		<h3>Sélectionnez l'appréciation souhaitée pour chaque matière</h3>
		<?php if (isset($erreur)) { afficherErreur("Votre formulaire est incomplet ou incorrect !"); } ?>
		<form class="form-group" method="post">
		<table class='table table-striped'>
			<thead class='thead-dark'>
				<tr class="table-primary">
					<th scope="col">Matières</th>
					<th scope="col">Très mécontent</th>
					<th scope="col">Mécontent</th>
					<th scope="col">Moyen</th>
					<th scope="col">Satisfait</th>
					<th scope="col">Très satisfait</th>
				</tr>
			</thead>
<?php  

	foreach($listeMatieres as $matieres => $ue) { //Pour chaque matières différentes on affiche un formulaire différent
?>
				<tr>
				<td><h5 class="display-6"><?php echo $matieres ?></h5></td>
				<label class="radio-inline">
				<?php 
					foreach($notes as $note => $description) { //Pour chaque type de vote on ajoute un radio button 
						echo '<td><input type="radio" name="'.$ue.'" value="'.$ue.'-'.$note.'" id="'.$ue.'-'.$note.'" required /> <label for="'.$ue.'-'.$note.'"></label></td>';
					}
				?>
				</label>
				</tr>	 
<?php
	}
?>
		</table>
		

				<button type="submit" class="btn btn-primary">Voter</button>
				<a href="logout.php" class="btn btn-danger" role="button">Se déconnecter</a>

		</form>
		
	</div>	 

	<?php
   

}
else { #Sinon si l'étudiant a déjà voté on affiche les résultats de son vote

	?>
	
	<div class="jumbotron">
		<h1 class="display-4">
			Votre vote - Etudiant <?php echo $_SESSION['id']; ?>
		</h1><br>
		
		<table class='table table-striped text-center' style="margin:0 auto;width:50%" >
			<thead>
				<tr>
					<th class="table-dark" scope="col">Matières</th>
					<th	 class="table-dark" scope="col">Notes</th>
				</tr>
			</thead>
<?php

	if (file_exists($voteFile)) { #On vérifie si le fichier existe bien
	
		$erreur = False ;
		$listeue = array("ue1","ue2","ue3","ue4","ue5");
		$pointeur = fopen($voteFile, "r"); #On l'ouvre en lecture
		
		while ( ($data = fgetcsv($pointeur)) !== False) { #On affiche toutes les données du fichier
		
			if (in_array($data[0],$listeue) && !empty($data[1]) && $data[1]<=5 && $data[1]>=1) { //On vérifie si le contenu du fichier est correct
				echo "<tr><td>".$listeUE[$data[0]]."</td><td>".$data[1]."/5</td></tr>";
			}
			else {
				$erreur = True;
			}
		}
		
		fclose($pointeur); //On ferme le fichier
		
		if ($erreur) { //Si il y a une erreur dans le fichier
			unlink($voteFile); //On le supprimer
			header('Location: vote.php'); //Et on actualise la page actuelle
			exit;
		}
		else { //Sinon on affiche le reste de la page
			?></table><br><br>
				<form class="form-group" action="logout.php" method="post">
					<button type="submit" class="btn btn-danger" style="margin:20px;">Se déconnecter</button>
				</form>
			</div><?php
		}
	}
	
	
	

}//else

require_once($fichiersInclude.'footer.php');

?>