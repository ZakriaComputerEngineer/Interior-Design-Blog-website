<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

$userStatus = json_decode(file_get_contents('user_status.json'), true);
if (!$userStatus['logged_in']) {
    echo "User not logged in.";
    exit();
}

$user_id = $userStatus['user']['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadedImages = [];

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
            $targetFile = $targetDir . basename($_FILES['images']['name'][$i]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                exit;
            }

            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                $uploadedImages[] = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit();
            }
        }
    }

    $imagesJson = json_encode($uploadedImages);

    $sql = "INSERT INTO BLOGS (date, user_id, images, upvotes, downvotes, comments) VALUES (NOW(), '$user_id', '$imagesJson', 0, 0, '[]')";
    if (mysqli_query($conn, $sql)) {
        header("Location: profile.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>