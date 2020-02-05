<?php

require_once 'Vue/Vue.php';
require_once 'Controleur/ControleurUser.php';  
require_once 'Modele/file.php';
require_once 'Modele/commentaire.php';  

class ControleurCommentaire extends ControleurUser
{
	private $commentaire;
	private $date_ajout;
	private $contenu;
	private $id_user;
	private $id_image;
	private $id;


	public function __construct(){
		parent::__construct();
		$this->commentaire = new Commentaire($this->login);
        $this->date_ajout = new DateTime("NOW");
	}
	
	function commenter(){
		

		if(isset($_POST['submit'])){
				if(isset($_GET['id']))
				{
					$this->id_image = $_GET['id'];
					$header='Location: index.php?action=affiche_file&id='.$this->id_image;
				}
				else if(isset($_GET['lien']))
				{
					$this->lien_img = $_GET['lien'];
					$header='Location: index.php?action=affiche_file&lien='.$this->lien_img;
				}
				
			
			if(strlen(trim($_POST['commentaire'])) === 0){

				$erreur = 'vous devez remplir ce champ';
				header($header);
			}


			elseif(!isset($erreur)){

				$commentaire = htmlspecialchars(urldecode($_POST['commentaire']));

				$this->contenu = $commentaire;
				$this->commentaire->setCommentaire($this->contenu,$this->date_ajout->format('Y-m-d H:i:s'), $this->lien_img);

				header($header);

				
			}

		$vue = new Vue("Image", $this->login);
        $vue->generer(array('erreur' => $erreur,'login'=>$this->login ));


		}
	}
}