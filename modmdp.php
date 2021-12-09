<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.ico"/> <!--Icon onglet page-->
    <title>MeetIN'Baumont</title>
</head>
<body>
    <!--Page apres changement de mot de passe-->
    <?php
        header ("Refresh: 3;URL=index.php#t5");
        echo "<div class='inscrit'>
            <h3>Votre mot de passe à été changé avec succès.</h3>
            <h3>Vous allez etre automatiquement redirige</h3>
            <p>Sinon cliquez <a href='index.php#t5'>ici</a></p>
            </div>";
    ?>
</body>
</html>