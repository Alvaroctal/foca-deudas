import { Component, ViewChild } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { NotificationService } from '../../services/notification.service';
import { UserModalComponent } from './modals/user.modal.component';
import { ModalDirective } from 'ngx-bootstrap';
import { DatatableComponent } from '@swimlane/ngx-datatable';

@Component({
  templateUrl: 'users.component.html'
})
export class UsersPageComponent {

    public users:Array<any> = [];
    public rows:Array<any> = [];
    @ViewChild(UserModalComponent) modalUser:UserModalComponent;
    @ViewChild(DatatableComponent) table: DatatableComponent;
    constructor(public api:ApiService, public notificationService: NotificationService) {
        this.reload()
    }

    public reload() {
        this.api.get('/users').then(response => {
            if (response['status'] == 1) this.rows = this.users = response['data'];
            else this.notificationService.emit({ type: 'danger', title: 'Error interno', body: 'No es posible obtener los usuarios'});
        });
    }

    public filter(event) {
        const val = event.target.value.toLowerCase();

        this.rows = this.users.filter(function(user) {
          return user.username.toLowerCase().indexOf(val) !== -1 || !val;
        }); this.table.offset = 0;
    }
}