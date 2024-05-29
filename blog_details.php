<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if blog_id and user_id are provided
if (isset($_GET['blog_id']) && isset($_GET['user_id'])) {
    // Include database connection file
    include 'db_connection.php';

    // Escape user inputs for security
    $blogId = mysqli_real_escape_string($conn, $_GET['blog_id']);
    $userId = mysqli_real_escape_string($conn, $_GET['user_id']);

    // Query to fetch blog details
    $sql = "SELECT * FROM BLOGS WHERE blog_id='$blogId' AND user_id='$userId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $blog = mysqli_fetch_assoc($result);
        // Return blog details as JSON response
        echo json_encode([

            'date' => $blog['date'],

            'images' => json_decode($blog['images']),
            'upvoteCount' => $blog['upvotes'], // Add upvote count to the response
            'downvoteCount' => $blog['downvotes'], // Add downvote count to the response
            // You can also add user's vote status here if needed
        ]);
    } else {
        // No blog found with the provided IDs
        echo json_encode(['error' => 'Blog not found']);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Error: blog_id or user_id not provided
    echo json_encode(['error' => 'Blog ID or User ID not provided']);
}
?>