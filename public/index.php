<html>
<head>
    <title>Gestion de l'IIA</title>
</head>
<body>
<h1>Liste des promotions</h1>
<?php
define('USER',"root");
define('PASSWD',"");
define('SERVER',"localhost");
define('BASE',"iia");
$dsn="mysql:dbname=".BASE.";host=".SERVER;
try{
    $connexion=new PDO($dsn,USER,PASSWD);
}
catch(PDOException $e){
    printf("Échec de la connexion : %s\n", $e->getMessage());
    exit();
}
$sql="SELECT * from promotion ORDER BY nom DESC";
if(!$connexion->query($sql)) echo "Pb d'accès au promotion";
else{
    foreach ($connexion->query($sql) as $row)
        echo $row['nom']."<br>";
}

?>
</body>
</html>