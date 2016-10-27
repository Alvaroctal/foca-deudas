<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    $statement = $db->prepare('SELECT id, rank, passwd, salt FROM users WHERE username = :username');
    $statement->execute(array(':name' => $_POST['username']));
    $row = $statement->fetch();

    if (isset($row['id']) && $row['passwd'] == hash("sha256", $_POST['passwd'] . $row['salt'])) {
        $_SESSION['user'] = (int) $row['id'];
        $_SESSION['rank'] = $row['rank'];

        $output['status'] = 1;
    } else {
        $output['code'] = 'no-login';
        $output['result'] = 'Username or password are incorrect';
    }
?>