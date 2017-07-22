<?php

if (isset($_POST['email'])) $_POST['user'] = $_POST['email'];

if (isset($_POST['username']) && $_POST['username'] != '') {

    $sentencia = $core->getDatabase()->prepare("SELECT u.id, u.passwd, u.salt, u.rank, GROUP_CONCAT(DISTINCT p.permission) as 'permissions' FROM users u LEFT JOIN permissions p ON u.rank = p.rank WHERE u.username = :username GROUP BY u.id LIMIT 1");

    $sentencia->bindParam(':username', $_POST['username']);

    if ($sentencia->execute()) {

        $row = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (isset($_POST['passwd']) && isset($row) && $row['passwd'] == hash('sha256', $_POST['passwd'] . $row['salt'])) {
            $output['status'] = 1;
            $output['data'] = array('token' => \Firebase\JWT\JWT::encode(array('id' => $row['id'], 'username' => $_POST['username'], 'rank' => $row['rank'],'permissions' => explode(',', $row['permissions']), 'version' => $environment['jwt']['version'], 'time' => time()), $environment['jwt']['key']));
            $core->log('access', 'Login user ('.$row['id'].') logged in from ip ('.$_SERVER['REMOTE_ADDR'].')');
        } else {
            $output['code'] = 'no-login';
        }
    } else {
        $output['code'] = 'no-sentence';
        if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
    }
} else {
    $output['code'] = 'no-username';
}