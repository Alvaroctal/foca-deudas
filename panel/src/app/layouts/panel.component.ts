import {Component, OnInit} from '@angular/core';
import { ToasterService, ToasterConfig } from 'angular2-toaster/angular2-toaster';
import { NotificationService } from '../services/notification.service';
import { LoggedUserComponent } from "../base/logged.user.component";
import { LoggedUserService } from "../services/logged.user.service";
import { Router } from "@angular/router";

@Component({
    selector: 'app-dashboard',
    templateUrl: './panel.component.html', 
    styleUrls: ['panel.css']
})
export class PanelComponent extends LoggedUserComponent implements OnInit {

    public version:string = '1.0-RC';
    public page:string = 'home';
    public toasterconfig: ToasterConfig = new ToasterConfig({ tapToDismiss: true, timeout: 5000 });
    public disabled:boolean = false;
    public status:{isopen:boolean} = {isopen: false};
    public username: string;

    constructor(
        private toasterService: ToasterService,
        private notificationService: NotificationService,
        router: Router,
        loggedUserService: LoggedUserService
    ) {
        super(router, loggedUserService);
    }

    ngOnInit() {
        this.username = this.getUsername();
        this.notificationService.listen.subscribe(toast => this.toasterService.pop(toast));
    }

}
