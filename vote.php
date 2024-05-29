<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection file
    include 'db_connection.php';

    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Extract variables
    $blogId = mysqli_real_escape_string($conn, $input['blogId']);
    $userId = mysqli_real_escape_string($conn, $input['userId']);
    $voteType = mysqli_real_escape_string($conn, $input['voteType']);

    // Fetch current vote for the user
    $sql_fetch_vote = "SELECT vote_type FROM USER_VOTES WHERE blog_id='$blogId' AND user_id='$userId'";
    $result_fetch_vote = mysqli_query($conn, $sql_fetch_vote);
    $currentVote = mysqli_fetch_assoc($result_fetch_vote);

    if ($currentVote) {
        // If user has already voted
        if ($currentVote['vote_type'] !== $voteType) {
            // Update their vote
            $sql_update_vote = "UPDATE USER_VOTES SET vote_type='$voteType' WHERE blog_id='$blogId' AND user_id='$userId'";
            mysqli_query($conn, $sql_update_vote);

            if ($currentVote['vote_type'] === 'upvote') {
                $sql_update_counts = "UPDATE BLOGS SET upvotes = upvotes - 1, downvotes = downvotes + 1 WHERE blog_id='$blogId' AND user_id='$userId'";
            } else {
                $sql_update_counts = "UPDATE BLOGS SET upvotes = upvotes + 1, downvotes = downvotes - 1 WHERE blog_id='$blogId' AND user_id='$userId'";
            }
            mysqli_query($conn, $sql_update_counts);
        }
    } else {
        // If user has not voted yet, insert their vote
        $sql_insert_vote = "INSERT INTO USER_VOTES (blog_id, user_id, vote_type) VALUES ('$blogId', '$userId', '$voteType')";
        mysqli_query($conn, $sql_insert_vote);

        if ($voteType === 'upvote') {
            $sql_update_counts = "UPDATE BLOGS SET upvotes = upvotes + 1 WHERE blog_id='$blogId' AND user_id='$userId'";
        } else {
            $sql_update_counts = "UPDATE BLOGS SET downvotes = downvotes + 1 WHERE blog_id='$blogId' AND user_id='$userId'";
        }
        mysqli_query($conn, $sql_update_counts);
    }

    // Get updated vote counts
    $sql_fetch_counts = "SELECT upvotes, downvotes FROM BLOGS WHERE blog_id='$blogId' AND user_id='$userId'";
    $result_fetch_counts = mysqli_query($conn, $sql_fetch_counts);
    $counts = mysqli_fetch_assoc($result_fetch_counts);

    // Return updated counts as JSON response
    echo json_encode([
        'success' => true,
        'upvoteCount' => $counts['upvotes'],
        'downvoteCount' => $counts['downvotes'],
    ]);

    // Close connection
    mysqli_close($conn);
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>