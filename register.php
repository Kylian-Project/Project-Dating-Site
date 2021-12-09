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
        if (isset($_REQUEST['pseudo'], $_REQUEST['password'], $_REQUEST['password2'])){
            // récupérer le nom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
            $pseudo = stripslashes($_REQUEST['pseudo']);
            $pseudo = mysqli_real_escape_string($conn, $pseudo); 
            // récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($conn, $password);
            $password2 = stripslashes($_REQUEST['password2']);
            $password2 = mysqli_real_escape_string($conn, $password2);
            $verif = mysqli_query($conn, "SELECT * FROM comptes WHERE pseudo = '".$_POST['pseudo']."'");
            if (mysqli_num_rows($verif)) {
                $message = "Ce pseudo existe déjà.";
            }else{
                if(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["pseudo"]))){
                    $message = "Le pseudo ne peut contenir que des lettres ou des chiffres.";
                }else{
                    if(strlen(trim($_POST["pseudo"])) < 3){
                            $message = "Le pseudo doit avoir au moins 3 caractères.";
                    }else{
                        if ($password != $password2){
                            $message = "Mot de passe non identique";
                        }else{
                            if ($password == $pseudo){
                                $message = "Le mot de passe ne doit pas être identique au pseudo.";
                            }else{
                                if(strlen(trim($_POST["password"])) < 8){
                                    $message = "Le mot de passe doit avoir au moins 8 caractères et 1 chiffre.";
                                }else{
                                    if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)){
                                        $message = "Le mot de passe doit contenir des lettres et des chiffres.";
                                    }else{
                                        //requete SQL + mot de passe crypté
                                        $query = "INSERT into `comptes` (pseudo, password)
                                        VALUES ('$pseudo', '".hash('sha256', $password)."')";
                                        // Exécute la requête sur la base de données
                                        $res = mysqli_query($conn, $query);
                                        if($res){
                                            // recup de l'id crée dans la base pour le nouvelle utilisateur
                                            $recupid = "SELECT id FROM `comptes` WHERE pseudo='$pseudo'";
                                            $resultid = mysqli_query($conn,$recupid);
                                            $finalid = mysqli_fetch_array($resultid);
                                            $recupid = $finalid["id"];
                                            // insertion de la même id dans la tables (profils)
                                            // ce qui permet une liaison entre clé primaire et clé étrangère pour l'id
                                            $queryid = "INSERT INTO `profils` (`id_comptes`, `nom`, `prenom`, `age`, `sexe`, `rech_sexe`, `classe`, `snap`, `insta`, `desk`, `theme`, `langue`, `complet`) VALUES ('$recupid', '', '', '', '', '', '', '', '', '', '', '', '')";
                                            // Exécute la requête sur la base de données
                                            $resid = mysqli_query($conn, $queryid);

                                            header("Location: inscrit.php");
                                        }else{
                                            $message = "Oups! Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    ?>
    <div id="t1">
        <ul id="menu2">
            <a href="auth.php"><img src="img/maison.png" class="selection" alt="Accueil" id="hover1"/></a><br>
        </ul>
        <!--Page pour la création de compte-->
        <div class="page" id="p1">
            <section class="center"><form autocomplete="off" class="connexion" method="post">
                <h1 class="titre2">Enregistrement</h1>
                <input class="entre" type="text" name="pseudo" autofocus placeholder="Pseudo" required/>
                <input class="entre" type="password" name="password" placeholder="Mot de passe" required/>
                <input class="entre" type="password" name="password2" placeholder="Confirmer mot de passe" required/>
                <button class="entre envoi" type="submit">Valider</button>
                <?php if(isset($message)) { ?>
                    <p class="erreur"><?php echo $message; ?></p>
                <?php } ?>
            </form></section>
        </div>
    </div>
    <footer>
        Kylian & Gabin TG3-TG1 Copyright ©
    </footer>
</body>
</html>