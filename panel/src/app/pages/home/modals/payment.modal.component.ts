import { Component, Input, ViewChild, EventEmitter, Output } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap';
import { ApiService } from '../../../services/api.service';
import { NotificationService } from '../../../services/notification.service';
import { IOption } from 'ng-select';
import {LoggedUserService} from "../../../services/logged.user.service";

@Component({
  selector: 'modal-payment',
  templateUrl: './payment.modal.component.html'
})
export class PaymentModalComponent {

    public title:string;
    public data:any = {};
    public working:string;
    public methods: Array<IOption> = [
        {value: 'cash', label: 'Metalico'},
        {value: 'paypal', label: 'PayPal'},
        {value: 'bank', label: 'Traferencia'},
        {value: 'other', label: 'Otro'}
    ];

    @ViewChild('modal') public modal:ModalDirective;
    @Output() reload = new EventEmitter();
    @Input() api: ApiService;
    @Input() notificationService: NotificationService;
    @Input() loggedUserService: LoggedUserService;
    constructor() {}

    public show($debt = null, $user = null) {
        if ($debt) this.api.get('/debts/' + $debt['id']).then(result => { this.data = result['data'];
            this.api.get('/users/' + this.data['user']).then(result => {
                this.data['user'] = Object.assign({}, result['data'], ($user ? $user : $debt['mine']));
                console.log(this.data);
            });
        }); this.modal.show();
    }
    
    public hide(update = false) {
        if (update) this.reload.emit();
        this.modal.hide();
    }

    public do(action:string, data = null) {
        this.working = action;

        this.api.post('/debts/' + action, Object.assign({}, { debt: this.data.id }, data)).then(result => {
            let toast:any;
            this.working = undefined;
            if (result['status'] == 1) { this.hide(true);
                if (action == 'pay') toast = { type: 'success', title: 'Deuda pagada', body: 'La deuda ha sido marcada como pagada'};
                else if (action == 'resolve') toast = { type: 'success', title: 'Deuda resuelta', body: 'La deuda ha sido resuelta'};
            } else {
                if (result['code'] == 'no-input') toast = { type: 'warning', title: 'Formulario incompleto', body: 'Quedan campos sin rellenar'};
                else toast = { type: 'error', title: 'Error desconocido', body: 'Se ha recibido un error inesperado (' + result['code'] + ')'};   
            } this.notificationService.emit(toast);
        }).catch(error => {
            this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'El servidor encontró un error'}); console.log(error);
        });
    }

    public pay() { this.do('pay', { method: this.data.user.method }) }

    public resolve(state:string = 'confirmed') { this.do('resolve', { user: this.data.user.user, state: state }) }

}
