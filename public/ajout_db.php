<html>
<head>
    <title>Ajout d'un élève</title>
</head>
<body>
<h1>Ajout d'un élève</h1>
<a href="index.php">Liste des Promotions</a>
<?php
require '../connect_db.php';
$dsn = "mysql:dbname=" . BASE . ";host=" . SERVER;
try {
    $connexion = new PDO($dsn, USER, PASSWD);
} catch (PDOException $e) {
    printf("Échec de la connexion : %s\n", $e->getMessage());
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Nom'], $_POST['Prénom'], $_POST['promotion'])) {
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prénom'];
    $promotion = $_POST['promotion'];

    $sql = "SELECT id_formation FROM formations WHERE nom_formation = :promotion";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':promotion', $promotion, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
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
    } else {
        echo "La promotion sélectionnée est invalide.";
    }
}
?>
<form method="post">
    <input type="text" id="Nom" name="Nom" placeholder="Nom de l'élève" required>
    <br>
    <input type="text" id="Prénom" name="Prénom" placeholder="Prénom de l'élève" required>
    <br>
    <select id="promotion" name="promotion" required>
        <option value="">Sélectionnez la promotion</option>
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
