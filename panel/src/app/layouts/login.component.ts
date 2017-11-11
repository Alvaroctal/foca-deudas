import { Component } from '@angular/core';
import { ApiService } from '../services/api.service';
import { Router } from '@angular/router';

@Component({
    templateUrl: 'login.component.html',
    styleUrls: ['login.css']
})
export class LoginComponent {

    public credentials:any = {
        user: <any> null,
        passwd: <string> ''
    };
    public error:string;
    public working:string;

    constructor(public api: ApiService, public router: Router) { }

    doLogin() {

        if(this.credentials.user == '' || this.credentials.passwd === '') {
            this.error = 'Introduzca sus datos de acceso';
        } else {
            this.working = 'login';

            this.api.login(this.credentials).then(result => this.router.navigateByUrl('/home')).catch(code => {
                if (code == 'no-server') this.error = 'No se ha podido establecer conexión el servidor';
                else if (code == 'no-user') this.error = 'Introduzca un user válido';
                else if (code == 'no-sentence') this.error = 'El servidor encontró un error y no pudo continuar';
                else if (code == 'no-login') this.error = 'Usuario y/o contraseña inválidos';
                else this.error = 'Error desconocido';

                setTimeout(() => this.error = null, 5000); this.working = undefined;
            });
        }
    }
}