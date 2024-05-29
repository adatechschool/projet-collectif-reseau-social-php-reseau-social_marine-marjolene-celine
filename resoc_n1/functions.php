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
        <address>par <?php echo $post["author_name"] ?></address>
        <div>
            <p>
                <?php
                echo $post["content"];
                ?>
            </p>
        </div>
        <footer>
            <small>♥ <?php echo $post['like_number'] ?> </small>
            <?php
            $tags_array = explode(",", $post['taglist']);
            foreach ($tags_array as $tag) {
            ?>
                <a><?php echo "#" . $tag . "," ?></a>

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
        <h3><?php echo $user["alias"] ?></h3>
        <p>id:<?php echo $user["id"] ?></p>
    </article>
<?php
}

?>