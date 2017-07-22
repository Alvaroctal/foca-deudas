<?php if (!$core->hasPermission('debt.resolve')) return false;
    
$database = $core->getDatabase();
$sentencia = $database->prepare("UPDATE user_debt ud JOIN debts d ON ud.debt = d.id SET ud.state = :state WHERE d.user = :owner AND ud.debt = :debt AND ud.user = :user");

$sentencia->bindParam(':owner', $session['id']);
$sentencia->bindParam(':debt', $_POST['debt']);
$sentencia->bindParam(':user', $_POST['user']);
$sentencia->bindParam(':state', $_POST['state']);

if ($sentencia->execute() && $sentencia->rowCount() == 1) {
    $output['status'] = 1;
    $core->log('users', 'Resolved debt ('.$_POST['debt'].') for user ('.$_POST['user'].') with state ('.$_POST['state'].')');

    $telegram = new Longman\TelegramBot\Telegram($environment['telegram']['token'], $environment['telegram']['name']);
    
    if ($user = $database->query("SELECT u.telegram, ud.quantity, IF(u2.telegram IS NULL, u2.username, CONCAT('@', u2.telegram_username)) as 'username' FROM user_debt ud JOIN users u on ud.user = u.id JOIN debts d ON ud.debt = d.id JOIN users u2 ON d.user = u2.id WHERE ud.debt = {$_POST['debt']} AND ud.user = {$_POST['user']} AND u.telegram IS NOT NULL", PDO::FETCH_ASSOC)->fetch()) Longman\TelegramBot\Request::sendMessage(['chat_id' => $user['telegram'], 'text' => $user['username'].' ha '.($_POST['state'] == 'confirmed' ? 'confirmado' : 'rechazado').' su deuda contigo', 'parse_mode' => 'markdown']);
} else {
    $output['code'] = 'no-debt';
    $output['return'] = 'Unable to find debt';
}