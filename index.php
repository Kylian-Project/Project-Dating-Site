<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
    if(!isset($_SESSION["pseudo"])){
        header("Location: auth.php");
        exit();
    }
    // Vérifie si l'utilisateur utilise un téléphone et le redirige sur un site aproprié si c'est le cas
    if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'Android') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))  {   header('Location: phone/index.php');   exit(); }
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.ico"/> <!--Icon onglet page-->
	<title>MeetIN'Baumont</title>
</head>
<body>
    <?php
        require('config.php');

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

        //notifications

        // $infonotif = "SELECT * FROM `notif` WHERE id_comptes='$recupid'";
        // $resultinfonotif = mysqli_query($conn,$infonotif);
        // $finalinfonotif = mysqli_fetch_array($resultinfonotif);
        // $recupnotif = $finalinfonotif["notif_idcompte"];

        // $list = array();
        // $list[] = $finalinfonotif["notif_idcompte"];
        // $list2 = implode(" ",$list);
        // $list3 = count($list);
        // $list4 = implode(" ",$list3);

        // echo $list2;
        // echo " / ".$list3;
        // if($recupnotif!=""){
        //     $infonotif2 = "SELECT * FROM `profils` WHERE id_comptes='$recupnotif'";
        //     $resultinfonotif2 = mysqli_query($conn,$infonotif2);
        //     $finalinfonotif2 = mysqli_fetch_array($resultinfonotif2);
        //     $recupnotifnom = $finalinfonotif2["nom"];
        //     $recupnotifprenom = $finalinfonotif2["prenom"];
        // }

        // Définition du thème
        if($recuptheme=='1'){
            $finaltheme = 'sombre'; // thème sombre
        }else{
            $finaltheme = 'clair'; // thème clair
        }

        if(isset($_POST['go']) AND $_POST['go']=='envoyer'){
            // Supprimer caractères non voulus
            $nommod = $_POST['nom'];
            $nommod2 = preg_replace('/[^A-Za-z0-9\-]\s\s+/', '', $nommod);
            $prenommod = $_POST['prenom'];
            $prenommod2 = preg_replace('/[^A-Za-z0-9\-]\s\s+/', '', $prenommod);
            $agemod = $_POST['age'];
            $classemod = $_POST['classe'];
            $classemod2 = preg_replace('/[^A-Za-z0-9\-]\s\s+/', '', $classemod);
            $snapmod = $_POST['snap'];
            $snapmod2 = preg_replace('/[^A-Za-z0-9\-]\s\s+/', '', $snapmod);
            $instamod = $_POST['insta'];
            $instamod2 = preg_replace('/[^A-Za-z0-9\-]\s\s+/', '', $instamod);
            $deskmod = $_POST['desk'];
            $deskmod3 = preg_replace("/[^a-z0-9_-]\s/i", "", $deskmod);
            $deskmod2 = str_replace(array("'", '"', ";"), '', $deskmod3);

            if(isset($_POST['sexechoix'])){
                $sexemod = $_POST['sexechoix'];
            }

            if(isset($_POST['rechsexechoix'])){
                $rechsexemod = $_POST['rechsexechoix'];
            }

            $querychange = "UPDATE profils
                            SET nom = '$nommod2',
                              prenom = '$prenommod2',
                              age = '$agemod',
                              sexe = '$sexemod',
                              rech_sexe = '$rechsexemod',
                              classe = '$classemod',
                              snap = '$snapmod2',
                              insta = '$instamod2',
                              desk = '$deskmod2'
                            WHERE id_comptes = $recupid";
            // Exécute la requête sur la base de données
            $reschange = mysqli_query($conn, $querychange);
            header("Location: index.php#t4");
        }

        if(isset($_POST['go2']) AND $_POST['go2']=='enrg'){
            if(isset($_POST['themechoix'])){
                $thememod = $_POST['themechoix'];
            }

            if(isset($_POST['langchoix'])){
                $langmod = $_POST['langchoix'];
            }

            if (!empty($_REQUEST['password']) AND !empty($_REQUEST['password2'])){
            $pseudo = $_SESSION["pseudo"];
            // récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($conn, $password);
            $password2 = stripslashes($_REQUEST['password2']);
            $password2 = mysqli_real_escape_string($conn, $password2);
                if ($password != $password2){
                    $message = "Mot de passe non identique";
                }else{
                    if ($password == $pseudo){
                        $message = "Le mot de passe ne doit pas être identique au pseudo.";
                    }else{
                        //requete SQL + mot de passe crypté
                        $querychange2 = "UPDATE comptes
                        SET password = '".hash('sha256', $password)."' WHERE id = $recupid";
                        // Exécute la requête sur la base de données
                        $reschange2 = mysqli_query($conn, $querychange2);
                        if($reschange2){
                            header("Location: modmdp.php");
                        }else{
                            $message = "Oups! Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
                        }
                    }
                }
            }

            $querychange3 = "UPDATE profils
                            SET theme = '$thememod',
                              langue = '$langmod'
                            WHERE id_comptes = $recupid";
            // Exécute la requête sur la base de données
            $reschange3 = mysqli_query($conn, $querychange3);
            if (empty($_REQUEST['password']) AND empty($_REQUEST['password2'])){
                header("Location: index.php#t5");
            }
        }

        $verif = "SELECT * FROM `profils` WHERE id_comptes!='$recupid' AND sexe='$recuprechsexe' AND rech_sexe='$recupsexe' AND complet='1'";
        $resultverif = mysqli_query($conn,$verif);
        $resultverif2 = mysqli_num_rows($resultverif);

        if($resultverif2>0){
            $affichecont = 1;
        }else{
            $affichecont = 0;
        }

        if($resultverif2>1){
            if(isset($_POST['go3']) AND $_POST['go3']=='next'){
                if($_SESSION["nbrenc"] < $resultverif2-1){
                    $_SESSION["nbrenc"]+=1;
                }
            }elseif(isset($_POST['go4']) AND $_POST['go4']=='prev'){
                if($_SESSION["nbrenc"] != 0){
                    $_SESSION["nbrenc"]-=1;
                }
            }else{
                $_SESSION["nbrenc"]=0;
            }
        }

        $x1 = $_SESSION["nbrenc"];

        if($resultverif2>0){
            $info2 = "SELECT * FROM `profils` WHERE id_comptes!='$recupid' AND sexe='$recuprechsexe' AND rech_sexe='$recupsexe' AND complet='1' ORDER BY id_comptes DESC LIMIT $x1,1";
            // plus le comptes est réscent plus on est en premiers
            $resultinfo2 = mysqli_query($conn,$info2);
            $finalinfo2 = mysqli_fetch_array($resultinfo2);
            $matchnom = $finalinfo2["nom"];
            $matchprenom = $finalinfo2["prenom"];
            $matchage = $finalinfo2["age"];
            $matchclasse = $finalinfo2["classe"];
            $matchsnap = $finalinfo2["snap"];
            $matchinsta = $finalinfo2["insta"];
            $matchdesk = $finalinfo2["desk"];
        }else{
            $matchnom = '';
            $matchprenom = '';
            $matchage = '';
            $matchclasse = '';
            $matchsnap = '';
            $matchinsta = '';
            $matchdesk = '';
        }

        // Partie traduction (relou)
        if($recuplang=='0'){
            // page 1
            $bvn = "Bienvenue";
            // page 3
            $news = "Infos";
            // paghe 4
            $tprof = "Informations personnelles";
            $tradnom = "Nom *";
            $tradprenom = "Prenom *";
            $tradage = "Age *";
            $tradclasse = "Classe *";
            $tradsnap = "Ton snap";
            $tradinsta = "Ton insta";
            $tradsexe = "Votre sexe *";
                $tradsexem = "Je suis un Homme";
                $tradsexef = "Je suis une Femme";
                $tradsexea = "Autre";
            $tradrecherchesx = "Par qui êtes-vous attiré ? *";
                $tradrecherchesxmasc = "Je recherche un homme";
                $tradrecherchesxfem = "Je recherche une femme";
            $traddesk = "Description *";
            $tradvalid = "Valider";
            // page 5
            $tradpara = "Paramètres";
            $tradmdp1 = "Nouveau MDP";
            $tradmdp2 = "Retaper MDP";
            $tradtheme = "Thème";
                $tradtheme1 = "Clair";
                $tradtheme2 = "Sombre";
            $tradlang = "Langue";
                $tradlang1 = "Français";
                $tradlang2 = "English";
            $tradsave = "Enregistrer";
            // page 2
            $tradrenc = "Rencontre N°";
            $tradbutsuiv = "Suivant";
            $tradbutnext = "Précédent";
            $tradnom2 = "Nom";
            $tradprenom2 = "Prenom";
            $tradage2 = "Age";
            $tradclasse2 = "Classe";
            $tradsnap2 = "Snap";
            $tradinsta2 = "Insta";
            $traddesk2 = "Description";
        }elseif($recuplang=='1'){
            // page 1
            $bvn = "Welcome";
            // page 3
            $news = "News";
            // page 4
            $tprof = "Personal information";
            $tradnom = "Name *";
            $tradprenom = "First name *";
            $tradage = "Years *";
            $tradclasse = "Class *";
            $tradsnap = "Your snap";
            $tradinsta = "Your insta";
            $tradsexe = "Your sexe *";
                $tradsexem = "I'm a Man";
                $tradsexef = "I'm a Woman";
                $tradsexea = "Other";
            $tradrecherchesx = "Who are you attracted to ? *";
                $tradrecherchesxmasc = "I want a man";
                $tradrecherchesxfem = "I want a woman";
            $traddesk = "Description *";
            $tradvalid = "Submit";
            // page 5
            $tradpara = "Settings";
            $tradmdp1 = "New PWD";
            $tradmdp2 = "Retype PWD";
            $tradtheme = "Theme";
                $tradtheme1 = "Light";
                $tradtheme2 = "Dark";
            $tradlang = "Language";
                $tradlang1 = "Français";
                $tradlang2 = "English";
            $tradsave = "Save";
            // page 2
            $tradrenc = "Meet N°";
            $tradbutsuiv = "Next";
            $tradbutnext = "Previous";
            $tradnom2 = "Name";
            $tradprenom2 = "First name";
            $tradage2 = "Years";
            $tradclasse2 = "Class";
            $tradsnap2 = "Snap";
            $tradinsta2 = "Insta";
            $traddesk2 = "Description";
        }

        //Si un élément du profil (sauf snap/insta) n'est pas complété, on ne met pas le profil en complet
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
        //Si toutes les conditions sont respectées, on valide le profil
        }else{
            $fromcomplet = 1;
            $changecomplet = "UPDATE profils
                            SET complet = '1'
                            WHERE id_comptes = $recupid";
        }
        // Exécute la requête sur la base de données
        $reschangecomplet = mysqli_query($conn, $changecomplet);
    ?>
    <link rel="stylesheet" href="theme/<?php print $finaltheme ?>.css">
	<div id="t1">
  	<div id="t2">
    <div id="t3">
    <div id="t4">
    <div id="t5">
        <!--Menu des icones à gauche-->
        <ul id="menu">
            <a href="#t1"><img src="img/maison.png" class="selection" alt="Accueil" id="hover1"/></a><br>
            <a href="#t2"><img src="img/recherche.png" class="selection" alt="Recherche" id="hover2"/></a><br>
            <a href="#t3"><img src="img/fil.png" class="selection" alt="Fils actus" id="hover3"/></a><br>
            <a href="#t4"><img src="img/compte.png" class="selection" alt="Mon compte" id="hover4"/></a><br>
            <a href="#t5"><img src="img/roue.png" class="selection" alt="Parametres" id="hover5"/></a><br>
            <a href="deconnexion.php"><img src="img/deco.png" class="selection" alt="Deconnexion" id="hover5"/></a>
        </ul>
        <div class="page" id="p1">
            <?php
            //Changement du theme
            if($recuptheme==1){ ?>
                <center><img class="image" src="img/logo2.png"></center>
            <?php }else{ ?>
                <center><img class="image" src="img/logo.png"></center>
            <?php } ?>
            <center><span class="titre"><?php print $bvn ?> <?php print $_SESSION["pseudo"] ?></span></center>
            <?php // if($recupnotif!=""){ ?>
