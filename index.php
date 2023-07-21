<?php
session_start();
$dbConnect = new PDO("mysql:host=localhost;dbname=projet9", "root", "");
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
                <!-- <a href="index.php?filtre=genre"><li>Genre</li></a>
                <a href="index.php?filtre=realisateur"><li>Réalisateur</li></a> -->
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
                }
                
            ?>
            </ul>
            <?php
                //  if (isset($_GET['filtre']) && ($_GET['filtre'])=='genre'){ 
                //  $filtre = $_GET['filtre'];
                //  $genresAffiches =[];
                //      echo '<ul class=\'genre\'>';

                //     foreach ($listfilm as $film => $value) {
                //         $genre=explode(", ",$value['genre']);
                //         foreach ($genre as $key) {
                //            if (!in_array($key, $genresAffiches)) {
                //             echo '<a href="index.php?filtre='. $filtre .'&genre='. $key .'"><li>'. $key .'</li></a>';
                //             $genresAffiches[] = $key;
                //         '</ul>';
                //         }
                          
                //         }
                //     }
                // }
                 
                 
                //  if (isset($_GET['filtre']) && ($_GET['filtre'])=='realisateur') {
                //      $filtre = $_GET['filtre'];
                //      $realAffiches=[];
                //         echo '<ul class=\'realisateur\'>';

                //     foreach ($listfilm as $film => $value) {
                //         $realisateur=explode(", ",$value['realisateur']);
                //         foreach ($realisateur as $keyreal) {
                //             if (!in_array($keyreal, $realAffiches)) {
                //                 echo
                //                     '<a href="index.php?filtre='. $filtre .'&realisateur='. $keyreal .'"><li>'. $keyreal .'</li></a>';
                //                     '</ul>';
                //             }
                //         }
                        
                //     }
                //  }
                
            ?>
        </nav>
    </header>
<section class='cardsection'>
    <?php 
                                    //  CONNEXION //

                    if (isset($_GET['page']) && ($_GET['page'])=='login'){ 
                    $page = $_GET['page'];
                    $genresAffiches =[];
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
                    $page = $_GET['page'];
                    $genresAffiches =[];
                    echo '<p>Voulez-vous vraiment vous déconnecter?<p><form method=\'POST\'>
                            <input type=\'submit\' name=\'logoutsubmit\' value=\'Se déconnecter\'></form>';
                 }

                if (isset($_POST['logoutsubmit'])){
                    session_destroy();
                }

                                    // DECONNEXION //


                                    // INSCRIPTION //

                  if (isset($_GET['page']) && ($_GET['page'])=='signin'){ 
                    $page = $_GET['page'];
                   $genresAffiches =[];

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

                            // CREATE - PARAMETRES ADMIN //

            if (isset($_GET['page']) && $_GET['page']=='settingsadmin'){
                echo '<div class=\'divcreate\'>
                        <form class=\'formcreate\' method=\'POST\'>
                            <p>Ajouter un film:</p>
                            <input type="text" name=\'title\' placeholder=\'Titre du film\'>
                            <input type="text" name=\'img\' placeholder=\'Affiche\'> 
                            <input type="text" name=\'actorname\' placeholder=\'Nom Acteur\'>
                            <input type="text" name=\'actorprename\' placeholder=\'Prénom Acteur\'>
                            <input type="text" name=\'realisatorname\' placeholder=\' Nom Réalisateur\'> 
                            <input type="text" name=\'realisatorprename\' placeholder=\'Prénom Réalisateur\'> 
                            <input type="text" name=\'exitdate\' placeholder=\'Date de sortie\'> 
                            <input type="text" name=\'duration\' placeholder=\'Durée\'>
                            <input type="submit" name=\'addfilm_submit\' value=\'Ajouter\'>
                        </form>
                    </div>';
            }

            if (isset($_POST['addfilm_submit'])){
                $titre=$_POST['title'];
                $img=$_POST['img'];
                $actorname=$_POST['actorname'];
                $actorprename=$_POST['actorprename'];
                $realisatorname=$_POST['realisatorname'];
                $realisatorprename=$_POST['realisatorprename'];
                $exitdate=$_POST['exitdate'];
                $duration=$_POST['duration'];
                    $sql = "INSERT INTO `film`(`Titre`, `Affiche`, `Durée`, `Date_de_sortie`) VALUES ('$titre','$img','$duration','$exitdate');
                            SELECT LAST_INSERT_ID();
                            SET @film_id := LAST_INSERT_ID();
                            INSERT INTO joue (id_film) VALUES (@film_id);";
                            $stmt = $dbConnect->prepare($sql);
                            $stmt->execute();
                            
                    $sql = "INSERT INTO `acteur`(`nom_acteur`, `prenom_acteur`) VALUES ('$actorname','$actorprename');
                            SELECT LAST_INSERT_ID();
                            SET @acteur_id := LAST_INSERT_ID();
                            INSERT INTO joue (id_acteur) VALUES (@acteur_id);";
                            $stmt = $dbConnect->prepare($sql);
                            $stmt->execute();
                    $sql = "INSERT INTO `realisateur`(`nom_realisateur`, `prenom_realisateur`) VALUES ('$realisatorname','$realisatorprename');";
                            $stmt = $dbConnect->prepare($sql);
                            $stmt->execute();
                

            }
?>  
<?php
                            // CREATE - PARAMETRES ADMIN //


        // else {
        //     foreach ($listfilm as $film => $value) {
        //         echo     '<div class=\'card\'>
        //             <p class=\'title\'>Film: '. $value['name'] .'</p>
        //             <div class=\'img_card\'><img src="'. $value['img'] .'" alt="#"></div>
        //             <p class=\'date\'>Date de sortie: '. $value['date'] .'</p>
        //             <p class=\'realisateur\'>Réalisateur: '. $value['realisateur'] .'</p>
        //             <p class=\'duree\'>Durée: '. $value['duree'] .'</p>
        //             <p class=\'genre\'>Genre: '. $value['genre'] .'</p>
        //             <p class=\'synopsis\'>Synopsis: '. $value['synopsis'] .'</p>
        //             <p class=\'bandeannonce\'>Bande-annonce: '. $value['bandeannonce'] .'</p>
        //             </div>';
        //     }

        // }
        ?>

</section>    
</body>
</html>
