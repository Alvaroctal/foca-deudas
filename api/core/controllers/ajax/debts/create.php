<?php if (!$core->hasPermission('debt.create')) return false;

foreach (['name', 'quantity', 'description'] as $field) {
    if (! (isset($_POST[$field]) && $_POST[$field] != '')) { $error = $field; break; }
} if (!isset($error)) {

    $database = $core->getDatabase();
    $database->beginTransaction();

    $sentencia = $database->prepare('INSERT INTO debts (user, name, quantity, description) VALUES (:user, :name, :quantity, :description)');

    $sentencia->bindParam(':user', $session['id']);
    $sentencia->bindParam(':name', $_POST['name']);
    $sentencia->bindParam(':quantity', $_POST['quantity']);
    $sentencia->bindParam(':description', $_POST['description']);

    if ($sentencia->execute()) {

        $debt = $database->lastInsertId();
        $sentencia = $database->prepare('INSERT INTO user_debt (debt, user, quantity, state) VALUES (:debt, :user, :quantity, :state)');

        foreach ($_POST as $name => $value) if (preg_match('/^user-(\d+)$/', $name, $matches) && isset($_POST[$name]) && $_POST[$name] > 0) {
            $sentencia->bindParam(':debt', $debt);
            $sentencia->bindParam(':user', $matches[1]);
            $sentencia->bindParam(':quantity', $_POST[$name]);
            $sentencia->bindValue(':state', $matches[1] == $session['id'] ? 'confirmed' : 'pending');
            if (!$sentencia->execute()) $error = $matches[1];
        } if (!isset($error)) {
            $database->commit();
            $output['status'] = 1;

            $telegram = new Longman\TelegramBot\Telegram($environment['telegram']['token'], $environment['telegram']['name']);

            foreach($database->query("SELECT u.telegram, ud.quantity, IF(u2.telegram IS NULL, u2.username, CONCAT('@', u2.telegram_username)) as 'username' FROM user_debt ud JOIN users u on ud.user = u.id JOIN debts d ON ud.debt = d.id JOIN users u2 ON d.user = u2.id WHERE d.id = $debt AND u.telegram IS NOT NULL") as $user) Longman\TelegramBot\Request::sendMessage(['chat_id' => $user['telegram'], 'text' => $user['username'].' ha creado una deuda nueva de '.$user['quantity'].' € [Pagar](https://deudas.motherfocas.eu/panel/#/home)', 'parse_mode' => 'markdown']);
        } else {
            $database->rollback();
            $output['code'] = 'no-users';
            if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
        }
    } else {
        $output['code'] = 'no-debt';
        if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
    }
} else {
    $output['code'] = 'no-input'; $output['return'] = $error;
}