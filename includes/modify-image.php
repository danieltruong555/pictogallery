<?php
include_once "db-header.php";

if(!empty($_GET["title"])){
    $imageTitle = $_GET["title"];
}
else{
    $query = "SELECT * FROM gallery WHERE id=".$_GET["id"];
    $statement = mysqli_stmt_init($connection);
        if(mysqli_stmt_prepare($statement, $query)){
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $row = mysqli_fetch_assoc($result);
            $imageTitle = $row["title"];
        }
        else{
            echo "Failed to prepare fetch statement!";
            exit();
        }
}
if(!empty($_GET["title"])){
    $imageDesc = $_GET["desc"];
}
else{
    $imageDesc = "Nothing here...";
}


$query = "UPDATE gallery SET title=?, description=? WHERE id=".$_GET["id"];

$statement = mysqli_stmt_init($connection);

if(mysqli_stmt_prepare($statement, $query)){
    mysqli_stmt_bind_param($statement, "ss", $imageTitle, $imageDesc);
    mysqli_stmt_execute($statement);
    
    header("Location:../index.php?modify&success=1");

}
else{
    echo "Failed to prepare update statement!";
    exit();
}

?>