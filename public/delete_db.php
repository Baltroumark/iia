
<html>
<head>
    <link rel="stylesheet" href="indedx.css">
</head>
</html><?php
require '../connect_db.php';
echo '<a href="index.php">Liste des Promotions</a><br>';
try {
    $connexion = new PDO($dsn, USER, PASSWD);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission for deleting a student or formation
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if it's a student deletion request
        if (isset($_POST['delete_student'])) {
            $id_etudiant = $_POST['id_etudiant'];
            $sql = "DELETE FROM etudiants WHERE id_etudiant = :id_etudiant";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_etudiant', $id_etudiant, PDO::PARAM_INT);
            $stmt->execute();
            echo "<p>Student with ID $id_etudiant has been deleted.</p>";
        }

        // Check if it's a formation deletion request
        if (isset($_POST['delete_formation'])) {
            $id_formation = $_POST['id_formation'];
            $sql = "DELETE FROM formations WHERE id_formation = :id_formation";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_formation', $id_formation, PDO::PARAM_INT);
            $stmt->execute();
            echo "<p>Formation with ID $id_formation has been deleted.</p>";
        }
    }

    // Table of students
    $sql = "SELECT f.nom_formation, f.id_formation, e.nom, e.prenom, e.id_etudiant 
            FROM formations f 
            JOIN etudiants e ON f.id_formation = e.id_formation 
            ORDER BY f.id_formation ASC";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    echo "<table>";
    echo "<tr><th>Formation</th><th>Nom</th><th>Prénom</th><th>Action</th></tr>";
    foreach ($stmt as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nom_formation']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
        echo "<td>
                <form method='POST'>
                    <input type='hidden' name='id_etudiant' value='".htmlspecialchars($row['id_etudiant'])."'>
                    <input type='submit' name='delete_student' value='Supprimer élève'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";

    // Table of formations
    echo "<br><table>";
    echo "<tr><th>Formation</th><th>Action</th></tr>";
    $sql = "SELECT id_formation, nom_formation FROM formations";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    foreach ($stmt as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nom_formation']) . "</td>";
        echo "<td>
                <form method='POST'>
                    <input type='hidden' name='id_formation' value='".htmlspecialchars($row['id_formation'])."'>
                    <input type='submit' name='delete_formation' value='Supprimer Formation'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    printf("Échec de la connexion : %s\n", $e->getMessage());
    exit();
}
?>