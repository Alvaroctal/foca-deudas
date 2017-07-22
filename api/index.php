<?php
/* =========================================================
 * Proyecto FocaDeudas
 * http://motherfocas.eu
 * =========================================================
 * All portions are Copyright © 2017 MotherFoca Industries
 * All Rights Reserved. 
 * Contributor(s):
 *  octal@motherfocas.eu
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

date_default_timezone_set('Europe/Madrid');

//--------------------------------------------------------------------------
// Cargar librerias
//--------------------------------------------------------------------------

require 'vendor/autoload.php';
require 'core/core.php';

//--------------------------------------------------------------------------
// Environment
//--------------------------------------------------------------------------

$environment = $core->getEnvironment();

//--------------------------------------------------------------------------
// JWT session
//--------------------------------------------------------------------------

if (isset($_REQUEST['token'])) {
    try {
        if (($session = \Firebase\JWT\JWT::decode($_REQUEST['token'], $environment['jwt']['key'], $environment['jwt']['algorithms'])) && isset($session->version) && $session->version == $environment['jwt']['version']) $core->setSession($session = (array) $session);
    } catch (Exception $e) { echo json_encode(['status' => 0, 'code' => 'no-session']); die; }
}

//--------------------------------------------------------------------------
// Router
//--------------------------------------------------------------------------

$relativePath = $core->getRelativePath();

$router = new AltoRouter();

$router->addRoutes(array(

    //--------------------------------------------------------------------------
    // Login
    //--------------------------------------------------------------------------
    
    array('GET', $relativePath.'/whoami', 'ajax:whoami'),

    array('POST', $relativePath.'/login', 'ajax:login'),
    
    //--------------------------------------------------------------------------
    // Users
    //--------------------------------------------------------------------------

    array('GET', $relativePath.'/users', 'ajax:users/list'),
    array('GET', $relativePath.'/users/[i:id]', 'ajax:users/get'),

    array('POST', $relativePath.'/users/create', 'ajax:users/create'),
    array('POST', $relativePath.'/users/update', 'ajax:users/update'),
    array('POST', $relativePath.'/users/delete', 'ajax:users/delete'),

    //--------------------------------------------------------------------------
    // Debts
    //--------------------------------------------------------------------------

    array('GET', $relativePath.'/debts', 'ajax:debts/list'),
    array('GET', $relativePath.'/debts/[i:id]', 'ajax:debts/get'),

    array('POST', $relativePath.'/debts/create', 'ajax:debts/create'),
    array('POST', $relativePath.'/debts/update', 'ajax:debts/update'),
    array('POST', $relativePath.'/debts/delete', 'ajax:debts/delete'),
    array('POST', $relativePath.'/debts/pay', 'ajax:debts/pay'),
    array('POST', $relativePath.'/debts/resolve', 'ajax:debts/resolve'),

    //--------------------------------------------------------------------------
    // Telegram
    //--------------------------------------------------------------------------

    array('GET', $relativePath.'/telegram/'.$environment['telegram']['webhook'].'/webhook', 'telegram:webhook'),
    array('POST', $relativePath.'/telegram/'.$environment['telegram']['webhook'].'/command', 'telegram:command')
));

if (! $match = $router->match()) $match = array('target' => 'error:404');

$target = explode(':', $match['target']);

if ($target[0] == 'ajax') {

    $output = array('status' => 0, 'action' => 'no-action');

    //--------------------------------------------------------------------------
    // Controlador de la API
    //--------------------------------------------------------------------------

    if (isset($session['id']) || $target[1] == 'login') {
    
        if (! include $core->getCorePath().'/controllers/ajax/'.$target[1].'.php') $output['code'] = 'no-permission';

        $output['action'] = $target[1];
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($disableLog)) $core->log('visits', explode('?', $_SERVER['REQUEST_URI'], 2)[0]);

    } else $output['code'] = 'no-login';
} else if ($target[0] == 'telegram') {

    $output = array('status' => 0, 'action' => $target[1]);

    //--------------------------------------------------------------------------
    // Controlador del bot de telegram
    //--------------------------------------------------------------------------

    $telegram = new Longman\TelegramBot\Telegram($environment['telegram']['token'], $environment['telegram']['name']);
    include $core->getCorePath().'/controllers/telegram/'.$target[1].'.php';
} else $output = array('status' => 0, 'code' => 'no-controller');

echo json_encode($output); die;