<!--                 <div class="contexte">
                   <section class="notif">
                        <img class="logo2notif" src="img/logo-notif.png">
                        <h6 class="notif-titre"><?php print $recupnotifprenom ?> <?php print $recupnotifnom ?></h6>
                        <p>Souhaite accéder à vos réseaux sociaux</p>
                        <div class="notif-complet">
                            <a href="index.php"><img class="logocheck" title="Accepter" alt="Accepter" src="img/valid1.png"></a><br>
                            <a href="index.php"><img class="logocheck" title="Refuser" alt="Refuser" src="img/valid2.png"></a>
                        </div>
                    </section>
                </div> -->
            <?php //} ?>
        </div>
        <!--Systeme de rencontre-->
        <div class="page" id="p2">
            <?php
                if($fromcomplet==1){
                if($affichecont==1){ ?>
                    <center class="center4"><h1 class="titregsp2"><?php print $tradrenc ?><?php print $_SESSION['nbrenc']+1 ?>/<?php print $resultverif2 ?> 🤝</h1></center>
                    <form method="post"><center class="center4">
                        <span class="cadre">
                            <div id="col">
                                <div>
                                    <h1 class="para"><?php echo $tradnom2; ?></h1><br>
                                    <input class="entre4" type="text" name="matchnom" value='<?php echo $matchnom; ?>' placeholder="Vide" readOnly="readOnly"/><br>
                                    <h1 class="para"><?php echo $tradage2; ?></h1><br>
                                    <input class="entre4" type="text" name="matchage" value='<?php echo $matchage; ?>' placeholder="Vide" readOnly="readOnly"/><br>
                                    <h1 class="para"><?php echo $tradsnap2; ?></h1><br>
                                    <input class="entre4" type="text" name="matchsnap" value='<?php echo $matchsnap; ?>' placeholder="Vide" readOnly="readOnly"/><br>
                                </div>
                                <div>
                                    <h1 class="para"><?php echo $tradprenom2; ?></h1><br>
                                    <input class="entre4" type="text" name="matchprenom" value='<?php echo $matchprenom; ?>' placeholder="Vide" readOnly="readOnly"/><br>
                                    <h1 class="para"><?php echo $tradclasse2; ?></h1><br>
                                    <input class="entre4" type="text" name="matchclasse" value='<?php echo $matchclasse; ?>' placeholder="Vide" readOnly="readOnly"/><br>
                                    <h1 class="para"><?php echo $tradinsta2; ?></h1><br>
                                    <input class="entre4" type="text" name="matchinsta" value='<?php echo $matchinsta; ?>' placeholder="Vide" readOnly="readOnly"/><br>
                                </div>
                            </div>
                        <textarea rows="5" class="entre5" name="desk" placeholder="Aucunes Infos" maxlength="300" readOnly="readOnly"><?php print $matchdesk ?></textarea>
                        </span>
                        <br>
                        <button class="valid2" type="submit" value="prev" name="go4">Précédent</button>
                        <button class="valid2" type="submit" value="next" name="go3">Suivant</button>
                    </form></center>
            <?php
                }else{ ?>
                    <center class="center5"><h1 class="titregsp3">Aucun résultat ne correspond à vos critères de recherche ⛔</h1></center>
                <?php }
                }else{ ?>
                    <!-- Au cas où le profil n'est pas complété, on ne peut pas voir d'autres profils -->
                    <center class="center5"><h1 class="titregsp3">Veuillez compléter votre profil ⛔</h1></center>
                <?php }
            ?>
        </div>
        <div class="page" id="p3">
            <section class="gossip"><span>
                <!--Partie sur le Gossip Baumont, lien entre l'instagram et le site-->
                <center class="titregsp">Gossip Baumont <?php print $news ?> <img class="logob" src="img/baumont.png"></center>
                <!-- <script src="https://apps.elfsight.com/p/platform.js" defer></script>
                <div class="elfsight-app-c14f084d-67ad-40f0-84e6-ec19f9647e9e"></div> -->
            </span></section>
        </div>
        <div class="page" id="p4">
            <!--Page pour compléter le profil : nom, prénom, age etc.-->
            <form method="post"><center class="center4">
                <h1 class="titregsp"><?php print $tprof ?></h1>
                <input class="entre1" type="text" name="nom" value='<?php echo $recupnom; ?>' placeholder="<?php print $tradnom ?>"/>
                <input class="entre1" type="text" name="prenom" value='<?php echo $recupprenom; ?>' placeholder="<?php print $tradprenom ?>"/>
                <input class="entre1" type="number" name="age" min="13" max="120" value='<?php echo $recupage; ?>' placeholder="<?php print $tradage ?>"/>
                <input class="entre1" type="text" name="classe" value='<?php echo $recupclasse; ?>' placeholder="<?php print $tradclasse ?>"/>
                <input class="entre1" type="text" name="snap" value='<?php echo $recupsnap; ?>' placeholder="<?php print $tradsnap ?>"/>
                <input class="entre1" type="text" name="insta" value='<?php echo $recupinsta; ?>' placeholder="<?php print $tradinsta ?>"/>
                <select class="entre2" name="sexechoix">
                    <option value=""><?php print $tradsexe ?></option>
                    <option value="m" <?php if($recupsexe == "m") echo 'selected="selected"'; ?>><?php print $tradsexem ?></option>
                    <option value="f" <?php if($recupsexe == "f") echo 'selected="selected"'; ?>><?php print $tradsexef ?></option>
                    <option value="autre" <?php if($recupsexe == "autre") echo 'selected="selected"'; ?>><?php print $tradsexea ?></option>
                </select><br>
                <select class="entre2" name="rechsexechoix">
                    <option value=""><?php print $tradrecherchesx ?></option>
                    <option value="m" <?php if($recuprechsexe == "m") echo 'selected="selected"'; ?>><?php print $tradrecherchesxmasc ?></option>
                    <option value="f" <?php if($recuprechsexe == "f") echo 'selected="selected"'; ?>><?php print $tradrecherchesxfem ?></option>
                </select><br>
                <textarea rows="5" class="entre3" name="desk" placeholder="<?php print $traddesk ?>" maxlength="300"><?php echo $recupdesk; ?></textarea><br>
                <button class="valid" type="submit" value="envoyer" name="go"><?php print $tradvalid ?></button>
            </form></center>
        </div>
        <div class="page" id="p5">
            <!--Page des paramètres-->
            <form method="post"><center class="center6">
                <h1 class="titregspv2"><?php print $tradpara ?></h1><br>
                <!--Conditions pour le changement de mot de passe-->
                <input class="entre1" type="password" name="password" pattern="(?=.*\d)(?=.*[a-z]).{8,}" title="8 caractères min et un chiffre (nom identique au pseudo)" placeholder="<?php print $tradmdp1 ?>"/>
                <input class="entre1" type="password" name="password2" pattern="(?=.*\d)(?=.*[a-z]).{8,}" title="8 caractères min et un chiffre (nom identique au pseudo)" placeholder="<?php print $tradmdp2 ?>"/>
                <select class="entre2" name="themechoix">
                    <!--Choix du theme (sombre/clair)-->
                    <option value=""><?php print $tradtheme ?></option>
                    <option value="0" <?php if($recuptheme == "0") echo 'selected="selected"'; ?>><?php print $tradtheme1 ?></option>
                    <option value="1" <?php if($recuptheme == "1") echo 'selected="selected"'; ?>><?php print $tradtheme2 ?></option>
                </select>
                <select class="entre2" name="langchoix">
                    <!--Choix de la langue anglaise/francaise-->
                    <option value=""><?php print $tradlang ?></option>
                    <option value="0" <?php if($recuplang == "0") echo 'selected="selected"'; ?>><?php print $tradlang1 ?></option>
                    <option value="1" <?php if($recuplang == "1") echo 'selected="selected"'; ?>><?php print $tradlang2 ?></option>
                </select><br>
                <!--Bouton de validation-->
                <button class="validv2" type="submit" value="enrg" name="go2"><?php print $tradsave ?></button><br>
                <!-- Message erreur si conditions mdp non respecté -->
                <?php if(isset($message)) { ?>
                    <a class="msgerror"><?php echo $message; ?></a>
                <?php } ?>
            </center></form>
        </div>
    </div>
	</div>
	</div>
	</div>
	</div>
    <footer>
        Kylian & Gabin TG3-TG1 Copyright ©
    </footer>
</body>
</html>