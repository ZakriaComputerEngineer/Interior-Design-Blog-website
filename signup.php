<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // Include database connection file
    include 'db_connection.php';

    // Escape user inputs for security
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        // Passwords do not match, redirect back to signup page with error message
        header("Location: signup.html?error=password_mismatch");
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $sql_check = "SELECT * FROM users WHERE username='$username'";
    $result_check = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($result_check) > 0) {
        // Username already exists, redirect back to signup page with error message
        header("Location: signup.html?error=username_exists");
        exit();
    } else {
        // Insert user data into database
        $sql_insert = "INSERT INTO users (username, role, gender, age, qualification, password) VALUES ('$username', '$role', '$gender', '$age', '$qualification', '$hashed_password')";
        if (mysqli_query($conn, $sql_insert)) {
            // User registered successfully, redirect to login page
            header("Location: login.html?signup=success");
            exit();
        } else {
            // Error inserting user data, redirect back to signup page with error message
            header("Location: signup.html?error=sql_error");
            exit();
        }
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Redirect back to signup page if accessed directly
    header("Location: signup.html");
    exit();
}
?>