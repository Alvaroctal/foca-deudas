<?php if (!$core->hasPermission('debt.read')) return false;

$database = $core->getDatabase();
$sentencia = $database->prepare("SELECT d.id, d.user, u.username, d.name, d.quantity, d.description, GROUP_CONCAT(ud.user) as 'users', GROUP_CONCAT(COALESCE(ud.quantity, '')) as 'quantities', GROUP_CONCAT(ud.state) as 'states', GROUP_CONCAT(ud.method) as 'methods', d.time, d.updateTime FROM debts d JOIN users u ON d.user = u.id LEFT JOIN user_debt ud ON ud.debt = d.id GROUP BY d.id");

if ($sentencia->execute()) {
    while ($row = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        $users = explode(',', $row['users']);
        $quantities = explode(',', $row['quantities']);
        $states = explode(',', $row['states']);
        $methods = explode(',', $row['methods']);
        unset($row['quantities'], $row['states'], $row['methods']); $row['users'] = array();

        for ($i = 0; $i < count($users); $i++) {
            $row['users'][] = array('user' => $users[$i], 'quantity' => $quantities[$i], 'state' => $states[$i], 'method' => $methods[$i]);
        } $output['data'][] = $row; 
    } $output['status'] = 1;
} else {
    $output['code'] = 'no-sentence';
    if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
}