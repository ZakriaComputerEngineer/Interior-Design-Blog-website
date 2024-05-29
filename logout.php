<?php
$userStatus = [
    'logged_in' => false,
    'user' => [
        'id' => null,
        'username' => ''
    ],
    'blogs' => []
];

file_put_contents('user_status.json', json_encode($userStatus));

echo json_encode(['logged_out' => true]);
?>