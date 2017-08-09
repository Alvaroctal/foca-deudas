import {Router} from "@angular/router";
import {LoggedUserService} from "app/services/logged.user.service";
import {Component, OnInit} from "@angular/core";

@Component({})
export class LoggedUserComponent implements OnInit {

    constructor(protected router: Router, private loggedUserService: LoggedUserService) {}

    ngOnInit() {
        this.checkLogin();
    }

    protected checkLogin() {
        if(this.loggedUserService.user && this.router.url === '/login') {
            this.router.navigateByUrl('/home');
        }
    }

    protected getUsername(): string {
        this.loggedUserService.loadUser();
        return this.loggedUserService.user.username;
    }

    protected logout() {
        window.localStorage.clear();
        this.loggedUserService.user = null;
        this.router.navigateByUrl('/login');
    }

}
