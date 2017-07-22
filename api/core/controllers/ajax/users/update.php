<?php if (!($core->hasPermission('user.update') || $_POST['id'] == $session['id'])) return false;

foreach (['username', 'rank'] as $field) {
    if (! (isset($_POST[$field]) && $_POST[$field] != '')) { $error = $field; break; }
} if (!isset($error)) {
    if (isset($_POST['email']) && ($_POST['email'] == '' || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        if (!(isset($_POST['passwd']) && $_POST['passwd'] != '') || $changePasswd = strlen($_POST['passwd']) >= 8) {

            $database = $core->getDatabase();
            
            $sentencia = $database->prepare('UPDATE users SET username = :username, email = :email, paypal = :paypal '.(isset($changePasswd) && $changePasswd ? ', passwd = :passwd, salt = :salt' : '').' WHERE id = :id');

            $sentencia->bindParam(':id', $_POST['id']);
            $sentencia->bindParam(':username', $_POST['username']);
            $sentencia->bindValue(':email', isset($_POST['email']) && $_POST['email'] != '' ?  $_POST['email'] : null);
            $sentencia->bindValue(':paypal', isset($_POST['paypal']) && $_POST['paypal'] != '' ?  $_POST['paypal'] : null);
            
            if (isset($changePasswd)) { 
                $salt = substr(md5(uniqid(rand(), true)), 0, 8);
                $passwd = hash('sha256', $_POST['passwd'] . $salt);
                $sentencia->bindParam(':passwd', $passwd); 
                $sentencia->bindParam(':salt', $salt); 
            }

            if ($sentencia->execute() && $sentencia->rowCount() == 1) {
                $output['status'] = 1;
            } else {
                $errorInfo = $sentencia->errorInfo();
                if ($errorInfo[1] == 1062) $output['code'] = 'duplicate';
                else $output['code'] = 'no-user';
                if ($core->isDebug() || true) $output['debug'] = $errorInfo;
            }
        } else $output['code'] = 'no-passwd';
    } else $output['code'] = 'no-email';
} else { $output['code'] = 'no-input'; $output['return'] = $error; }