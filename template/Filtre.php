<?php
    require_once 'connexion.php';
    session_start();

    if (!isset($_SESSION['pseudo'])) {
        $_SESSION['pseudo'] = '';
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <script src="../js/app.js" defer></script>
    <title>PtiCuisto</title>
</head>
<body>
    <header>
        <img src="../img/Logo.png" alt="Logo">
        <nav>
            <div><a href="../index.php">Accueil</a></div>
            <div><a href="nosrecette.php">Nos Recettes</a></div>
            <div><a href="Filtre.php">Filtres</a></div>
            <div><a href="connexion.php">Connexion</a></div>
        </nav>
    </header>
    <main>
        <div class="filtre-container">
            <!-- Modale Catégories -->
            <div class="modal-container categorie-container">
                <div class="overlay categorie "></div>
                <div class="modal">
                    <button class="close-modal categorie ">X</button>
                    <h1>Catégories</h1>
                    <form action="" method="post">

                        <input type="checkbox" name="entree" id="entree">
                        <label for="entree">Entrée</label>
                        <br>
                        <input type="checkbox" name="plats" id="plats">
                        <label for="plats">Plats</label>
                        <br>
                        <input type="checkbox" name="dessert" id="dessert">
                        <label for="dessert">Dessert</label>
                        <br>
                        <input class="valider" value="Valider" type="submit" name="valider-categorie" id="valider-categorie">
                    </form>
                </div>
            </div>

            <button class="modal-btn categorie ">Catégories</button>

            <!-- Modale Titre -->
            <div class="modal-container titre-container">
                <div class="overlay titre "></div>
                <div class="modal">
                    <button class="close-modal titre ">X</button>
                    <h1>Titre de recette</h1>
                    <form action="" method="post">
                        <label for="titre">Titre de la recette : </label>
                        <input type="text" name="titre" id="titre">
                        <br>
                        <input class="valider" value="Valider" type="submit" name="valider-titre" id="valider-titre">
                    </form>
                </div>
            </div>

            <button class="modal-btn titre ">Titre</button>

            <!-- Modale Ingrédients -->
            <div class="modal-container ingredient-container">
                <div class="overlay ingredient "></div>
                <div class="modal">
                    <button class="close-modal ingredient">X</button>
                    <h1>Ingrédients</h1>
                    <form action="" method="post">

                        <input type="checkbox" name="tomate" id="tomate">
                        <label for="tomate">Tomate</label>
                        <br>
                        <input type="checkbox" name="concombre" id="concombre">
                        <label for="concombre">Concombre</label>
                        <br>
                        <input type="checkbox" name="champignon" id="champignon">
                        <label for="champignon">Champignon</label>
                        <br>
                        <input type="checkbox" name="porc" id="porc">
                        <label for="porc">Porc</label>
                        <br>
                        <input type="checkbox" name="poulet" id="poulet">
                        <label for="poulet">Poulet</label>
                        <br>
                        <input class="valider" value="Valider" type="submit" name="valider-ingredients" id="valider-ingredients">
                    </form>
                </div>
            </div>

            <button class="modal-btn ingredient">Ingrédients</button>

        </div>
        <?php
            // Initialisation des filtres
            $categoryFilter = ''; // Filtre de catégorie
            $ingredientFilter = ''; // Filtre d'ingrédients

            if (isset($_POST['valider-categorie'])) {
                // Traitement des catégories
                $categories = [];

                if (isset($_POST['entree'])) {
                    $categories[] = 1;
                }
                if (isset($_POST['plats'])) {
                    $categories[] = 2;
                }
                if (isset($_POST['dessert'])) {
                    $categories[] = 3;
                }

                if (!empty($categories)) {
                    $categoryFilter = 'CATEGORIE_ID IN (' . implode(', ', $categories) . ')';
                }
            }

            if (isset($_POST['valider-titre']) && !empty($_POST['titre'])) {
                $titreFilter = 'TITRE LIKE :titre';
                $titreValue = '%' . $_POST['titre'] . '%';
            }

            if (isset($_POST['valider-ingredients'])) {
                // Traitement des ingrédients
                $ingredients = [];

                if (isset($_POST['tomate'])) {
                    $ingredients[] = 'Tomate';
                }
                if (isset($_POST['concombre'])) {
                    $ingredients[] = 'Concombre';
                }
                if (isset($_POST['champignon'])) {
                    $ingredients[] = 'Champignon';
                }
                if (isset($_POST['porc'])) {
                    $ingredients[] = 'Porc';
                }
                if (isset($_POST['poulet'])) {
                    $ingredients[] = 'Poulet';
                }

                if (!empty($ingredients)) {
                    $ingredientFilter = 'ING_INTITULE LIKE "%' . implode('%" AND ING_INTITULE LIKE "%', $ingredients) . '%"';
                    $ingredientJoin = ' JOIN CONTENIR USING (REC_ID) JOIN INGREDIENT USING (INGREDIENT_ID)';
                }
            }

            // Construction de la requête SQL
            $sql = 'SELECT * FROM RECETTE';

            if (!empty($categoryFilter) || !empty($ingredientFilter) || !empty($titreFilter)) {
                if (!empty($ingredientFilter)) {
                    $sql .= $ingredientJoin;
                }
                $sql .= ' WHERE ';

                if (!empty($categoryFilter)) {
                    $sql .= $categoryFilter;
                }

                if (!empty($categoryFilter) && !empty($ingredientFilter)) {
                    $sql .= ' AND ';
                }

                if (!empty($ingredientFilter)) {
                    $sql .= $ingredientFilter;
                }

                if ((!empty($categoryFilter) || !empty($ingredientFilter)) && !empty($titreFilter)) {
                    $sql .= ' AND ';
                }

                if (!empty($titreFilter)) {
                    $sql .= $titreFilter;
                }
            }

            $query = $bdd->prepare($sql);
            echo $sql;
            if (isset($titreFilter)) {
                $query->bindValue(':titre', $titreValue, PDO::PARAM_STR);
            }
            $query->execute();

            while ($donnees = $query->fetch()) {
                echo "ID : " . $donnees['REC_ID'] . "<br>";
                echo "Titre : " . $donnees['TITRE'] . "<br>";
            }
        ?>
    </main>
    <footer></footer>
</body>
</html>
