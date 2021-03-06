<?php

require_once 'Vue/Vue.php';
require_once 'Controleur/ControleurUser.php';   
require_once 'Modele/File.php';

class ControleurSearch extends ControleurUser
{
  private $file;
  public function __construct() 
  { 
    //test de connexion, récupérer login
    parent::__construct();  
    $this->file = new file($this->login);   
  } 

  public function Recherche() 
  { 
    if(isset($_POST['submitSearch']))
    {
      //recup mots clés
      if(!empty($_POST['q']))
      {
        $motsCles=trim(strip_tags($_POST['q'])); //sanitize
        if($motsCles!="")
        {
          $motsCles = explode(' ',$motsCles); //retourne tableau mot clés
          $nombreMots=count($motsCles);
          for ($i =0; $i<$nombreMots; $i++) //nettoyage tableau
          {
            if (!strlen($motsCles[$i])) 
            {
              unset($motsCles[$i]);
            } 
            else 
            {
              $motsCles[$i] = addslashes(strip_tags(urlencode($motsCles[$i])));
            }
          }
          $nombreMots=count($motsCles);
         
          //recup choix tag ou titre         
          if($_POST['choix']==='Par tag')
          {
            //recup fichier, vote, tag
            $listeFile=$this->file->getFileByTag([$nombreMots,$motsCles]);
            foreach ($listeFile as $key => $value) 
            {
              $vote=new vote($this->login,$value['ID']);
              $nbVoteUp=$vote->getNbVote(1);
              $nbVoteDown=$vote->getNbVote(-1);
              $listeFile[$key]['voteUp']=$nbVoteUp;
              $listeFile[$key]['voteDown']=$nbVoteDown;
              $tag= new tag($value['ID'],$this->login);
              $tags=$tag->getTagImage();
              $listeFile[$key]['tag']=$tags;
            }
          }
          else
          {
            //recup fichier,vote,tag
            $listeFile=$this->file->getFile(null,[$nombreMots,$motsCles],null,null);
            foreach ($listeFile as $key => $value) 
            {
              $vote=new vote($this->login,$value['ID']);
              $nbVoteUp=$vote->getNbVote(1);
              $nbVoteDown=$vote->getNbVote(-1);
              $listeFile[$key]['voteUp']=$nbVoteUp;
              $listeFile[$key]['voteDown']=$nbVoteDown;
              $tag= new tag($value['ID'],$this->login);
              $tags=$tag->getTagImage();
              $listeFile[$key]['tag']=$tags;
             }
          }
          //génération vue
          $vue = new Vue("Search",$this->login);
          if(count($listeFile)==0)
          {
            $erreur='Aucun résultat pour la recherche';
            $vue->generer(array('listeFile' => $listeFile,'erreur' => $erreur));
          }
          else
          {
            $vue->generer(array('listeFile' => $listeFile,'login'=>$this->login));
          }
        }
      }   
      else
      {
        $erreur=("veuillez spécifiez au moins un mot clé");
        $vue = new Vue("Search",$this->login);
        $vue->generer(array('erreur' => $erreur));
      }   
    }
  } 
}