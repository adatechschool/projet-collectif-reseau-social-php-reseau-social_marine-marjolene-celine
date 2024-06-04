<?php
include "./functions.php";
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    display_header();
    ?>
    <div id="wrapper">
        <?php
        /**
         * Etape 1: Le mur concerne un utilisateur en particulier
         * La première étape est donc de trouver quel est l'id de l'utilisateur
         * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
         * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
         * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
         */
        $userId = intval($_GET['user_id']);
        ?>
        <?php
        /**
         * Etape 2: se connecter à la base de donnée
         */
        $mysqli = connectToDB();
        ?>

        <aside>
            <?php
            /**
             * Etape 3: récupérer le nom de l'utilisateur
             */
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice :
                    <?php if ($user) {
                        echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                    <?php
                    }
                    if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] != $userId) {
                    ?>
                        <!-- Création du bouton gestion abonnement -->
                        <br>
                        <br>
                <form method="post">

                    <input type='hidden' name='follow' value="follow">
                    <button type="submit"> Abonne toi </button>
                </form>
            <?php
                    }

                    $enCoursDeTraitement = isset($_POST['follow']);
                    if ($enCoursDeTraitement) {
                        $following_id = $_SESSION['connected_id'];
                        $followed_id = $userId;

                        $mysqli = new mysqli("localhost", "root", "", "socialnetwork");

                        $following_id = $mysqli->real_escape_string($following_id);
                        $followed_id = $mysqli->real_escape_string($followed_id);

                        $lInstructionSql = "INSERT INTO followers (id, followed_user_id, following_user_id) "
                            . "VALUES (NULL, "
                            . "'" . $followed_id . "', "
                            . "'" . $following_id . "' "
                            . ");";

                        $ok = $mysqli->query($lInstructionSql);
                        if (!$ok) {
                            echo "l'abonnement a échoué " . $mysqli->error;
                        } else {
                            echo "Vous suivez à présent " . $user['alias'];
                        }
                    }
            ?>
 
            </p>

            </section>
        </aside>
        <main>
            <?php
            /**
             * Etape 3: récupérer tous les messages de l'utilisatrice
             */
            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id AS post_id,
                    users.alias as author_name,
                    users.id as author_id,
                    COUNT(DISTINCT likes.id) as like_number,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id ORDER BY tags.label) AS tagid_list
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             */
            while ($post = $lesInformations->fetch_assoc()) {
                displayPost($post);
            }

            ?>

        </main>
    </div>
</body>

</html>