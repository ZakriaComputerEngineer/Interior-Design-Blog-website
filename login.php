<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) {
    include 'db_connection.php';

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql_check = "SELECT * FROM USERS WHERE username='$username'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $user = mysqli_fetch_assoc($result_check);

        if (password_verify($password, $user['password'])) {
            $userStatus = [
                'logged_in' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username']
                ],
                'blogs' => []
            ];
            file_put_contents('user_status.json', json_encode($userStatus));

            header("Location: index.html");
            exit();
        } else {
            echo '<p class="error">Incorrect username or password</p>';
        }
    } else {
        echo '<p class="error">Incorrect username or password</p>';
    }

    mysqli_close($conn);
} else {
    echo "NO CONN TO DB";
    exit();
}
?>