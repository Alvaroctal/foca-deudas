<?php if (!$core->hasPermission('debt.create')) return false;

foreach (['name', 'quantity', 'description'] as $field) {
    if (! (isset($_POST[$field]) && $_POST[$field] != '')) { $error = $field; break; }
} if (!isset($error)) {

    $database = $core->getDatabase();
    $database->beginTransaction();

    $sentencia = $database->prepare('UPDATE debts SET name = :name, quantity = :quantity, description = :description) WHERE id = :id AND (user = :user OR 1 = :permission)');

    $sentencia->bindParam(':id', $_POST['id']);
    $sentencia->bindParam(':user', $session['id']);
    $sentencia->bindParam(':name', $_POST['name']);
    $sentencia->bindParam(':quantity', $_POST['quantity']);
    $sentencia->bindParam(':description', $_POST['description']);
    $sentencia->bindValue(':permission', (int) $core->hasPermission('*'));

    if ($sentencia->execute() && $sentencia->rowCount() == 1) {

        $sentencia = $database->prepare('DELETE FROM user_debt WHERE debt = :debt');
        $sentencia->bindParam(':debt', $_POST['id']);
        $sentencia->execute();

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