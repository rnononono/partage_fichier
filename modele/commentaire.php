<?php
require_once'modele/connexion_bdd.php';
class Commentaire extends connexion_bdd
{
	private $user;

	public function __construct($login)
	{
		$this->user=$login;
	}

  //supprimer les commentaires liés à une image
  public function supprimer($id)
  {
    try
    {
      $suppressionfile="DELETE FROM commentaire WHERE id_image=?";
      $param=[array(1,$id,PDO::PARAM_INT)];
      $supprimer=$this->executerRequete($suppressionfile,$param);
    }
    catch(Exception $e)
    {
      echo " Erreur ! ".$e->getMessage(); print_r($datas); die;
    }
  }

  //obtenir les commentaires liés à une imae
	public function getCommentaires($id_file)
	{
		try
    {
      $commentaires = "SELECT contenu,date_ajout, pseudo FROM commentaire INNER JOIN user AS user ON user.ID=id_user WHERE id_image=? ORDER BY date_ajout DESC";
      $param=[array(1,$id_file,PDO::PARAM_INT)];
      $listCommentaires=$this->executerRequete($commentaires,$param);
    }
    catch(Exception $e)
    {
      echo " Erreur ! ".$e->getMessage(); print_r($datas); die;
    }
    $result=$listCommentaires->fetchAll();     
    return $result;  
	}

  //ajouter un commentaire à une image
	public function setCommentaire($contenu,$date_ajout,$id_file)
	{
		try
    {
      $ajoutCommentaire ="INSERT INTO commentaire(contenu, date_ajout,id_image, id_user ) VALUES(?, ?, ?, (SELECT ID FROM user WHERE pseudo=?)) "; 
      $param=[array(1,$contenu,PDO::PARAM_STR),array(2,$date_ajout,PDO::PARAM_STR),array(3,$id_file,PDO::PARAM_STR),array(4,$this->user,PDO::PARAM_STR)];
      $ajoutCommentaire=$this->executerRequete($ajoutCommentaire,$param);
    }
    catch(Exception $e)
    {
      echo " Erreur ! ".$e->getMessage(); print_r($datas); die;
    }
	}
}