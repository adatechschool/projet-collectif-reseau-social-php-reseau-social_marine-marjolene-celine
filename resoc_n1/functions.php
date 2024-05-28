<?php
function connectToDB()
{
    return new mysqli("localhost", "root", "", "socialnetwork");
}

function displayPost($post)
{
?>
    <article>
        <h3>
            <time datetime=<?php $post["created"] ?>>
                <?php
                setlocale(LC_TIME, "fr_FR.UTF-8", 'fra');
                echo strftime("%d %B %Y à %Hh%M", strtotime($post['created']));
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