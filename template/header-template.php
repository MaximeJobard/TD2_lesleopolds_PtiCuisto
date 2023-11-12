<header>
    <a href="../index.php"><img src="../img/Logo.png" alt="Logo"></a>
    <nav>
        <div id="menuPC">
            <div><a href="../index.php">Accueil</a></div>
            <div><a href="../index.php?page=liste">Nos Recettes</a></div>
            <div><a href="Filtre.php">Filtres</a></div>
            <?php
            if($_SESSION["type"] === 0){
                echo"<div><a href=\"page-connexion.php\">Connexion</a></div>";
            }else{
                echo "<div><a href=\"../controller/traitement_deconnexion.php\">Déconnexion</a></div>";
                if($_SESSION["type"] == '1' || $_SESSION["type"] == '2'){
                    echo "<div><a href=\"../index.php?page=mesrecettes\">Mes recettes</a></div>";
                }
            }
            ?>
        </div>
        <div id="burger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </nav>
    <script src="js/burger.js" defer></script>
</header>
<div id="menuPortable">
    <div><a href="../index.php">Accueil</a></div>
    <div><a href="nosRecettes.php">Nos Recettes</a></div>
    <div><a href="Filtre.php">Filtres</a></div>
    <?php
    if($_SESSION["type"] === 0){
        echo"<div><a href=\"page-connexion.php\">Connexion</a></div>";
    }else{
        echo "<div><a href=\"../controller/traitement_deconnexion.php\">Déconnexion</a></div>";
        if($_SESSION["type"] == '1'){
            echo "<div><a href=\"../index.php?page=mesrecettes\">Mes recettes</a></div>";
        }
    }
    ?>
</div>