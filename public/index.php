<html>
<head>
    <title>Gestion de l'IIA</title>
    <link rel="stylesheet" href="affiche.css">
</head>
<body>
<h1>Liste des promotions</h1>
<form method="post" >
    <?php
    echo '<a href="ajout_db.php">Ajout élève</a><br>';
    echo '<a href="delete_db.php">Supprimer Formation / élève</a><br>';
    require '../database.php';
    ?>
    <input type="submit" value="Filtrer">
</form>
<?php
require '../affiche_nom.php'
?>
</body>
</html>