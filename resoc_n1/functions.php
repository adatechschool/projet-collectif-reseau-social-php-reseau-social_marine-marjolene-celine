<?php

function connectToDB()
{
    return new mysqli("localhost", "root", "", "socialnetwork");
}

function getFrenchMonth($month)
{

    $arrayMonths = array(
        "January" => "janvier",
        "February" => "février",
        "March" => "mars",
        "April" => "avril",
        "May" => "mai",
        "June" => "juin",
        "July" => "juillet",
        "August" => "août",
        "September" => "septembre",
        "October" => "octobre",
        "November" => "novembre",
        "December" => "décembre"
    );

    return $arrayMonths[$month];
}


function displayPost($post)
{
    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
    $likeEnCours = isset($_POST['post_' . $post['post_id']]);


    if ($likeEnCours) {
        // Etape 2: récupérer ce qu'il y a dans le formulaire
        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
        //echo "<pre> user_id = " . $_SESSION['connected_id'] . "</pre>";
        $new_post_id = $_POST['post_' . $post['post_id']];
        $new_user_id = $_SESSION['connected_id'];

        //Etape 3 : Ouvrir une connexion avec la base de donnée
        $mysqli = new mysqli("localhost", "root", "", "socialnetwork");

        //Etape 4 : sécurité contre injection
        $new_post_id = $mysqli->real_escape_string($new_post_id);
        $new_user_id = $mysqli->real_escape_string($new_user_id);

        //Etape 7 : verification like existant
        $verification_sql = "SELECT * FROM likes
        WHERE post_id = " . $new_post_id
            . " AND user_id = " . $new_user_id . ";";

        $verification = $mysqli->query($verification_sql);
        if ($verification->num_rows != 0) {
            echo "<pre>Vous avez déja laissé un Like</pre>";
        } else {
            //Etape 6 : ajout du like
            $ajout_sql = "INSERT INTO likes (id, user_id, post_id) "
                . "VALUES (NULL, "
                . "'" . $new_user_id . "', "
                . "'" . $new_post_id . "');";


            $insertion = $mysqli->query($ajout_sql);
            if (!$insertion) {
                echo "Vous n'avez pas réussi à mettre un Like : " . $mysqli->error;
            } else {
                unset($_POST['post_' . $post['post_id']]);
            }

            //effacer la variable dans $_POST
            $_POST = array();
        };
    };
    //echo "<pre> post_id = " . $post['post_id'] . "</pre>";
?>
    <article>
        <h3>
            <time datetime=<?php $post["created"] ?>>
                <?php
                $timeStamp = strtotime($post['created']); //convertit la date récupérée en timestamp Unix 
                $englishMonth = date("F", $timeStamp); // récupère le mois en anglais
                $frenchMonth = getFrenchMonth($englishMonth);
                $completeDate = date("d ", $timeStamp) . $frenchMonth . date(" Y à H\hi", $timeStamp);
                echo $completeDate;

                ?>
            </time>
        </h3>
        <address>
            par
            <a href=<?php echo "/resoc_n1/wall.php?user_id=" . $post["author_id"] ?>><?php echo $post["author_name"] ?></a>
        </address>
        <div>
            <p>
                <?php
                echo $post["content"];
                ?>
            </p>
        </div>
        <footer>
            <small class="like">
                <p>♥<?php echo $post['like_number'] ?></p>
                <form method="post">
                    <input type='hidden' name=<?php echo "post_" . $post['post_id'] ?> value=<?php echo $post['post_id'] ?>>
                    <input type="submit" value="Like">
                </form>
            </small>

            <?php
            $tags_array = explode(",", $post['taglist']);
            $tagids_array = explode(",", $post['tagid_list']);
            $post_tags = array_combine($tags_array, $tagids_array);
            //print_r($post_tags);
            foreach ($post_tags as $tag => $tagid) {
            ?>
                <a href=<?php echo "/resoc_n1/tags.php?tag_id=" . $tagid ?>><?php echo "#" . $tag . "," ?>
                </a>

            <?php
            }
            ?>
        </footer>
    </article>
<?php }

function displayUserFollow($user)
{
?>
    <article>
        <img src="user.jpg" alt="blason" />
        <a href=<?php echo "/resoc_n1/wall.php?user_id=" . $user["id"] ?>>
            <h3><?php echo $user["alias"] ?></h3>
        </a>

        <p>id:<?php echo $user["id"] ?></p>
    </article>
<?php
}

?>