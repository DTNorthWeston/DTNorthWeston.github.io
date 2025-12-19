<?php
session_start();

$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$_SESSION['messages'] = [];

foreach ($_FILES['images']['name'] as $index => $name) {
    $tmp_name = $_FILES['images']['tmp_name'][$index];
    $size = $_FILES['images']['size'][$index];
    $error = $_FILES['images']['error'][$index];

    if ($error !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.',
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];
    $message = $errorMessages[$error] ?? "Unknown upload error ($error)";
    $_SESSION['messages'][] = "Error uploading $name: $message";
    continue;
}

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $_SESSION['messages'][] = "Invalid file type: $name.";
        continue;
    }

    if ($size > 5 * 1024 * 1024) {
        $_SESSION['messages'][] = "File too large: $name.";
        continue;
    }

    $new_name = uniqid("img_", true) . ".$ext";
    $destination = $target_dir . $new_name;

    if (move_uploaded_file($tmp_name, $destination)) {
        $_SESSION['messages'][] = "<b>Thank You!</b>";
    } else {
        $_SESSION['messages'][] = "Failed to save $name.";
    }
}

// Redirect back to the form
header("Location: Upload_Page.php");
exit;