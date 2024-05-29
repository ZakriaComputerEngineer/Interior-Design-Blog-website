<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['blog_id'])) {
    // Fetch comments for a blog
    $blogId = mysqli_real_escape_string($conn, $_GET['blog_id']);
    $sql = "SELECT USER_COMMENTS.comment, USERS.username, USER_COMMENTS.date 
            FROM USER_COMMENTS 
            JOIN USERS ON USER_COMMENTS.user_id = USERS.id 
            WHERE blog_id='$blogId' 
            ORDER BY USER_COMMENTS.date DESC";
    $result = mysqli_query($conn, $sql);
    $comments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }
    echo json_encode($comments);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);

    // Check if data is provided
    if (isset($data['blogId']) && isset($data['comment'])) {
        // Get user status
        $userStatus = json_decode(file_get_contents('user_status.json'), true);

        if ($userStatus['logged_in']) {
            $blogId = mysqli_real_escape_string($conn, $data['blogId']);
            $userId = mysqli_real_escape_string($conn, $userStatus['user']['id']);
            $comment = mysqli_real_escape_string($conn, $data['comment']);

            // Insert comment into database
            $sql = "INSERT INTO user_comments (blog_id, user_id, comment) VALUES ('$blogId', '$userId', '$comment')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'User not logged in']);
        }
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

// Close connection
mysqli_close($conn);
?>