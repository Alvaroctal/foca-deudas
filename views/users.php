<h1 class="page-header">Morosos</h1>
<div class="row placeholders">
    <div class="col-xs-6 col-sm-3 placeholder">
        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
        <h4>Moroso Supremo</h4>
        <span class="text-muted">Merece que le partamos las piernas</span>
    </div>
</div>
<div class="panel panel-default">

    <div class="panel-heading">Usuarios</div>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Sitio</th>
                <th>Paypal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php

                include_once __DIR__ . '/../models/user.php';

                foreach (User::getAll() as $user) {
                    echo '  <tr>
                                <td>'.$user['name'].' ('.$user['username'].')</td>
                                <td>'.$user['place'].'</td>
                                <td><span class="label label-'.($user['paypal'] ? 'success' : 'default').'">'.($user['paypal'] ? $user['paypal'] : 'no establecido').'</span></td>
                                <td><button class="btn btn-warning btn-xs btn-edit-user" btn-data=\'{"id": '.$user['id'].'}\'>Editar</button></td>
                            </tr>';
                }
            ?>
        
        </tbody>
    </table>
</div>

<!-- Modals -->

<div id="modal-edit-user" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar usuario</h4>
            </div>
            <div class="modal-body">
                <form id="form-update-user" action="/deudas/update/" method="POST">
                    <input name="id" type="hidden">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <input type="text" name="name" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <input type="text" name="surname" class="form-control" placeholder="Apellido">
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <input type="text" name="username" class="form-control" placeholder="Nick">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <input type="text" name="place" class="form-control" placeholder="Sitio">
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <input type="text" name="paypal" class="form-control" placeholder="Paypal">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="update-user" type="submit" class="btn btn-warning" form="form-update-user">Actualizar</button>
            </div>
        </div>
    </div>
</div>