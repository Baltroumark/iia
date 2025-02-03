<html>
<head>
    <title>Gestion de l'IIA</title>
</head>
<body>
<h1>Liste des promotions</h1>

<!-- Formulaire pour sélectionner une formation -->
<form method="GET" action="">
    <label for="formation">Sélectionner une formation :</label>
    <select name="formation" id="formation">
        <?php
        // Connexion à la base de données
        define('USER', "root");
        define('PASSWD', "");
        define('SERVER', "localhost");
        define('BASE', "iia");
        $dsn = "mysql:dbname=" . BASE . ";host=" . SERVER;

        try {
            $connexion = new PDO($dsn, USER, PASSWD);
        } catch (PDOException $e) {
            printf("Échec de la connexion : %s\n", $e->getMessage());
            exit();
        }

        // Récupération des formations
        $sql = "SELECT DISTINCT formation FROM promotion ORDER BY formation";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            foreach ($resultat as $row) {
                echo "<option value='" . $row['formation'] . "'>" . $row['formation'] . "</option>";
            }
        } else {
            echo "<option>Aucune formation trouvée</option>";
        }
        ?>
    </select>
    <input type="submit" value="Filtrer">
</form>

<?php
// Vérification si une formation a été sélectionnée
if (isset($_GET['formation'])) {
    $formation = $_GET['formation'];

    // Récupération des promotions correspondant à la formation sélectionnée
    $sql = "SELECT * FROM promotion WHERE formation = :formation ORDER BY nom DESC";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':formation', $formation, PDO::PARAM_STR);
    $stmt->execute();

    // Affichage des résultats
    if ($stmt->rowCount() > 0) {
        echo "<h2>Promotions pour la formation : $formation</h2>";
        foreach ($stmt as $row) {
            echo $row['nom'] . "<br>";
        }
    } else {
        echo "Aucune promotion trouvée pour cette formation.";
    }
}
?>

</body>
</html>
