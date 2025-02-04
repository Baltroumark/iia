<html>
<head>
    <title>Ajout d'un élève</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Ajout d'un élève</h1>
<?php
require '../connect_db.php';
$dsn = "mysql:dbname=" . BASE . ";host=" . SERVER;
try {
    $connexion = new PDO($dsn, USER, PASSWD);
} catch (PDOException $e) {
    printf("Échec de la connexion : %s\n", $e->getMessage());
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom'], $_POST['prenom'])) {
    $nom = htmlspecialchars(preg_replace('/[^a-zA-ZáéíóúñÁÉÍÓÚÑ]/u', "", $_POST['nom']));
    $prenom = htmlspecialchars(preg_replace('/[^a-zA-ZáéíóúñÁÉÍÓÚÑ]/u', "", $_POST['prenom']));
    $promotion = htmlspecialchars($_POST['promotion']);

    if (!empty($nom) && !empty($prenom) && !empty($promotion)) {
        $sql = "SELECT id_formation FROM formations WHERE nom_formation = :promotion";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':promotion', $promotion, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && empty($_POST['promotion'])) {
            $insert_sql = "INSERT INTO etudiants (nom, prenom, id_formation) VALUES (:nom, :prenom, :id_formation)";
            $insert_stmt = $connexion->prepare($insert_sql);
            $insert_stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $insert_stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $insert_stmt->bindParam('0', $promotion, PDO::PARAM_STR);
        } elseif ($row) {
            $id_formation = $row['id_formation'];
            $insert_sql = "INSERT INTO etudiants (nom, prenom, id_formation) VALUES (:nom, :prenom, :id_formation)";
            $insert_stmt = $connexion->prepare($insert_sql);
            $insert_stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $insert_stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $insert_stmt->bindParam(':id_formation', $id_formation, PDO::PARAM_INT);

            if ($insert_stmt->execute()) {
                echo "L'élève a été ajouté avec succès!";
            } else {
                echo "Une erreur est survenue lors de l'ajout de l'élève.";
            }
        }
    } else {
        echo '<script language="Javascript">
        alert ("Saisissez un nom valide !" )
        </script>';
    }
}
?>
<form method="post">
    <a href="index.php">Liste des Promotions</a>
    <input type="text" id="nom" name="nom" placeholder="Nom de l'élève" required>
    <br>
    <input type="text" id="prenom" name="prenom" placeholder="Prénom de l'élève" required>
    <br>
    <select id="promotion" name="promotion">
        <option value="Sans formation">Sélectionnez la promotion</option>
        <?php
        $sql = "SELECT * from formations ORDER BY nom_formation DESC";
        foreach ($connexion->query($sql) as $row) {
            $selected = $_POST['promotion'] && $_POST['promotion'] == $row['nom_formation'] ? 'selected' : '';
            echo "<option value='" . $row['nom_formation'] . "' $selected>" . $row['nom_formation'] . "</option>";
        }
        ?>
    </select>
    <br>
    <input type="submit" value="Ajouter l'élève">
</form>
</body>
</html>