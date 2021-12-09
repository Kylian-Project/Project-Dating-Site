<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
    if(!isset($_SESSION["pseudo"])){
        header("Location: ../auth.php");
        exit();
    }
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/stylep1.css">
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" href="../img/logo.ico"/> <!--Icon onglet page-->
	<title>MeetIN'Baumont</title>
</head>
<body>
    <?php
        require('../config.php');

        $pseudomtn = $_SESSION["pseudo"];

        $recupid = "SELECT id FROM `comptes` WHERE pseudo='$pseudomtn'";
        $resultid = mysqli_query($conn,$recupid);
        $finalid = mysqli_fetch_array($resultid);
        $recupid = $finalid["id"];

        $info = "SELECT * FROM `profils` WHERE id_comptes='$recupid'";
        $resultinfo = mysqli_query($conn,$info);
        $finalinfo = mysqli_fetch_array($resultinfo);
        $recupnom = $finalinfo["nom"];
        $recupprenom = $finalinfo["prenom"];
        $recupage = $finalinfo["age"];
        $recupclasse = $finalinfo["classe"];
        $recupdesk = $finalinfo["desk"];
        $recupsexe = $finalinfo["sexe"];
        $recuprechsexe = $finalinfo["rech_sexe"];
        $recupsnap = $finalinfo["snap"];
        $recupinsta = $finalinfo["insta"];
        $recuptheme = $finalinfo["theme"];
        $recuplang = $finalinfo["langue"];

        // Définition du thème
        if($recuptheme=='1'){
            $finaltheme = 'sombre';
        }else{
            $finaltheme = 'clair';
        }

        // Partie traduction (relou)
        if($recuplang=='0'){
            // page 1
            $bvn = "Bienvenue";
        }elseif($recuplang=='1'){
            // page 1
            $bvn = "Welcome";
        }

        if($recupnom == "" or
            $recupprenom == "" or
            $recupage == "" or
            $recupclasse == "" or
            $recupsexe == "" or
            $recuprechsexe == "" or
            $recupdesk == ""
        ){
            $fromcomplet = 0;
            $changecomplet = "UPDATE profils
                            SET complet = '0'
                            WHERE id_comptes = $recupid";
        }else{
            $fromcomplet = 1;
            $changecomplet = "UPDATE profils
                            SET complet = '1'
                            WHERE id_comptes = $recupid";
        }
        // Exécute la requête sur la base de données
        $reschangecomplet = mysqli_query($conn, $changecomplet);
    ?>
    <link rel="stylesheet" href="../theme/<?php print $finaltheme ?>.css">
	<div id="t1">
  	<div id="t2">
    <div id="t3">
    <div id="t4">
    <div id="t5">
        <center><div id="fixe">
            <a href="index.php"><img src="../img/maison.png" class="menuimg" alt="Accueil" id="hover1"/></a>
            <a href="search.php"><img src="../img/recherche.png" class="menuimg" alt="Recherche" id="hover2"/></a>
            <!-- <a href="#t3"><img src="../img/fil.png" class="menuimg" alt="Fils actus" id="hover3"/></a> -->
            <a href="profil.php"><img src="../img/compte.png" class="menuimg" alt="Mon compte" id="hover4"/></a>
            <a href="para.php"><img src="../img/roue.png" class="menuimg" alt="Parametres" id="hover5"/></a>
            <a href="../deconnexion.php"><img src="../img/deco.png" class="menuimg" alt="Deconnexion" id="hover5"/></a>
        </div></center>
        <div class="page" id="p1">
            <?php
            if($recuptheme==1){ ?>
                <center><img class="image" src="../img/logo2.png"></center>
            <?php }else{ ?>
                <center><img class="image" src="../img/logo.png"></center>
            <?php } ?>
            <center><span class="titre"><?php print $bvn ?> <?php print $_SESSION["pseudo"] ?></span></center>
        </div>
    </div>
	</div>
	</div>
	</div>
	</div>
    <!-- <footer>
        Kylian & Gabin TG3-TG1 Copyright ©
    </footer> -->
    <div id="site-horizontal">
      <p>Ce site est fait pour être consulté en affichage vertical.</p>
      <p>Merci d'orienter votre telephone/tablette en mode portrait.</p>
    </div>
</body>
</html>