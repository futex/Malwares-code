<?php
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ($_FILES["file"]["error"] > 0){
    echo "Error";
}else{
    $newfile = uniqid("image_").".".$extension;
    move_uploaded_file($_FILES["file"]["tmp_name"], "Images/" . $_FILES["file"]["name"]);
}
?>