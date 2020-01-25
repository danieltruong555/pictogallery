<?php
include 'includes/fetch-gallery.php';
include 'header.html';
include_once 'includes/db-header.php';

if(!(isset($_GET["modify"]) || isset($_GET["delete"]))){
    $errorMessage = fetchGallery($connection);
    if(!is_null($errorMessage)){
        echo $errorMessage;
        exit();
    }
}

$query = "SELECT * FROM  gallery ORDER by rank DESC;";
$statement = mysqli_stmt_init($connection);

if(mysqli_stmt_prepare($statement, $query)){
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
}
else{
    echo "Failed to prepare fetch statement!";
    exit();
}

?>
<section class="gallery">
    <h1>Gallery</h1>
    <div class="row">
        <?php
            while($row=mysqli_fetch_assoc($result)){
                echo '<div class="col"> 
                    <a href="#">
                    <img src="'.$row["filepath"].'">
                    </a>
                    <h2>'.$row["title"].'</h2>
                    <p>'.$row["description"].'</p>
                    <form method="get">
                    <input hidden type="hidden" name="id" value="'.$row["id"].'">
                    <input class="gallery-btn" type="submit" value="Modify Image" formaction="modify.php"><br>
                    <input class="gallery-btn" type="submit" value="Delete Image" formaction="includes/delete-image.php">
                    </form>
                    </div>';
            }
        ?>
    </div>
</section>

<?php
include "footer.html";
?>

