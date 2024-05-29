<?php
include 'db_connection.php';

// Get blog_id from the query parameter
$blog_id = intval($_GET['blog_id']);

// Delete rows from user_votes and user_comments
$deleteVotes = "DELETE FROM USER_VOTES WHERE blog_id=$blog_id";
$deleteComments = "DELETE FROM USER_COMMENTS WHERE blog_id=$blog_id";
$deleteBlog = "DELETE FROM BLOGS WHERE blog_id=$blog_id";

if (mysqli_query($conn, $deleteVotes) && mysqli_query($conn, $deleteComments) && mysqli_query($conn, $deleteBlog)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>