<?php
session_start();
$dbConnect = new PDO("mysql:host=localhost;dbname=projet99", "root", "");
include("tableaufilm.php");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet 7 Cinema</title>
</head>

<body>
    <header>
        <div class='jumpbottron'>
            <img class='marvelbanner' src="marvelbanner.png" alt="">
        </div>
        <nav>

            
            </ul>
            <ul class='ulnavun'>
                <a href="index.php"><li>Accueil</li></a>
                <a href="index.php?page=film"><li>Les Films</li></a>
                <a href="index.php?page=settingsadmin"><li>Paramètres</li></a>
                <?php 
                if (!empty($_SESSION)){
                    echo '<a href="index.php?page=logout"><li>Se déconnecter</li></a>';
                } 
                else{
                    echo '<a href="index.php?page=login"><li>Se connecter</li></a>';
                }
                
                ?>
            </ul>
            
            
            <ul class='ulnav2'>
            <?php
                if (isset($_GET['page']) && $_GET['page']=='film'){
                    echo '<a href="index.php?page=film&filtre=acteur"><li>Par Acteur</li></a>';
                    echo '<a href="index.php?page=film&filtre=realisateur"><li>Par Réalisateur</li></a>';
                    echo '<a href="index.php?page=film&filtre=annee"><li>Par Années</li></a>';
                }
                
            ?>
            </ul>
        </nav>
    </header>
