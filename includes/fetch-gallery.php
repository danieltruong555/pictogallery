<?php
function fetchGallery($connection){
    // include 'db-header.php';
    $errorMessage = null;

    $supportedExt = ['png', 'jpg', 'jpeg'];

    $path = "./images/";
    $images = glob($path."*");
    foreach($images as $image){
        $split = explode('./', $image);
        $imageNameSplit =  explode('.', end($split));
        $imageExt = strtolower(end($imageNameSplit));
        $imageSize = filesize($image);
        $imageHash = md5_file($image);
        if(in_array($imageExt, $supportedExt)){
            if($imageSize < 20000){
                $query = "SELECT * FROM gallery WHERE filesize=? AND md5hash=?";
                $statement = mysqli_stmt_init($connection);
                if(mysqli_stmt_prepare($statement, $query)){
                    mysqli_stmt_bind_param($statement, "ss", $imageSize, $imageHash);
                    mysqli_stmt_execute($statement);
                    $result = mysqli_stmt_get_result($statement);
                    if($row=mysqli_fetch_assoc($result)){
                        continue; //duplicate --> skip
                    }
                    $query = 'SELECT * FROM gallery;';
                    $statement = mysqli_stmt_init($connection);
                    if(mysqli_stmt_prepare($statement, $query)){
                        mysqli_stmt_execute($statement);
                        $result = mysqli_stmt_get_result($statement);
                        $numRows = mysqli_num_rows($result);
                        

                        $imageFullName = $imageNameSplit[0].uniqid('');
                        $imageFullNameSplit = explode('/', $imageFullName);
                        $imageActualName = end($imageFullNameSplit);
                        $imageDest = 'gallery/'.$imageActualName.".".$imageExt;
                        $imageDesc = "Nothing here...";
                        $imageRank = $numRows + 1;

                        $query = 'INSERT INTO gallery (title, description, filepath, rank, md5hash, filesize) VALUES (?, ?, ?, ?, ?, ?);';
                        $statement = mysqli_stmt_init($connection);
                        if(mysqli_stmt_prepare($statement, $query)){
                            mysqli_stmt_bind_param($statement, "ssssss", $imageActualName, $imageDesc, $imageDest, $imageRank, $imageHash, $imageSize);
                            mysqli_stmt_execute($statement);
                            
                            copy($image, './'.$imageDest);
                        }
                        else{
                            $errorMessage = 'Failed to insert '.$image.' into the database';
                        }
                        
                    }
                    else{
                        $errorMessage = 'Failed to prepare fetch statement!'; 
                    }
                }
                else{
                    $errorMessage = "Failed to prepare fetch statement!";
                }
            }
            else{
                $errorMessage = "Failed to upload image! ".$image.' is too big!';
                continue;
            }
        }
        else{
            $errorMessage = "Failed to upload image!".$imageExt.' is not supported!';
            continue;
        }
    }
    return $errorMessage;
}
//errorCode == 0 -> success
//header("Location:../index.php?errorcode=".$errorCode); 
?>