<?php if (!$core->hasPermission('debt.pay')) return false;
    
$database = $core->getDatabase();
$sentencia = $database->prepare("UPDATE user_debt SET state = 'paid', method = :method WHERE user = :user AND debt = :debt AND state IN ('pending', 'rejected')");

$sentencia->bindParam(':user', $session['id']);
$sentencia->bindParam(':debt', $_POST['debt']);
$sentencia->bindParam(':method', $_POST['method']);

if ($sentencia->execute() && $sentencia->rowCount() == 1) {
    $output['status'] = 1;
    $core->log('users', 'Payed debt ('.$_POST['debt'].') with method ('.$_POST['method'].')');

    $telegram = new Longman\TelegramBot\Telegram($environment['telegram']['token'], $environment['telegram']['name']);

    if ($user = $database->query("SELECT u2.telegram, ud.quantity, IF(u.telegram IS NULL, u.username, CONCAT('@', u2.telegram_username)) as 'username' FROM user_debt ud JOIN users u on ud.user = u.id JOIN debts d ON ud.debt = d.id JOIN users u2 ON d.user = u2.id WHERE ud.debt = {$_POST['debt']} AND ud.user = {$session['id']} AND u2.telegram IS NOT NULL", PDO::FETCH_ASSOC)->fetch()) Longman\TelegramBot\Request::sendMessage(['chat_id' => $user['telegram'], 'text' => $user['username'].' ha pagado su deuda contigo de '.$user['quantity'].' € [Confirmar](https://deudas.motherfocas.eu/panel/#/home)', 'parse_mode' => 'markdown']);
} else {
    $output['code'] = 'no-debt';
    $output['return'] = 'Unable to find debt';
    $output['debug'] = $sentencia->errorInfo();
}