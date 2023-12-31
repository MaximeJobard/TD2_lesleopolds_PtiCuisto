<?php

    /**
     * Fonction permettant de rechercher une recette dans la bas de données à partie de son identifiant.
     * $num : l'identifiant de la rcette
     * Retourne un tableau contenant les caractéristiques de la recette.
     */
    function trouver_recette($num){
        require("connexion.php");

        $reponse = $bdd->query("SELECT rec_id, titre, rec_resume, rec_image, cat_intitule, contenu from RECETTE join CATEGORIE using(categorie_id) where rec_id = " . $num . ";");

        $retour = array();

        while ($donnees = $reponse->fetch()){ 
            $retour['titre'] = $donnees['titre'];
            $retour['rec_resume'] = $donnees['rec_resume'];
            $retour['rec_image'] = $donnees['rec_image'];
            $retour['categorie'] = $donnees['cat_intitule'];
            $retour['contenu'] = $donnees['contenu'];
            $retour['rec_id'] = $donnees['rec_id'];
        }

        return $retour;

    }

    /**
     * Fonction listant les ingrédients d'une recette avec leur quantité et leur description.
     * $id : l'identifiant de la recette.
     * Retourne un tableau avec chaque ingrédient et ses propriétés.
     */
    function lister_ingredients($id){
        require("connexion.php");
        $reponse = $bdd->query("SELECT quantite, ING_INTITULE as intitule, ING_DESCRIPTION as descritpion FROM CONTENIR join INGREDIENT using(INGREDIENT_ID) where REC_ID = ". $id ."; ");

        $i = 0;
        $retour = array();

        while ($donnees = $reponse->fetch()){ 
            $retour[$i]['quantite'] = $donnees['quantite'];
            $retour[$i]['intitule'] = $donnees['intitule'];
            $retour[$i]['description'] = $donnees['descritpion'];
        }
        return $retour;
    }

    /**
     * Fonction retournant toutes les recettes avec les propriétés : titre, résumé, image et intitulé de la catégorie
     * Le retour se fait sous la forme d'un tableau à deux dimensions : la première pour choisir la recette, et la deuxième pourchoisir la propriété (titre, rec_resume, 
     * rec_image et categorie)
     * Les recettes sont organisées par date de modifications 
     */
    function liste(){
        require("connexion.php");
        $reponse = $bdd->query('SELECT titre, rec_resume, rec_image, cat_intitule as categorie, rec_id from RECETTE join CATEGORIE using(categorie_id) where rec_validation = 1 ORDER BY date_modification;');

        $retour = array();
        $i = 0;

        while ($donnees = $reponse->fetch()){ 
            $retour[$i]['titre'] = $donnees['titre'];
            $retour[$i]['rec_resume'] = $donnees['rec_resume'];
            $retour[$i]['rec_image'] = $donnees['rec_image'];
            $retour[$i]['categorie'] = $donnees['categorie'];
            $retour[$i]['rec_id'] = $donnees['rec_id'];
            $i++;
        }

        return $retour;
    }

    /**
     * Fonction pour rechercher dans la base de données, toutes les recettes sans restrictions.
     * Retourne un tableau avec toutes les recettes et leur propriétés.
     */
    function liste_totale(){
        require("connexion.php");
        $reponse = $bdd->query('SELECT titre, rec_resume, rec_image, cat_intitule as categorie, rec_id from RECETTE join CATEGORIE using(categorie_id) ORDER BY date_modification;');

        $retour = array();
        $i = 0;

        while ($donnees = $reponse->fetch()){ 
            $retour[$i]['titre'] = $donnees['titre'];
            $retour[$i]['rec_resume'] = $donnees['rec_resume'];
            $retour[$i]['rec_image'] = $donnees['rec_image'];
            $retour[$i]['categorie'] = $donnees['categorie'];
            $retour[$i]['rec_id'] = $donnees['rec_id'];
            $i++;
        }

        return $retour;
    }

    /**
     * Fonction retournant toutes les recettes qui n'ont pas encore été validé par un administrateur
     * Avec les propriétés : titre, résumé, image et intitulé de la catégorie
     * Le retour se fait sous la forme d'un tableau à deux dimensions : la première pour choisir la recette, et la deuxième pourchoisir la propriété (titre, rec_resume, 
     * rec_image et categorie)
     * Les recettes sont organisées par date de modifications 
     */
    function liste_validation() {
        require("connexion.php");
        $reponse = $bdd->query('SELECT titre, rec_resume, rec_image, cat_intitule as categorie, rec_id from RECETTE join CATEGORIE using(categorie_id) where rec_validation = 0 ORDER BY date_modification;');

        $retour = array();
        $i = 0;

        while ($donnees = $reponse->fetch()){ 
            $retour[$i]['titre'] = $donnees['titre'];
            $retour[$i]['rec_resume'] = $donnees['rec_resume'];
            $retour[$i]['rec_image'] = $donnees['rec_image'];
            $retour[$i]['categorie'] = $donnees['categorie'];
            $retour[$i]['rec_id'] = $donnees['rec_id'];
            $i++;
        }

        return $retour;
    }

    /**
     * Fonction pour valider une recette donnée.
     * $num : l'identifiant de la recette à valider.
     */
    function valider($num){
        require("connexion.php");
        $req = $bdd->prepare('UPDATE RECETTE set rec_validation = 1 where rec_id='. $num .';');
        $req->execute();
    }

    /**
     * Fonction pour interdire une recette donnée.
     * $num : l'identifiant de la recette à interdire.
     */
    function interdire($num){
        require("connexion.php");
        $req = $bdd->prepare('UPDATE RECETTE set rec_validation = -1 where rec_id='. $num .';');
        $req->execute();
    }

    /**
     * Fonction pour rechercher toutes les recettes d'un utilisateur donné.
     * $id : l'identifiant de l'utilisateur
     * Retourne un tableau contenant les recettes de l'utilisateur.
     */
   function liste_proprietaire($id){
        require("connexion.php");
        $reponse = $bdd->query('SELECT titre, rec_resume, rec_image, cat_intitule as categorie, rec_id from RECETTE join CATEGORIE using(categorie_id) where uti_id = ' . $id . ' ORDER BY date_modification;');

        $retour = array();
        $i = 0;

        while ($donnees = $reponse->fetch()){ 
            $retour[$i]['titre'] = $donnees['titre'];
            $retour[$i]['rec_resume'] = $donnees['rec_resume'];
            $retour[$i]['rec_image'] = $donnees['rec_image'];
            $retour[$i]['categorie'] = $donnees['categorie'];
            $retour[$i]['rec_id'] = $donnees['rec_id'];
            $i++;
        }

        return $retour;
   }

   /**
    * Fonction pour supprimer une recette.
    * $recID : l'identifiant de la recette a supprimer.
    */
   function suppression(int $recID){
    include_once "connexion.php";
    if(isset($recID)){
        $req3 = $bdd->prepare('DELETE FROM `CONTENIR` WHERE REC_ID=?'); //supprime les lignes dans contenir qui lie des ingrédients à la recette
        $req3->execute(array($recID)); 
        $req4 = $bdd->prepare('DELETE FROM `RECETTE` WHERE REC_ID=?');//supprime la recette
        $req4->execute(array($recID)); 
        $req5 = $bdd->prepare('DELETE FROM `INGREDIENT` WHERE INGREDIENT_ID NOT IN ( SELECT INGREDIENT_ID FROM `CONTENIR`)');//supprime les ingrédients qui ne sont plus lier à aucune recette
        $req5->execute(array()); 
    }
}
?>