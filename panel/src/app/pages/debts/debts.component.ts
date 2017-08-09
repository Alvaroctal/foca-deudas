import { Component, OnInit, ViewChild } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { NotificationService } from '../../services/notification.service';
import { DebtModalComponent } from './modals/debt.modal.component';
import { DatatableComponent } from '@swimlane/ngx-datatable';

@Component({
    selector: 'debt-component',
    templateUrl: 'debts.component.html'
})
export class DebtsPageComponent implements OnInit {

    public debts:Array<any> = [];
    public rows:Array<any> = [];

    @ViewChild(DebtModalComponent) modalDebt:DebtModalComponent;
    @ViewChild(DatatableComponent) table: DatatableComponent;
    constructor(private api: ApiService, private notificationService: NotificationService) {}

    ngOnInit() {
        this.reload();
    }

    public reload() {
        this.api.get('/debts').then(response => {
            if (response['status'] == 1) this.rows = this.debts = response['data'];
            else this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'No es posible obtener los usuarios'});
        });
    }

    public filter(event) {
        const val = event.target.value.toLowerCase();

        this.rows = this.debts.filter(function(user) {
          return user.username.toLowerCase().indexOf(val) !== -1 || !val;
        }); this.table.offset = 0;
    }

}
