<?php
    require __DIR__ . '/../config/db.php';
    require __DIR__ . '/../vendor/autoload.php';

    $router = new AltoRouter();

    $baseURL = '/deudas/api';

    $router->addRoutes(array(
        array( 'GET', $baseURL.'/', 'home'),

        // Users
        
        array( 'GET', $baseURL.'/users', 'users/list'),
        array( 'GET', $baseURL.'/users/[i:id]', 'users/get'),
    ));

    $output = array('status' => 0);

    $match = $router->match();

    if( $match ) {
        $controller = './controllers/'.$match['target'].'.php';
        if (file_exists($controller)) {

            $database = DB::getConnection();
            
            if (!$dataBase->connect_errno) {
                include $controller;

                $output['action'] = str_replace('/', '-', $match['target']);
            }
            else {
                $output['code'] = 'no-db';
                $output['return'] = 'Internal error, database error';
            }
        } 
        else {
            $output['code'] = 'no-controller';
            $output['return'] = 'Internal error, unable to find the controller';
        }
    } else {
        $output['code'] = 'no-found';
        $output['return'] = 'This option cant be found';
    }

    echo json_encode($output);

?>