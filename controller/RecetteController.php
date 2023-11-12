<?php
    require_once('manager/RecetteManager.php'); //Récupération du modèle

    /**
     * Fonction affichant les caractéristiques d'une recette ainsi que ses ingrédients et leurs quantités.
     * $num : Le numéro de la recette à afficher.
     */
    function detailRecette($num){
        $recette = trouver_recette($num);
        echo '<h1>' . $recette['titre'] . '</h1>';
        echo '<img src=' . $recette['rec_image'] . 'alt="image de la recette">';
        echo '<p>' . $recette['categorie'] . '</p>';
        echo '<p>' . $recette['rec_resume'] . '</p>';

        $ingredients = lister_ingredients(10);

        echo '<table>';
        for($i = 0; $i < count($ingredients); $i++){ 
            echo '<tr> <td>' . $ingredients[$i]['quantite'] . ' g <td/><td>' . $ingredients[$i]['intitule'] . '<td/><td>' . $ingredients[$i]['description']  . '<td/><tr/>';
            echo '<br>';
        }
        echo '<table/>';

        echo '<p>' . $recette['contenu'] . '</p>';

    }
    
    /**
     * Fonction pour la page "Nos recettes"
     * le paramère $depart indique où on en est de la liste (utilisation du bouton "Plus" pour avancer de 10 en 10)
     */
    function afficher_liste($depart, $nb){
        $recettes = liste();
        echo '<table>';
        for($i = $depart; $i < $depart + $nb && $i < count($recettes); $i++){ 
            echo '<tr> <td> <a href="index.php?page=detailsRecette&recette=' . $recettes[$i]['rec_id'] . '">' . $recettes[$i]['titre'] . '</a><td/><td>' . $recettes[$i]['categorie'] . '<td/><td>' . $recettes[$i]['rec_resume']  . '<td/>' . '<td>' . '<a href="index.php?page=detailsRecette&recette=' . $recettes[$i]['rec_id'] . '"><img src=' . $recettes[$i]['rec_image'] . 'alt="image de la recette">' . '</img></a><td/><tr/>';
            echo '<br>';
        }
        echo '<table/>';
        echo '<a href="index.php?page=liste&depart=' . ($depart + 10) . '">Plus</a>';
        if($depart !=0){
            echo '<a href="index.php?page=liste&depart=' . ($depart - 10) . '">Moins</a>';
        }
    }

    /**
     * Fonction permettant d'afficher toutes les recettes en attente d'une validation (rec_validation = 0)
     * Deux boutons s'affichent : valider pour valider la recette (rec_validation = 1) et interdire pour bannir la recette (rec_validation = -1)
     */
    function afficher_recettes_validation(){

        if(isset($_GET['valider'])){
            valider($_GET['valider']);
        }

        if(isset($_GET['interdire'])){
            interdire($_GET['interdire']);
        }

        $recettes = liste_validation();
        echo '<table>';
        foreach($recettes as $recette){ 
            echo '<tr> <td>' . $recette['titre'] . '<td/><td>' . $recette['categorie'] . '<td/><td>' . $recette['rec_resume']  . '<td/>' . '<td>' . '<img src=' .$recette['rec_image'] . 'alt="image de la recette"> <td/><td> <a href="index.php?page=listeAdmin&valider=' . $recette['rec_id'] . '">Valider</a> <a href="index.php?page=listeAdmin&interdire=' . $recette['rec_id'] . '">Interdire</a> <td/><tr/>';
            echo '<br>';
        }
        echo '<table/>';
    }

    /**
     * Fonctions permettant d'afficher les trois dernières recette sur l'accueil.
     */
    function afficher_recettes_accueil(){
        $recettes = liste();
        echo 
        '<div>
        <img src="' . $recettes[1]['rec_image'] . '" alt="recette1">
        <p class="recette-acceuil">' . $recettes[1]['rec_resume']  . '</p>
        </div>
        <div>
            <img src="' . $recettes[2]['rec_image'] . '" alt="recette1">
            <p class="recette-acceuil">' . $recettes[2]['rec_resume']  . '</p>
        </div>
        <div>
            <img src="' . $recettes[3]['rec_image'] . '" alt="recette1">
            <p class="recette-acceuil">' . $recettes[3]['rec_resume']  . '</p>
        </div>';
    }

    /**
     * Fonctions permettant d'afficher toutes les recettes crées par l'utilisateurs.
     * Affiche également un bouton pour modifier la recette.
     */
    function afficher_mes_recettes(){
        if($_SESSION['type'] == '2'){
            echo '<a href = "index.php?page=listeAdmin">Valider et interdire des recettes></a>';
            echo '<br>';
            $recettes = liste_totale();
            echo '<table>';
            for($i = 0; $i < count($recettes); $i++){ 
                echo '<tr> <td> <a href="index.php?page=detailsRecette&recette=' . $recettes[$i]['rec_id'] . '">' . $recettes[$i]['titre'] . '</a><td/><td>' . $recettes[$i]['categorie'] . '<td/><td>' . $recettes[$i]['rec_resume']  . '<td/>' . '<td>' . '<a href="index.php?page=detailsRecette&recette=' . $recettes[$i]['rec_id'] . '"><img src=' . $recettes[$i]['rec_image'] . 'alt="image de la recette">' . '</img></a><td/><td> <a href="index.php?page=modifierRecette&recette=' . $recettes[$i]['rec_id'] . '">Modifier la recette</a><td/><td> <a href="index.php?page=mesrecettes&suppression=' . $recettes[$i]['rec_id'] . '">Supprimer la recette</a><td/><tr/>';
                echo '<br>';
            }
                echo '<table/>';
        }else{
            $recettes = liste_proprietaire($_SESSION['id']);
            echo '<table>';
            for($i = 0; $i < count($recettes); $i++){ 
                echo '<tr> <td> <a href="index.php?page=detailsRecette&recette=' . $recettes[$i]['rec_id'] . '">' . $recettes[$i]['titre'] . '</a><td/><td>' . $recettes[$i]['categorie'] . '<td/><td>' . $recettes[$i]['rec_resume']  . '<td/>' . '<td>' . '<a href="index.php?page=detailsRecette&recette=' . $recettes[$i]['rec_id'] . '"><img src=' . $recettes[$i]['rec_image'] . 'alt="image de la recette">' . '</img></a><td/><td> <a href="index.php?page=modifierRecette&recette=' . $recettes[$i]['rec_id'] . '">Modifier la recette</a><tr/>';
                echo '<br>';
            }
                echo '<table/>';
        }

        
        echo '<a href="index.php?page=ajouterRecette">Ajouter une recette</a>';
    }

    /**
     * Fonction permettant de supprimer une recette.
     * $recID : l'identifiant de la recette à supprimer
     * exemple appel de la fonction : supprimer(28);
     */
    function supprimer(int $recID){
        suppression($recID);
    }

    
?>