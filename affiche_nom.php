<html>
<head>
    <link rel="stylesheet" href="public/affiche.css">
</head>

<?php
$dsn = "mysql:dbname=" . BASE . ";host=" . SERVER;
$connexion = new PDO($dsn, USER, PASSWD);
$sql = "SELECT nom, prenom FROM etudiants";
$stmt = $connexion->prepare($sql);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    echo "<h2>Liste des étudiants</h2>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['nom'] . " " . $row['prenom'] . "<br>";
    }


} if (isset($_POST['promotion'])) {
    $promotion = $_POST['promotion'];
    $sql = "SELECT e.nom, f.nom_formation, e.prenom
            FROM etudiants e
            INNER JOIN formations f ON e.id_formation = f.id_formation
            WHERE f.nom_formation = :promotion
            ORDER BY f.id_formation ASC";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':promotion', $promotion);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<h2>Liste des étudiants pour la formation : $promotion</h2>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['nom'] . " " . $row['prenom'] . "<br>";
        }
    }
}
?>
</html>