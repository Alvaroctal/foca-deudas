<?php if (!$core->hasPermission('user.create')) return false;

foreach (['username', 'passwd', 'rank'] as $field) {
    if (! (isset($_POST[$field]) && $_POST[$field] != '')) { $error = $field; break; }
} if (!isset($error)) {
    if ((!isset($_POST['email'])) || isset($_POST['email']) && ($_POST['email'] == '' || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        if (isset($_POST['passwd']) && strlen($_POST['passwd']) >= 8) {

            $database = $core->getDatabase();
            
            $salt = substr(md5(uniqid(rand(), true)), 0, 8);
            $passwd = hash('sha256', $_POST['passwd'] . $salt);
            $sentencia = $database->prepare('INSERT INTO users (username, email, paypal, passwd, salt) VALUES (:username, :email, :paypal, :passwd, :salt)');

            $sentencia->bindParam(':username', $_POST['username']);
            $sentencia->bindValue(':email', isset($_POST['email']) && $_POST['email'] != '' ?  $_POST['email'] : null);
            $sentencia->bindValue(':paypal', isset($_POST['paypal']) && $_POST['paypal'] != '' ?  $_POST['paypal'] : null);
            $sentencia->bindParam(':passwd', $passwd);
            $sentencia->bindParam(':salt', $salt);

            if ($sentencia->execute()) {
                $output['status'] = 1;
            } else {
                $errorInfo = $sentencia->errorInfo();
                if ($errorInfo[1] == 1062) $output['code'] = 'duplicate';
                else $output['code'] = 'no-user';
                if ($core->isDebug()) $output['debug'] = $errorInfo;
            }
        } else $output['code'] = 'no-passwd';
    } else $output['code'] = 'no-email';
} else { $output['code'] = 'no-input'; $output['return'] = $error; }