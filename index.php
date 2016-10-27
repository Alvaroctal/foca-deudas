<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">
        <title>Foca Deudas</title>

        <!-- Bootstrap  CSS -->

        <link href="/deudas/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Page CSS -->

        <link href="/deudas/assets/css/app.css" rel="stylesheet">
    </head>
    <body ng-app="foca-deudas">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Foca Deudas</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li class="active"><a href="#">Overview</a></li>
                        <li><a href="#">Users</a></li>
                    </ul>
                </div>
                
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <div ng-view></div>
                </div>
            </div>
        </div>

        <!-- Core JS -->

        <script src="/deudas/node_modules/angular/angular.min.js"></script>
        <script src="/deudas/node_modules/angular-route/angular-route.min.js"></script>
        <script src="/deudas/node_modules/angular-ui-bootstrap/dist/ui-bootstrap.js"></script>
        <script src="/deudas/node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js"></script>
        <script src="/deudas/node_modules/jquery/dist/jquery.min.js"></script>
        <script src="/deudas/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Page JS -->

        <script src="/deudas/assets/js/app.js"></script>

        <script type="text/javascript">
            var focaDeudas = angular.module('foca-deudas', ['ngRoute','ui.bootstrap']);

            focaDeudas.config(['$routeProvider', function($routeProvider) {
                $routeProvider.when('/overview', {
                    templateUrl: 'views/partials/overview.html',
                    controller: 'AddOrderController'
                }).when('/users', {
                    templateUrl: 'views/partials/users.html',
                    controller: 'ShowOrdersController'
                }).when('/404', {
                    templateUrl: 'views/partials/404.html',
                    controller: 'ShowOrdersController'
                }).otherwise({
                    redirectTo: '/404'
                });
            }]);
        </script>
    </body>
</html>