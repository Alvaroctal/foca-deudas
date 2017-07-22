import { Component, Input, ViewChild, EventEmitter, Output } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap';
import { ApiService } from '../../../services/api.service';
import { NotificationService } from '../../../services/notification.service';
import { IOption } from 'ng-select';

@Component({
  selector: 'modal-user',
  templateUrl: './user.modal.component.html'
})
export class UserModalComponent {

    public title:string;
    public data:any = {};
    public ranks: Array<IOption> = [{value: 'user', label: 'Usuario'},{value: 'developer', label: 'Desarrollador'}];
    public math = Math;
    public working:string;
    @ViewChild('modal') public modal:ModalDirective;
    @Output() reload = new EventEmitter();
    @Input() api:ApiService;
    constructor(public notificationService: NotificationService) {}
    
    public show(user = null) {
        if (user) this.api.get('/users/' + user['id']).then(result => this.data = result['data']);
        else this.data = {};
        this.modal.show();
    }

    public hide(update = false) {
        if (update) this.reload.emit();
        this.modal.hide();
    }

    public do(action:string) {
        this.working = action;

        this.api.post('/users/' + action, this.data).then(result => {
            let toast:any;
            this.working = undefined;
            if (result['status'] == 1) { this.hide(true);
                if (action == 'create') toast = { type: 'success', title: 'Usuario creado', body: 'El usuario ha sido creado'};
                else if (action == 'update') toast = { type: 'success', title: 'Usuario modificado', body: 'El usuario ha sido modificado'};
                else if (action == 'delete') toast = { type: 'success', title: 'Usuario eliminado', body: 'El usuario ha sido eliminado'};
            } else {
                if (result['code'] == 'no-input') toast = { type: 'warning', title: 'Formulario incompleto', body: 'Quedan campos sin rellenar'};
                else if (result['code'] == 'no-email') toast = { type: 'warning', title: 'Email no válido', body: 'El email introducido no es válido'};
                else if (result['code'] == 'no-passwd') toast = { type: 'warning', title: 'Contraseña no válida', body: 'La contraseña introducida no es válida'};
                else if (result['code'] == 'duplicate') toast = { type: 'warning', title: 'Usuario duplicado', body: 'Ya existe un usuario con ese username, paypal o email'};
                else toast = { type: 'error', title: 'Error desconocido', body: 'Se ha recibido un error inesperado (' + result['code'] + ')'};   
            } this.notificationService.emit(toast);
        }).catch(error => {
            this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'El servidor encontró un error'}); console.log(error);
        });
    }

    public create() { this.do('create') }

    public update() { this.do('update') }

    public delete() { this.do('delete') }
}