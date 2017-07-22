import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { ToasterModule, ToasterService, ToasterConfig } from 'angular2-toaster/angular2-toaster';
import { NotificationService } from '../services/notification.service';
import { ApiService } from '../services/api.service';

@Component({
    selector: 'app-dashboard',
    templateUrl: './panel.component.html', 
    styleUrls: ['panel.css']
})
export class PanelComponent {

    public version:string = '1.0-RC';
    public page:string = 'debts';
    public user:any;
    public toasterconfig: ToasterConfig = new ToasterConfig({ tapToDismiss: true, timeout: 5000 });
    constructor(toasterService: ToasterService, notificationService: NotificationService, public api: ApiService, router: Router) {
        notificationService.listen.subscribe(toast => toasterService.pop(toast));
        if (this.user = this.api.getUser()) this.page = router.url;
        else router.navigateByUrl('/login')
    }

    public disabled:boolean = false;
    public status:{isopen:boolean} = {isopen: false};

    public toggleDropdown($event:MouseEvent):void {
        $event.preventDefault();
        $event.stopPropagation();
        this.status.isopen = !this.status.isopen;
    }
}
