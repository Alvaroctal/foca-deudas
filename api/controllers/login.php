<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    $statement = $database->prepare('SELECT id, username, rank, passwd, salt FROM users WHERE username = ?');
    $statement->bind_param('s', $_POST['username']);
    $statement->execute();
    $row = $statement->get_result()->fetch_assoc();

    if (isset($row['id']) && $row['passwd'] == hash("sha256", $_POST['passwd'] . $row['salt'])) {
        $_SESSION['user'] = (int) $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['rank'] = $row['rank'];

        $output['status'] = 1;
    } else {
        $output['code'] = 'no-login';
        $output['result'] = 'Username or password are incorrect';
    }
?>