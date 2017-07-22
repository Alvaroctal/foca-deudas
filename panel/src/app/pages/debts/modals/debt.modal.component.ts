import { Component, Input, ViewChild, EventEmitter, Output } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap';
import { ApiService } from '../../../services/api.service';
import { NotificationService } from '../../../services/notification.service';
import { SelectComponent, IOption } from 'ng-select';

@Component({
  selector: 'modal-debt',
  templateUrl: './debt.modal.component.html'
})
export class DebtModalComponent {

    public title:string;
    public data:any = {};
    public users:Array<any> = [];
    public working:string;
    public someValue: number = 5;
    public max:number = 8;
    @ViewChild('modal') public modal:ModalDirective;
    @Output() reload = new EventEmitter();
    @Input() api:ApiService;
    constructor(public notificationService: NotificationService) {}

    public show($debt = null) {
        this.api.get('/users').then(response => {
            if (response['status'] == 1) { 
                this.users = response['data'];

                if ($debt) this.api.get('/debts/' + $debt['id']).then(result => {
                    this.data = result['data'];
                    this.max = Number(this.data.quantity);

                    for (let user of this.users) if (this.data.users.find(debtor => debtor.user == user['id'])) user['disabled'] = true;
                    console.log(this.users);
                    console.log(this.data.users);
                }); else this.data = { users: [] };
            } else this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'No es posible obtener los usuarios'});
        }); this.modal.show();
    }
    
    public hide(update = false) {
        if (update) this.reload.emit();
        this.modal.hide();
    }

    public do(action:string) {

        this.working = action;

        for (let user of this.data['users']) this.data['user-'+ user.user] = user.quantity;

        this.api.post('/debts/' + action, this.data).then(result => {
            let toast:any;
            this.working = undefined;
            if (result['status'] == 1) { this.hide(true);
                if (action == 'create') toast = { type: 'success', title: 'Deuda creada', body: 'La deuda ha sido creada'};
                else if (action == 'update') toast = { type: 'success', title: 'Deuda modificada', body: 'La deuda ha sido modificada'};
                else if (action == 'delete') toast = { type: 'success', title: 'Deuda eliminada', body: 'La deuda ha sido eliminada'};
            } else {
                if (result['code'] == 'no-input') toast = { type: 'warning', title: 'Formulario incompleto', body: 'Quedan campos sin rellenar'};
                else toast = { type: 'error', title: 'Error desconocido', body: 'Se ha recibido un error inesperado (' + result['code'] + ')'};   
            } this.notificationService.emit(toast);
        }).catch(error => {
            this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'El servidor encontró un error'}); console.log(error);
        });
    }

    public create() { this.do('create') }

    public update() { this.do('update') }

    public delete() { this.do('delete') }

    public setMax(quantity) {
        if ((!isNaN(quantity = Number(quantity))) && quantity > 0) this.max = quantity;
    }

    public addUser($event) {
        for (let user of this.users) { 
            if (user['id'] == $event.id) {
                user['disabled'] = true;
                this.data.users.push({ user: $event.id, username: $event.username, quantity: (this.max / (this.data.users.length + 1)).toFixed(2) });
                for (let index in this.data.users) {
                    this.data.users[index]['quantity'] = (this.max / this.data.users.length).toFixed(2);
                } break;
            }
        }
    }

    public removeUser($event) {
        for (let user of this.users) { 
            if (user['id'] == $event.id) {
                let length = this.data.users.length - 1;
                for (let index in this.data.users) {
                    this.data.users[index]['quantity'] = (this.max / length).toFixed(2);
                    if (this.data.users.id == $event.id) this.data.users.splice(index, 1);
                } user['disabled'] = false;
            }
        } console.log(this.users);
    }

    public debtChange(user, $event) {
        console.log($event);
    }
}