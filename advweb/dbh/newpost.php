<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location:../index.php');
}

if (!isset($_POST['desc'])) {
    header('Location:../home.php');
    die();
}


include('dbdata.php');
$con = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

$email = $_SESSION['user'];
$description = $con->real_escape_string($_POST['desc']);

$imgName  = "";


if (isset($_FILES["file"]["name"])) {
    $fileSize = $_FILES["file"]["size"]; // File size in bytes
    $fileName = $_FILES["file"]["name"]; // The file name
    $fileTmpLoc = $_FILES["file"]["tmp_name"]; // File in the PHP tmp folder
    $fileType = $_FILES["file"]["type"]; // The type of file it is
    $fileErrorMsg = $_FILES["file"]["error"]; // 0 for false... and 1 for true
    // Verify if valid image file

    $valid_image = false;
    $sizeLimit = 3 * 1024 * 1024;

    if ($fileSize >= $sizeLimit) {
        echo ("File size is larger than $sizeLimit MB");
        die;
    }

    if ($fileType != "image/jpeg" && $fileType != "image/png") {
        echo ("Only Images are allowed");
        die;
    }

    if (@is_array(getimagesize($_FILES["file"]["tmp_name"]))) {
        $valid_image = true;
    } else {
        echo ("Not a valid image");
        die;
    }
    list($width, $height, $type, $attr) = getimagesize($_FILES["file"]["tmp_name"]);
    if ($type != 2 && $type != 3) {
        echo ("Please make sure that your image is in the format JPEG or PNG");
        die;
    }
    $extension = ".jpg";
    if ($type == 3)
        $extension = ".png";

    $newFilename = 'user_post_' . uniqid() . $extension;

    if (!$fileTmpLoc) {
        echo ("Please select a file before clicking upload");
        die;
    }

    if (move_uploaded_file($fileTmpLoc, "../assets/img/userUploads/$newFilename")) {
        $imgName = $newFilename;
    }
} else {
    die("lol");
}

$sql = "INSERT INTO masks(description,email,image) VALUES ('$description','$email','$imgName')";
$result = $con->query($sql);
if ($result == TRUE) {
    header("Location:../home.php");
} else {
    header("Location:../home.php?failed");
}
$con->close();
