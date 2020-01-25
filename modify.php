
<?php
include 'header.html';
include_once 'includes/db-header.php';

$query = "SELECT * FROM  gallery where id=".$_GET["id"];
$statement = mysqli_stmt_init($connection);

if(mysqli_stmt_prepare($statement, $query)){
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($result);
}
else{
    echo "Failed to prepare fetch statement!";
    exit();
}
?>

<section class="modify">
    <?php
    echo '<img src="'.$row["filepath"].'">
    <form action="includes/modify-image.php" method="get">
        <input hidden type="hidden" name="id" value="'.$row["id"].'">';
    ?>
        <fieldset>
            <h1>Title:</h1> <textarea name="title"  placeholder="Enter new Header here." maxlength="80" rows="2" cols="50"></textarea><br>
            <h1>Description:</h1> <textarea name="desc"  placeholder="Enter new Description here." maxlength="200" rows="6" cols="50"></textarea><br>
            <input type="submit" value="Submit">
    </form>
</section>

<?php
include 'footer.html';
?>