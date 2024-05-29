<?php
header('Content-Type: application/json');

include 'db_connection.php';

// Get user_id from the query parameter
$user_id = $_GET['user_id'];

$sql = "SELECT * FROM BLOGS WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);

$blogs = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row['images'] = json_decode($row['images']);
        $row['comments'] = json_decode($row['comments']);
        $blogs[] = $row;
    }
    echo json_encode(['blogs' => $blogs]);
} else {
    echo json_encode(['blogs' => []]);
}

mysqli_close($conn);
?>