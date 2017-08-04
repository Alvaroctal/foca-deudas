import {ApiService} from "../services/api.service";
import {Router} from "@angular/router";

export class LoggedUserComponent {

    private router: Router;

    protected user: any;

    constructor(api: ApiService, router: Router) {
        this.router = router;

        this.checkLogin(api, router);
    }

    protected getUser() {
        return this.user;
    }

    protected checkLogin(api: ApiService, router: Router) {
        api.getUserAsync().then(response => {
            if(response) {
                this.user = response['data'];
            }
            else {
                router.navigateByUrl('/login');
            }
        }, () => {
            router.navigateByUrl('/login');
        });
    }

    public logout() {
        window.localStorage.clear();
        this.user = null;
        this.router.navigateByUrl('/login');
    }

}
