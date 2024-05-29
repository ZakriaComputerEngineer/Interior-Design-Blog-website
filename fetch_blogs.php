<?php
include 'db_connection.php';

// Check if a specific blog_id is requested
if (isset($_GET['blog_id'])) {
    $blog_id = intval($_GET['blog_id']);
    $sql = "SELECT * FROM BLOGS WHERE blog_id = $blog_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $blog = mysqli_fetch_assoc($result);
        echo json_encode($blog);
    } else {
        echo json_encode(null);
    }
} else {
    // Check if sort parameter is provided
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

    // Set SQL query based on sort parameter
    if ($sort == 'topRated') {
        $sql = "SELECT * FROM BLOGS ORDER BY (upvotes - downvotes) DESC, date DESC";
    } else {
        $sql = "SELECT * FROM BLOGS ORDER BY date DESC";
    }

    $result = mysqli_query($conn, $sql);
    $blogs = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $blogs[] = $row;
        }
    }

    // Return blogs in JSON format
    echo json_encode($blogs);
}

mysqli_close($conn);
?>