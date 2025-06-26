<?php
$target_dir = "uploads/";

// Ensure the uploads folder exists
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$target_file = $target_dir . basename($_FILES["image"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$uploadOk = 1;

// Check if image is actual image
if(isset($_POST["submit"]) || isset($_FILES["image"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Allow only specific image formats
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if(!in_array($imageFileType, $allowed)) {
    echo "Sorry, only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
    $uploadOk = 0;
}

// Check file size (5MB limit)
if ($_FILES["image"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Upload if no errors
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>