<section class='cardsection'>
    <?php 
                                    //  CONNEXION //

                    if (isset($_GET['page']) && ($_GET['page'])=='login'){ 

                    echo '<div class=\'divformlog\'><form class=\'formulairelogin\' method=\'POST\'>
                    <input type=text name=\'id\' placeholder=\'Identifiant\'>
                    <input type="password" name=\'password\' placeholder=\'Mot de passe\'>
                    <input type="submit" name=\'submitlogin\' value=\'Se connecter\'>
                    <a href="index.php?page=signin">Créer un compte</a></div>';
                 }
            

                 if (isset($_POST['submitlogin'])){
                    $identifiant=$_POST['id'];
                    $sql="SELECT * FROM `utilisateur` WHERE `identifiant`='$identifiant'";
                    $stmt= $dbConnect->prepare($sql);
                    $stmt->execute();
                    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($_POST['password']==$result[0]['Motdepasse']){
                    $_SESSION=['Identifiant'=> $result[0]['Identifiant'], 'Mail'=>$result[0]['Mail']];
                    echo 'bienvenue '. $_SESSION['Identifiant'] .'';
                        }
                }
                
                                    // CONNEXION //


                                    // DECONNEXION //

                if (isset($_GET['page']) && ($_GET['page'])=='logout'){ 

                    echo '<p>Voulez-vous vraiment vous déconnecter?<p><form method=\'POST\'>
                            <input type=\'submit\' name=\'logoutsubmit\' value=\'Se déconnecter\'></form>';
                 }

                if (isset($_POST['logoutsubmit'])){
                    session_destroy();
                }

                                    // DECONNEXION //


                                    // INSCRIPTION //

                  if (isset($_GET['page']) && ($_GET['page'])=='signin'){ 

                   echo 
                        '<div class=\'divformlog\'>
                            <form method=\'POST\' class=\'formsignin\'>
                   
                                <input type=\'text\' name=\'idsignin\' placeholder=\'Identifiant\'>
                                <input type=\'password\' name=\'passwordsignin\' placeholder=\'Mot de passe\'>
                                <input type=\'text\' name=\'mailsignin\' placeholder=\'Email\'>
                                <input type=\'submit\' name=\'submitsignin\' value=\'Créer mon compte\'>
                              
                            </form></div>' ;
                  }
                  if (isset($_POST['submitsignin'])){
                      $identifiant=$_POST['idsignin'];
                      $password=$_POST['passwordsignin'];
                      $mail=$_POST['mailsignin'];

                    $sql_check_unique = "SELECT COUNT(*) FROM utilisateur WHERE identifiant = ?";
                    $stmt_check_unique = $dbConnect->prepare($sql_check_unique);
                    $stmt_check_unique->execute([$mail]);
                    $count = $stmt_check_unique->fetchColumn();


                      
                      $sql = "INSERT INTO `utilisateur`(`Identifiant`, `Motdepasse`, `Mail`) 
                     VALUES ('$identifiant','$password','$mail')";
                    $stmt = $dbConnect->prepare($sql);
                    $stmt->execute();
                    
                }
                
                                    // INSCRIPTION //

                                    
                                    // PAGE D'ACCUEILLE //

    if (!isset($_GET['page']) && empty($_SESSION)){

        echo '<div class=\'warning1\'><p>Vous devez être connecter pour accéder à cette page.</p><div>';
    }                                 

    if (!isset($_GET['page']) && !empty($_SESSION)){

        echo '<div class=\'warning2\'><p>Bienvenue sur votre page d\'accueille '. $_SESSION['Identifiant'] .' !</p></div>';
    }

                                  // PAGE D'ACCUEILLE //

        
                                  // PAGE FILM //

    if (isset($_GET['page']) && $_GET['page']=='film' && !isset($_GET['filtre'])){

            $sql="SELECT `film`.`Titre`,`film`.`Affiche`,GROUP_CONCAT(CONCAT(`acteur`.`prenom_acteur`,' ',`acteur`.`nom_acteur`)) AS 'Acteurs', CONCAT(`realisateur`.`prenom_realisateur`,' ',`realisateur`.`nom_realisateur`) AS 'Réalisateur',`film`.`Durée`,`film`.`Date_de_sortie` AS 'Date'
            FROM `film` 
            INNER JOIN `joue` ON `film`.`id_film`=`joue`.`id_film` 
            INNER JOIN `acteur` ON `acteur`.`id_acteur`=`joue`.`id_acteur` 
            INNER JOIN `realise` ON `realise`.`id_film`=`film`.`id_film` 
            INNER JOIN `realisateur` ON `realise`.`id_realisateur`=`realisateur`.`id_realisateur` 
            GROUP BY `film`.`Titre`;"; 
            $stmt = $dbConnect->prepare($sql);
            $stmt -> execute();
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);

            foreach ($result as $key){
                echo '<div class=\'card\'>
                <p class=\'title\'>Film: '. $key['Titre'] .'</p>
                <div class=\'img_card\'><img src="'. $key['Affiche'] .'" alt="#"></div>
                <p class=\'acteur\'>Acteurs: '. $key['Acteurs'] .'</p>
                <p class=\'realisateur\'>Réalisateur: '. $key['Réalisateur'] .'</p>
                <p class=\'date\'>Date de sortie: '. $key['Date'] .'</p>
                <p class=\'duree\'>Durée: '. $key['Durée'] .'</p>
                </div>';
            }
    }

                                  // PAGE FILM //

                                // FILTRE ACTEUR //

            if (isset($_GET['filtre']) &&  $_GET['filtre']=='acteur'){

                echo 'Sélectionner un acteur:<form method=\'POST\'>
                    <select name="acteur">
                    <option value="">Séléctionner</option>';
                
                $sql="SELECT CONCAT(`acteur`.nom_acteur,' ',`acteur`.prenom_acteur) AS 'Acteurs' , `acteur`.`id_acteur` FROM `acteur`; "; 
                $stmt = $dbConnect->prepare($sql);
                $stmt -> execute();
                $resultacteur = $stmt->fetchall(PDO::FETCH_ASSOC);
                
                foreach ($resultacteur as $acteur ) {
                    echo '<option value="' .$acteur['id_acteur']. '">' .$acteur['Acteurs']. '</option>';
                }
                echo '</select>';
                echo '<input type="submit" name=\'acteur_submit\' value=\'Filtrer\'></form>';
            }
            
            if (isset($_POST['acteur_submit'])){
                $acteurchoisi=$_POST['acteur'];
                $sql="SELECT `film`.`Titre`,`film`.`Affiche`,GROUP_CONCAT(CONCAT(`acteur`.`prenom_acteur`,' ',`acteur`.`nom_acteur`)) AS 'Acteurs', CONCAT(`realisateur`.`prenom_realisateur`,' ',`realisateur`.`nom_realisateur`) AS 'Réalisateur',`film`.`Durée`,`film`.`Date_de_sortie` AS 'Date'
                FROM `film` 
                INNER JOIN `joue` ON `film`.`id_film`=`joue`.`id_film` 
                INNER JOIN `acteur` ON `acteur`.`id_acteur`=`joue`.`id_acteur` 
                INNER JOIN `realise` ON `realise`.`id_film`=`film`.`id_film` 
                INNER JOIN `realisateur` ON `realise`.`id_realisateur`=`realisateur`.`id_realisateur` 
                WHERE `acteur`.`id_acteur`=" .$acteurchoisi. "
                GROUP BY `film`.`Titre`;";
            $stmt = $dbConnect->prepare($sql);
            $stmt -> execute();
            $resultacteurread = $stmt->fetchall(PDO::FETCH_ASSOC);
            foreach ($resultacteurread as $key){
                echo '<div class=\'card\'>
                <p class=\'title\'>Film: '. $key['Titre'] .'</p>
                <div class=\'img_card\'><img src="'. $key['Affiche'] .'" alt="#"></div>
                <p class=\'acteur\'>Acteurs: '. $key['Acteurs'] .'</p>
                <p class=\'realisateur\'>Réalisateur: '. $key['Réalisateur'] .'</p>
                <p class=\'date\'>Date de sortie: '. $key['Date'] .'</p>
                <p class=\'duree\'>Durée: '. $key['Durée'] .'</p>
                </div>';
            }
            
            }

                            // FILTRE ACTEUR //
                            

                            // FILTRE REALISATEUR //

            if (isset($_GET['filtre']) &&  $_GET['filtre']=='realisateur'){

                echo 'Sélectionner un réalisateur:<form method=\'POST\'>
                    <select name="realisateur">
                    <option value="">Séléctionner</option>';
                                
                $sql="SELECT CONCAT(`realisateur`.nom_realisateur,' ',`realisateur`.prenom_realisateur) AS 'realisateur' , `realisateur`.`id_realisateur` FROM `realisateur`; "; 
                $stmt = $dbConnect->prepare($sql);
                $stmt -> execute();
                $resultreal = $stmt->fetchall(PDO::FETCH_ASSOC);
                
                foreach ($resultreal as $real ) {
                    echo '<option value="' .$real['id_realisateur']. '">' .$real['realisateur']. '</option>';
                }
                echo '</select>';
                echo '<input type="submit" name=\'real_submit\' value=\'Filtrer\'></form>';
            }
                            
            if (isset($_POST['real_submit'])){
                $realchoisi=$_POST['realisateur'];
                $sql="SELECT `film`.`Titre`,`film`.`Affiche`,GROUP_CONCAT(CONCAT(`acteur`.`prenom_acteur`,' ',`acteur`.`nom_acteur`)) AS 'Acteurs', CONCAT(`realisateur`.`prenom_realisateur`,' ',`realisateur`.`nom_realisateur`) AS 'Réalisateur',`film`.`Durée`,`film`.`Date_de_sortie` AS 'Date'
                FROM `film` 
                INNER JOIN `joue` ON `film`.`id_film`=`joue`.`id_film` 
                INNER JOIN `acteur` ON `acteur`.`id_acteur`=`joue`.`id_acteur` 
                INNER JOIN `realise` ON `realise`.`id_film`=`film`.`id_film` 
                INNER JOIN `realisateur` ON `realise`.`id_realisateur`=`realisateur`.`id_realisateur` 
                WHERE `realisateur`.`id_realisateur`=" .$realchoisi. "
                GROUP BY `film`.`Titre`;";
                    $stmt = $dbConnect->prepare($sql);
                    $stmt -> execute();
                    $resultrealread = $stmt->fetchall(PDO::FETCH_ASSOC);
                        foreach ($resultrealread as $keyreal){
                            echo '<div class=\'card\'>
                            <p class=\'title\'>Film: '. $keyreal['Titre'] .'</p>
                            <div class=\'img_card\'><img src="'. $keyreal['Affiche'] .'" alt="#"></div>
                            <p class=\'acteur\'>Acteurs: '. $keyreal['Acteurs'] .'</p>
                            <p class=\'realisateur\'>Réalisateur: '. $keyreal['Réalisateur'] .'</p>
                            <p class=\'date\'>Date de sortie: '. $keyreal['Date'] .'</p>
                            <p class=\'duree\'>Durée: '. $keyreal['Durée'] .'</p>
                            </div>';
                        }
            }

    
                            // FILTRE REALISATEUR //

                                                        // FILTRE ANNEE //

            if (isset($_GET['filtre']) &&  $_GET['filtre']=='annee'){

                echo 'Sélectionner un réalisateur:<form method=\'POST\'>
                    <select name="DDate">
                    <option value="">Séléctionner</option>';
                                
                $sql="SELECT `film`.`Date_de_sortie` AS 'Date', `film`.`id_film` FROM `film` GROUP BY `film`.`Date_de_sortie`;"; 
                $stmt = $dbConnect->prepare($sql);
                $stmt -> execute();
                $resultdate = $stmt->fetchall(PDO::FETCH_ASSOC);
                
                foreach ($resultdate as $date) {
                    echo '<option value="' .$date['Date']. '">' .$date['Date']. '</option>';
                }
                echo '</select>';
                echo '<input type="submit" name=\'date_submit\' value=\'Filtrer\'></form>';
                
            }
                            
            if (isset($_POST['date_submit'])){
                $datechoisi=$_POST['DDate'];
                $sql="SELECT `film`.`Titre`,`film`.`Affiche`,GROUP_CONCAT(CONCAT(`acteur`.`prenom_acteur`,' ',`acteur`.`nom_acteur`)) AS 'Acteurs', CONCAT(`realisateur`.`prenom_realisateur`,' ',`realisateur`.`nom_realisateur`) AS 'Réalisateur',`film`.`Durée`,`film`.`Date_de_sortie` AS 'Date'
                FROM `film` 
                INNER JOIN `joue` ON `film`.`id_film`=`joue`.`id_film` 
                INNER JOIN `acteur` ON `acteur`.`id_acteur`=`joue`.`id_acteur` 
                INNER JOIN `realise` ON `realise`.`id_film`=`film`.`id_film` 
                INNER JOIN `realisateur` ON `realise`.`id_realisateur`=`realisateur`.`id_realisateur` 
                WHERE `film`.`Date_de_sortie`=" .$datechoisi. "
                GROUP BY `film`.`Titre`;";
                    $stmt = $dbConnect->prepare($sql);
                    $stmt -> execute();
                    $resultanneeread = $stmt->fetchall(PDO::FETCH_ASSOC);
                        foreach ($resultanneeread as $keyyear){
                            echo '<div class=\'card\'>
                            <p class=\'title\'>Film: '. $keyyear['Titre'] .'</p>
                            <div class=\'img_card\'><img src="'. $keyyear['Affiche'] .'" alt="#"></div>
                            <p class=\'acteur\'>Acteurs: '. $keyyear['Acteurs'] .'</p>
                            <p class=\'realisateur\'>Réalisateur: '. $keyyear['Réalisateur'] .'</p>
                            <p class=\'date\'>Date de sortie: '. $keyyear['Date'] .'</p>
                            <p class=\'duree\'>Durée: '. $keyyear['Durée'] .'</p>
                            </div>';
                        }
            }

    
                            // FILTRE ANNEE //

                            // CREATE - PARAMETRES ADMIN //

            if (isset($_GET['page']) && $_GET['page']=='settingsadmin'){
                echo '<section class=\'createsection\'><div class=divglobalcreate><div class=\'divcreate\'>
                        <form class=\'formcreate\' method=\'POST\'>
                            <p>Ajouter un film:</p>
                            <input type="text" name=\'title\' placeholder=\'Titre du film\'>
                            <input type="text" name=\'img\' placeholder=\'Affiche\'> 
                            <select name="acteur"><option value="">Acteur -1-</option>';
                                
                                $sql="SELECT CONCAT(`acteur`.nom_acteur,' ',`acteur`.prenom_acteur) AS 'Acteurs' , `acteur`.`id_acteur` FROM `acteur`; "; 
                                $stmt = $dbConnect->prepare($sql);
                                $stmt -> execute();
                                $resultacteur = $stmt->fetchall(PDO::FETCH_ASSOC);
                                
                                foreach ($resultacteur as $acteur ) {
                                    echo '<option value="' .$acteur['id_acteur']. '">' .$acteur['Acteurs']. '</option>';
                                }
        
                           
                             
                            echo    '</select><select name="acteur2"><option value="">Acteur -2-</option>';
                                
                            $sql="SELECT CONCAT(`acteur`.nom_acteur,' ',`acteur`.prenom_acteur) AS 'Acteurs' , `acteur`.`id_acteur` FROM `acteur`; "; 
                            $stmt = $dbConnect->prepare($sql);
                            $stmt -> execute();
                            $resultacteur = $stmt->fetchall(PDO::FETCH_ASSOC);
                            
                            foreach ($resultacteur as $acteur ) {
                                echo '<option value="' .$acteur['id_acteur']. '">' .$acteur['Acteurs']. '</option>';
                            }
    
                       
                         
                            echo    '</select><select name="acteur3"><option value="">Acteur -3-</option>';
                                
                            $sql="SELECT CONCAT(`acteur`.nom_acteur,' ',`acteur`.prenom_acteur) AS 'Acteurs' , `acteur`.`id_acteur` FROM `acteur`; "; 
                            $stmt = $dbConnect->prepare($sql);
                            $stmt -> execute();
                            $resultacteur = $stmt->fetchall(PDO::FETCH_ASSOC);
                        
                            foreach ($resultacteur as $acteur ) {
                                echo '<option value="' .$acteur['id_acteur']. '">' .$acteur['Acteurs']. '</option>';
                            }

                   
                            
                            echo    '</select><select name="acteur4"><option value="">Acteur -4-</option>';
                                        
                            $sql="SELECT CONCAT(`acteur`.nom_acteur,' ',`acteur`.prenom_acteur) AS 'Acteurs' , `acteur`.`id_acteur` FROM `acteur`; "; 
                            $stmt = $dbConnect->prepare($sql);
                            $stmt -> execute();
                            $resultacteur = $stmt->fetchall(PDO::FETCH_ASSOC);
                            
                            foreach ($resultacteur as $acteur ) {
                                echo '<option value="' .$acteur['id_acteur']. '">' .$acteur['Acteurs']. '</option>';
                            }

                                
                            
                            echo    '</select><select name="acteur5"><option value="">Acteur -5-</option>';
                                            
                            $sql="SELECT CONCAT(`acteur`.nom_acteur,' ',`acteur`.prenom_acteur) AS 'Acteurs' , `acteur`.`id_acteur` FROM `acteur`; "; 
                            $stmt = $dbConnect->prepare($sql);
                            $stmt -> execute();
                            $resultacteur = $stmt->fetchall(PDO::FETCH_ASSOC);
                            
                            foreach ($resultacteur as $acteur ) {
                                echo '<option value="' .$acteur['id_acteur']. '">' .$acteur['Acteurs']. '</option>';
                            }

           
             
                            echo    '</select><select name="realisateur"><option value="">Réalisateur</option>';
                                    
                                 $sql="SELECT CONCAT(`realisateur`.nom_realisateur,' ',`realisateur`.prenom_realisateur) AS 'realisateur' , `realisateur`.`id_realisateur` FROM `realisateur`; "; 
                                $stmt = $dbConnect->prepare($sql);
                                $stmt -> execute();
                                $resultreal = $stmt->fetchall(PDO::FETCH_ASSOC);
                    
                                foreach ($resultreal as $real ) {
                                    echo '<option value="' .$real['id_realisateur']. '">' .$real['realisateur']. '</option>';
                                }

                            echo    '</select>
                                    <input type="text" name=\'exitdate\' placeholder=\'Date de sortie\'> 
                                    <input type="text" name=\'duration\' placeholder=\'Durée\'>
                                    <input type="submit" name=\'addfilm_submit\' value=\'Ajouter\'>
                                    </form>';
             
             if (isset($_POST['addfilm_submit'])){
                $titre=$_POST['title'];
                $img=$_POST['img'];
                $idacteur=$_POST['acteur'];
                $idacteur2=$_POST['acteur2'];
                $idacteur3=$_POST['acteur3'];
                $idacteur4=$_POST['acteur4'];
                $idacteur5=$_POST['acteur5'];
                $idrealisateur=$_POST['realisateur'];
                $exitdate=$_POST['exitdate'];
                $duration=$_POST['duration'];
                    $sql = "INSERT INTO `film`(`Titre`, `Affiche`, `Durée`, `Date_de_sortie`) VALUES ('$titre','$img','$duration','$exitdate');
                    SELECT LAST_INSERT_ID();
                    SET @film_id := LAST_INSERT_ID();
                    INSERT INTO joue (`id_film`, `id_acteur`) VALUES (@film_id, $idacteur);
                    INSERT INTO joue (`id_film`, `id_acteur`) VALUES (@film_id, $idacteur2);
                    INSERT INTO joue (`id_film`, `id_acteur`) VALUES (@film_id, $idacteur3);
                    INSERT INTO joue (`id_film`, `id_acteur`) VALUES (@film_id, $idacteur4);
                    INSERT INTO joue (`id_film`, `id_acteur`) VALUES (@film_id, $idacteur5);
                    INSERT INTO realise (`id_film`, `id_realisateur`) VALUES (@film_id, $idrealisateur);";
                            
                            $stmt = $dbConnect->prepare($sql);
                            $stmt->execute();
            
            }
                                    // CREATE - PARAMETRES ADMIN //


                                    // CREATE - Ajouter acteur-real //


                echo
                     '<div class=\'formacteur\'><form class=\'formcreate\' method=\'POST\'>
                     <p>Ajouter un acteur à la BDD: </p>
                     <input type="text" name=\'Nom_acteur\' placeholder=\'Nom\'>      
                     <input type="text" name=\'Prenom_acteur\' placeholder=\'Prenom\'>
                     <input type="submit" name=\'addactor_submit\' value=\'Ajouter\'></form>';
                
                if (isset($_POST['addactor_submit'])) {
                    $nomacteur=$_POST['Nom_acteur'];
                    $prenomacteur=$_POST['Prenom_acteur'];
                    $sql="INSERT INTO `acteur`(`nom_acteur`, `prenom_acteur`) VALUES ('$nomacteur', '$prenomacteur');";
                    $stmt = $dbConnect->prepare($sql);
                    $stmt->execute();
                }

                echo 
                    '<form class=\'formcreate\' method=\'POST\'>
                    <p>Ajouter un réalisateur à la BDD: </p>
                    <input type="text" name=\'Nom_real\' placeholder=\'Nom\'>      
                    <input type="text" name=\'Prenom_real\' placeholder=\'Prenom\'>
                    <input type="submit" name=\'addreal_submit\' value=\'Ajouter\'></form></div></div>';
                
                if (isset($_POST['addreal_submit'])) {
                    $nomreal=$_POST['Nom_real'];
                    $prenomreal=$_POST['Prenom_real'];
                    $sql="INSERT INTO `realisateur`(`nom_realisateur`, `prenom_realisateur`) VALUES ('$nomreal', '$prenomreal');";
                    $stmt = $dbConnect->prepare($sql);
                    $stmt->execute();
                }
                                    // CREATE - Ajouter acteur-real //         
        

                                    // DELETE - SUPPRIMER FILM //

        echo 
            '<form class=\'formdelete\' method=\'POST\'>
            <p>Supprimer un film: </p>
            <select name="deleteselect"><option value="">Séléctionner</option>';
                
            $sql="SELECT (`film`.`titre`) AS 'Titre' , `film`.`id_film` FROM `film`;"; 
            $stmt = $dbConnect->prepare($sql);
            $stmt -> execute();    
            $filmmarvel = $stmt->fetchall(PDO::FETCH_ASSOC);
                
                foreach ($filmmarvel as $titrefilmmarvel ) {
                    echo '<option value="' .$titrefilmmarvel['id_film']. '">' .$titrefilmmarvel['Titre']. '</option>';
                }
                echo '</select>';
                echo '<input type="submit" name=\'deletefilm_submit\' value=\'Supprimer\'></form>';
            
            if (isset($_POST['deletefilm_submit'])) {

                $iddeletefilm=$_POST['deleteselect'];
                $sql="DELETE FROM `realise` WHERE id_film='$iddeletefilm';
                      DELETE FROM `joue` WHERE id_film='$iddeletefilm';
                      DELETE FROM `film` WHERE id_film='$iddeletefilm';";
                $stmt = $dbConnect->prepare($sql);
                $stmt -> execute();  
            }

                                // UPDATE FILM //

        echo '<form class=\'formdelete\' method=\'POST\'>';

            $sql="SELECT `film`.`id_film`,`film`.`Titre`,`film`.`Affiche`,CONCAT(`acteur`.`prenom_acteur`,' ',`acteur`.`nom_acteur`) AS 'Acteurs', CONCAT(`realisateur`.`prenom_realisateur`,' ',`realisateur`.`nom_realisateur`) AS 'Réalisateur',`film`.`Durée`,`film`.`Date_de_sortie` AS 'Date'
            FROM `film` 
            INNER JOIN `joue` ON `film`.`id_film`=`joue`.`id_film` 
            INNER JOIN `acteur` ON `acteur`.`id_acteur`=`joue`.`id_acteur` 
            INNER JOIN `realise` ON `realise`.`id_film`=`film`.`id_film` 
            INNER JOIN `realisateur` ON `realise`.`id_realisateur`=`realisateur`.`id_realisateur`
            GROUP BY `film`.`Titre`;";
            $stmt = $dbConnect->prepare($sql);
            $stmt -> execute();
            $tablefilmupdate = $stmt->fetchall(PDO::FETCH_ASSOC);
            

            echo  '<form class=\'tableform\'method=\'POST\'><p>Modifier Film: </p><table>
                    <tr><th>Titre</th><th>Affiche</th><th>Durée</th><th>Date de sortie</th><th>Modifier</th></tr>';
            foreach ($tablefilmupdate as $key){
                $idfilm=$key['id_film'];
                echo    '<tr><td><input type="text" name=\'update_title'. $idfilm .'\' value=\'' .$key['Titre']. '\'></td>
                    <td><input type="text" name=\'update_affiche' .$idfilm. '\' value=\'' .$key['Affiche']. '\'></td>
                    <td><input type="text" name=\'update_duree' .$idfilm. '\' value=\'' .$key['Durée']. '\'></td>
                    <td><input type="text" name=\'update_date' .$idfilm. '\' value=\'' .$key['Date']. '\'></td>
                    <td><input type="submit" name=\'modifier' .$idfilm. '\' value=\'Modifer\'></td>';

                    if (isset($_POST['modifier' .$idfilm. ''])){
                        $titreupdate=$_POST['update_title'. $idfilm .''];
                        $afficheupdate=$_POST['update_affiche'. $idfilm .''];
                        $dureeupdate=$_POST['update_duree'. $idfilm .''];
                        $dateupdate=$_POST['update_date'. $idfilm .''];
                       $sql2="UPDATE `film` SET `Titre`='$titreupdate',`Affiche`='$afficheupdate',`Durée`='$dureeupdate',`Date_de_sortie`='$dateupdate' WHERE film.id_film='$idfilm'";
                       $stmt = $dbConnect->prepare($sql2);
                       $stmt -> execute();
                    }
          
        }
        echo '</table></form>';
                                    // UPDATE FILM //
        
                                    // UPDATE ACTEUR  //

        echo  '<form class=\'tableform\'method=\'POST\'><p>Modifier Acteur :<p><table>
                    <tr><th>Acteur</th><th>Nouveau prenom:</th><th>Nouveau Nom:</th><th>Modifier:</th><th>Supprimer:</th></tr>';
            $sql3="SELECT acteur.id_acteur, CONCAT(acteur.prenom_acteur,' ',acteur.nom_acteur) AS 'Acteur' FROM `acteur`; ";
            $stmt = $dbConnect->prepare($sql3);
            $stmt -> execute();
            $tableacteurupdate = $stmt->fetchall(PDO::FETCH_ASSOC);

            foreach ($tableacteurupdate as $keyacteur){
                
                
                $idacteurupdate=$keyacteur['id_acteur'];
                $nomcompletacteur=$keyacteur['Acteur'];

                list($nameactormaj, $prenameactormaj)=explode(" ",$nomcompletacteur);
                echo    '<tr><td>' .$keyacteur['Acteur']. '</td>
                    <td><input type="text" name=\'update_nomacteur' .$idacteurupdate. '\' value=\'' .$nameactormaj. '\'></td>
                   <td><input type="text" name=\'update_prenomacteur' .$idacteurupdate. '\' value=\'' .$prenameactormaj. '\'></td>
                    <td><input type="submit" name=\'modifieracteur' .$idacteurupdate. '\' value=\'Modifer\'></td>
                    <td><input type="submit" name=\'deleteacteur' .$idacteurupdate. '\' value=\'Supprimer\'></td>';

                    if (isset($_POST['modifieracteur' .$idacteurupdate. ''])){
                        $actornomupdatefinal=$_POST['update_prenomacteur'. $idacteurupdate .''];
                        $actorprenomupdatefinal=$_POST['update_nomacteur'. $idacteurupdate .''];

                       $sql3="UPDATE `acteur` SET `nom_acteur`='$actornomupdatefinal',`prenom_acteur`='$actorprenomupdatefinal' WHERE acteur.id_acteur='$idacteurupdate'";
                       $stmt = $dbConnect->prepare($sql3);
                       $stmt -> execute();
                    }

                    if (isset($_POST['deleteacteur' .$idacteurupdate. ''])) {
                        $sql4="DELETE FROM `acteur` WHERE id_acteur='$idacteurupdate'";
                        $stmt = $dbConnect->prepare($sql4);
                        $stmt -> execute();
                    }
            }
                echo '</table></form></div></section>';
        }
                                    // UPDATE ACTEUR  //
?>  
</section>    
</body>
</html>
