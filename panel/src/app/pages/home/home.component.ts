import { Component, ViewChild } from '@angular/core';
import { Router } from "@angular/router";
import { ApiService } from '../../services/api.service';
import { NotificationService } from '../../services/notification.service';
import { PaymentModalComponent } from './modals/payment.modal.component';
import { LoggedUserComponent } from "app/base/logged.user.component";

@Component({
  templateUrl: 'home.component.html'
})
export class HomePageComponent extends LoggedUserComponent {

    public users:Array<any> = [];
    public debts:Array<any> = [];

    @ViewChild(PaymentModalComponent) modalPayment: PaymentModalComponent;
    constructor(private api: ApiService, public notificationService: NotificationService, router: Router) {
        super(api, router);

        this.api.get('/users').then(response => {
            if (response['status'] == 1) { this.users = response['data'];
                this.reload();
            } else this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'No es posible obtener los usuarios'});
        });
    }

    public reload() {
        this.api.get('/debts').then(response => {

            if (response['status'] == 1) {

                this.debts = response['data'];

                for (let debt of this.debts) {

                    var users:any = [];
                    debt['stats'] = { total: debt.users.length, pending: 0, paid: 0, confirmed: 0, rejected: 0 };

                    for (let user of this.users) {
                        let debtor;
                        if (debtor = debt.users.find(debtor => debtor.user == user.id)) {
                            users.push(debtor);
                            debt['stats'][debtor.state]++;
                        } else users.push({ user: user.id, quantity: 0, state: false });
                        if (users[users.length-1]['user'] == this.getUser()['id']) debt['mine'] = users[users.length-1];
                    } debt['users'] = users;
                } console.log(this.debts);
            } else this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'No es posible obtener las deudas'});
        });
    }

}
