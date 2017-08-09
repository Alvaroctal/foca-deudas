import {Injectable} from "@angular/core";
import {JwtHelper} from "angular2-jwt";

const TOKEN_KEY = 'token';

@Injectable()
export class LoggedUserService {

    private _user: any = null;

    constructor() {
        this.loadUser();
    }

    get user(): any {
        return this._user;
    }

    set user(value: any) {
        this._user = value;
    }

    public loadUser() {
        let token: string = window.localStorage.getItem(TOKEN_KEY);

        if(token !== null) {
            this._user = new JwtHelper().decodeToken(token);
        }
    }

}
