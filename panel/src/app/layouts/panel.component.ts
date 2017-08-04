import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { ToasterService, ToasterConfig } from 'angular2-toaster/angular2-toaster';
import { NotificationService } from '../services/notification.service';
import { ApiService } from '../services/api.service';
import { LoggedUserComponent } from "../base/logged.user.component";

@Component({
    selector: 'app-dashboard',
    templateUrl: './panel.component.html', 
    styleUrls: ['panel.css']
})
export class PanelComponent extends LoggedUserComponent {

    public version:string = '1.0-RC';
    public page:string = 'debts';
    public toasterconfig: ToasterConfig = new ToasterConfig({ tapToDismiss: true, timeout: 5000 });

    constructor(toasterService: ToasterService, notificationService: NotificationService, api: ApiService, router: Router) {
        super(api, router);

        notificationService.listen.subscribe(toast => toasterService.pop(toast));
    }

    public disabled:boolean = false;
    public status:{isopen:boolean} = {isopen: false};

    public toggleDropdown($event:MouseEvent):void {
        $event.preventDefault();
        $event.stopPropagation();
        this.status.isopen = !this.status.isopen;
    }

}
