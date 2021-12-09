<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.ico"/> <!--Icon onglet page-->
    <title>MeetIN'Baumont</title>
</head>
<body>
    <!--Page apres enregistrement si le compte est valide-->
    <?php
        header ("Refresh: 3;URL=auth.php");
        echo "<div class='inscrit'>
            <h3>Vous etes inscrit avec succes.</h3>
            <h3>Vous allez etre automatiquement redirige</h3>
            <p>Ou cliquez <a href='auth.php'>ici</a> pour vous connecter</p>
            </div>";
    ?>
</body>
</html>