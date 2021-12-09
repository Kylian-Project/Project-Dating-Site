<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme/clair.css">
    <link rel="icon" href="img/logo.ico"/> <!--Icon onglet page-->
    <title>MeetIN'Baumont</title>
</head>
<body>
    <?php
        require('config.php');
        session_start();

        #page de connexion
        if (isset($_POST['pseudo'])){
            $pseudo = stripslashes($_REQUEST['pseudo']);
            $pseudo = mysqli_real_escape_string($conn, $pseudo);
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($conn, $password);
            $query = "SELECT * FROM `comptes` WHERE pseudo='$pseudo' and password='".hash('sha256', $password)."'";
            $result = mysqli_query($conn,$query) or die(mysql_error());
            $rows = mysqli_num_rows($result);
            if($rows==1){
                $_SESSION['pseudo'] = $pseudo;
                header("Location: index.php");
            }
            else
            {
                $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
            }
        }
    ?>
    <!--page de connexion-->
    <div id="t1">
        <ul id="menu2">
            <a href="auth.php"><img src="img/maison.png" class="selection" alt="Accueil" id="hover1"/></a><br>
        </ul>
        <div class="page" id="p1">
            <section class="center"><form class="connexion" method="post">
                <h1 class="titre2">MeetIN'Baumont ❤</h1>
                <input class="entre" type="text" name="pseudo" autofocus placeholder="Pseudo" /> <!--Demande de pseudo-->
                <input class="entre" type="password" name="password" placeholder="Mot de passe" /> <!--Demande de MDP-->
                <button class="entre envoi" type="submit">Connexion</button> <!--Bouton pour se connecter-->
                <a class="register" href="register.php">S'enregister</a> <!--Bouton qui renvoie à la page d'enregistrement de profil-->
                <?php if (! empty($message)) { ?>
                    <p class="erreur"><?php echo $message; ?></p> <!--en cas d'erreur-->
                <?php } ?>
            </form></section>
        </div>
    </div>
    <footer>
        Kylian & Gabin TG3-TG1 Copyright © <!--les meilleurs-->
    </footer>
</body>
</html>