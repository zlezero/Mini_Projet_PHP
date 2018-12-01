<?php 

    require_once("config.php");

    function afficherErreur($erreur) {
        ?>
        
        <div class="form-control-feedback alert alert-danger alert-dismissible fade show" role="alert">

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>

        <span class="text-danger align-middle">
        <i class="fa fa-close"></i><strong> Erreur :</strong> <?php echo $erreur; ?>
        </span>

        </div> 

        <?php
    }
    
    function afficherSucces($message) {
        ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            <span class="text-success align-middle">
                <i class="fa fa-close"></i><strong> Succès :</strong> <?php echo $message; ?>
            </span>

        <?php
    }

    function estConnecte() {
        
        $roles = array("admin", "etudiant", "professeur");
        if (isset($_SESSION['id']) AND isset($_SESSION['role'])) {
            
            if ( (!empty($_SESSION['id'])) AND in_array($_SESSION['role'], $roles) ) {
                return TRUE;
            }
        }
        return FALSE;
    }

    function redirigerBonnePage() {

        if (estConnecte()) {

            if ($_SESSION['role'] == "admin") { #Si il s'agit d'un admin
                header('Location: admin.php');
                exit;
            }
            else if ($_SESSION['role'] == "etudiant") { #Si il s'agit d'un étudiant
                header('Location: vote.php');
                exit;
            }
            else if ($_SESSION['role'] == "professeur") { #Sinon il s'agit d'un prof
                header('Location: prof.php');
                exit;
            }

        }
        else {
            header("Location: index.php");
            exit;
        }

    }
	
	
	function calculDonneesAdmin() {
		
		$fichiersVote = "votes/";
		$listeUE = array("ue1","ue2","ue3","ue4","ue5");
		
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
							
		
		
		// Parcours des fichiers de vote
		foreach (glob($fichiersVote."vote-e????.csv") as $filename) {
			$file = file($filename);
			$erreur = False ;
			$tabVoteEtu = array() ;
			
			//A chaque ligne on récupère l'ue et le vote correspondant
			for ($ligne = 0; $ligne < count($file); $ligne++) {
				
				if (count($file) == 5) {
					
					$ligneVote = explode(',', $file[$ligne]);
					
					$ue = $ligneVote[0] ;
					$vote = $ligneVote[1] ;
				
					//On vérifie la validité des notes
					if((intval($vote)<=5 && intval($vote)>=1) && in_array($ue,$listeUE)) {
						$tabVoteEtu[$ue] = $vote ;
					}		
					else { //Si le fichier est erronné, on le marque comme erroné
						$erreur = True ;
					}
				}
				else {
					$erreur = True;
				}
				
				if ($erreur) { //Si il y a une erreur avec le fichier actuel on le supprime (car admin)
					unlink($filename) ;
				}

			}
		
			if ($erreur == False) {
				foreach($tabVoteEtu as $ue => $vote) {
					//Ajout du vote au tableau des votes
					$tabVoteUE[$ue][intval($vote)-1] +=1  ;
				
					//Ajout au nombre de votes total et celui de l'ue
					$tabNbVotes["total"] +=1 ;
					$tabNbVotes[$ue] +=1 ;
				
					//Ajout à la moyenne
					$tabMoyennes[$ue] += intval($vote) ;	
				}
			}
		}

		//Calcul des moyennes
		foreach($tabMoyennes as $ue => $moyenne) {
			if ($tabNbVotes[$ue] > 0) { 
				$tabMoyennes[$ue] = $moyenne/$tabNbVotes[$ue] ;
			}
			
			else {
				$tabMoyennes[$ue] = 0 ;
			}
		}

		//Calcul des écarts-types
		foreach ($tabET as $ue => $val) {
			foreach ($tabVoteUE[$ue] as $vote => $nb) {
				$val += $nb*pow(($vote +1 - $tabMoyennes[$ue]),2) ;
			}
			
			if ($tabNbVotes[$ue] > 0) { 
				$val = $val/$tabNbVotes[$ue] ;
				$tabET[$ue] = sqrt($val) ;
			}
			
			else {
				$tabET[$ue] = 0 ;
			}

		}
		
		$resultat = array(
			"Votes" => $tabVoteUE,
			"Totaux" => $tabNbVotes,
			"Moyennes" => $tabMoyennes,
			"ET" => $tabET) ;
		
		return $resultat ;
	
	}
	
?>