<?php if (!$core->hasPermission('debt.read')) return false;

$database = $core->getDatabase();
$sentencia = $database->prepare("SELECT d.id, d.user, d.name, d.quantity, d.description, d.time, d.updateTime FROM debts d WHERE d.id = :id");

$sentencia->bindParam(':id', $match['params']['id']);

if ($sentencia->execute()) {
    if ($output['data'] = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        $output['status'] = 1;
        $output['data']['users'] = $database->query("SELECT ud.user, u.username, ud.quantity, ud.state, ud.method, ud.notes FROM user_debt ud JOIN users u ON ud.user = u.id WHERE ud.debt = {$output['data']['id']}", PDO::FETCH_ASSOC)->fetchAll();
    } else $output['code'] = 'no-debt';
} else {
    $output['code'] = 'no-sentence';
    if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
}