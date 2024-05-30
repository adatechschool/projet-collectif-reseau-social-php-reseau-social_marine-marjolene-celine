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

function displayContent($content) {
    $tag_pattern = '/#\w+/u';
    $new_content = preg_replace($tag_pattern,
    '<a href="/resoc_n1/tags.php?tag_id='
    ,
    $content);
    echo $new_content;
}

function displayPost($post)
{
    /* if (preg_match_all('/#\w+/u', $post['content'], $matches)) {
        print_r($matches);
    } */
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
                    displayContent($post["content"]);
                ?>
            </p>
        </div>
        <footer>
            <small>♥ <?php echo $post['like_number'] ?> </small>
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