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



            <?php

                 if (isset($_GET['filtre']) && ($_GET['filtre'])=='genre'){ 
                 $filtre = $_GET['filtre'];
                 $genresAffiches =[];
                     echo '<ul class=\'genre\'>';

                    foreach ($listfilm as $film => $value) {
                        $genre=explode(", ",$value['genre']);
                        foreach ($genre as $key) {
                           if (!in_array($key, $genresAffiches)) {
                            echo '<a href="index.php?filtre='. $filtre .'&genre='. $key .'"><li>'. $key .'</li></a>';
                            $genresAffiches[] = $key;
                        '</ul>';
                        }
                          
                        }
                    }
                }
                 
                 
                 if (isset($_GET['filtre']) && ($_GET['filtre'])=='realisateur') {
                     $filtre = $_GET['filtre'];
                     $realAffiches=[];
                        echo '<ul class=\'realisateur\'>';

                    foreach ($listfilm as $film => $value) {
                        $realisateur=explode(", ",$value['realisateur']);
                        foreach ($realisateur as $keyreal) {
                            if (!in_array($keyreal, $realAffiches)) {
                                echo
                                    '<a href="index.php?filtre='. $filtre .'&realisateur='. $keyreal .'"><li>'. $keyreal .'</li></a>';
                                    '</ul>';
                            }
                        }
                        
                    }
                 }
                
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

        echo 'Vous devez être connecter pour accéder à cette page.';
    }                                 

    if (!isset($_GET['page']) && !empty($_SESSION)){

        echo 'Bienvenue sur votre page d\'accueille '. $_SESSION['Identifiant'] .' !';
    }

                                  // PAGE D'ACCUEILLE //

        
                                  // PAGE FILM //

    if (isset($_GET['page']) && $_GET['page']=='film'){

            $sql="SELECT `film`.`Titre`,`film`.`Affiche`, GROUP_CONCAT(CONCAT(`acteur`.`prenom_acteur`,' ',`acteur`.`nom_acteur`)) AS 'Acteurs',
            CONCAT(`realisateur`.`prenom_realisateur`,' ',`realisateur`.`nom_realisateur`) AS 'Réalisateur',`film`.`Durée`,`film`.`Date_de_sortie` AS 'Date'
            FROM `film` INNER JOIN `joue` ON `film`.`id_film`=`joue`.`id_film`
            INNER JOIN `acteur` ON `acteur`.`id_acteur`=`joue`.`id_acteur`
            INNER JOIN `realise` ON `realise`.`id_film`=`film`.`id_film`
            INNER JOIN `realisateur` ON `realise`.`id_realisateur`=`realisateur`.`id_realisateur` ";
            $stmt = $dbConnect->prepare($sql);
            $stmt -> execute();
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);

            foreach ($result as $key) {var_dump($result);
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

    if (isset($_GET['genre'])) {
        
       
        foreach ($listfilm as $film => $value) {
            
            if ($value['duree'] !== 'inconnu') {
                $value['duree'] = $value['duree'] / 60;}
                
                if ($_GET['genre']==$value['genre']) {
                    
                    echo     '<div class=\'card\'>
                    <p class=\'title\'>Film: '. $value['name'] .'</p>
                    <div class=\'img_card\'><img src="'. $value['img'] .'" alt="#"></div>
                    <p class=\'date\'>Date de sortie: '. $value['date'] .'</p>
                    <p class=\'realisateur\'>Réalisateur: '. $value['realisateur'] .'</p>
                    <p class=\'duree\'>Durée: '. $value['duree'] .'</p>
                    <p class=\'genre\'>Genre: '. $value['genre'] .'</p>
                    <p class=\'synopsis\'>Synopsis: '. $value['synopsis'] .'</p>
                    <p class=\'bandeannonce\'>Bande-annonce: '. $value['bandeannonce'] .'</p>
                    </div>';
                    
                }    
            }
            
        }   
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
