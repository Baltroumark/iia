<?php
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

$sql = "SELECT * from formations ORDER BY nom_formation DESC";
if (!$connexion->query($sql)) {
    echo "Pb d'accès aux promotions";
} else {
    echo "<select name='promotion'>";
    echo "<option value='0' " . (empty($_POST['promotion']) ? 'selected' : '') . ">Selectionner une promotion</option>";
    foreach ($connexion->query($sql) as $row) {
        $selected = (isset($_POST['promotion']) && $_POST['promotion'] == $row['nom_formation']) ? 'selected' : '';
        echo "<option value='" . $row['nom_formation'] . "' $selected>" . $row['nom_formation'] . "</option>";
    }
    echo "</select>";
}
?>