<?php
include_once "db-header.php";

$query = "SELECT * FROM gallery WHERE id=".$_GET["id"];
$statement = mysqli_stmt_init($connection);

if(mysqli_stmt_prepare($statement,$query)){
    mysqli_stmt_execute($statement);
    $results = mysqli_stmt_get_result($statement);
    if($row = mysqli_fetch_assoc($results)){
        $filepath = $row["filepath"];
        $query = "DELETE FROM gallery WHERE id=".$_GET["id"];
        $statement = mysqli_stmt_init($connection);

        if(mysqli_stmt_prepare($statement,$query)){
            mysqli_stmt_execute($statement);
            if(unlink("../".$filepath)){
                header("Location:../index.php?delete&success=1");
            }
            else{
                echo "Failed to delete image!";
                exit();
            }
        }
        else{
            echo "Failed to prepare delete statement!";
            exit();
            
        }
    }
    else{
        echo "Failed to retrieve image!";
        exit();
    }
}
else{
    echo "Failed to prepare fetch statement!";
    exit();
}
?>