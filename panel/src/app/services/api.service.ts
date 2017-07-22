import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

import { Http, Headers, URLSearchParams } from "@angular/http";
import { environment } from '../../environments/environment';
import { JwtHelper} from 'angular2-jwt';

@Injectable()
export class ApiService {

    private baseURL:string = environment.server + '/api';
    private token:string;
    private user:any;
    
    constructor(private _http: Http, private router: Router) { this.reload() }

    private reload() {
        if (this.token = window.localStorage.getItem('token')) {
            this.get('/whoami').then(response => {
                if (response['status'] == 0) this.router.navigateByUrl('/login');
                else this.user = response['data'];
            });
        } else this.router.navigateByUrl('/login');
    }
 
    public get(endpoint, data = null, auth = true) {

        let params: URLSearchParams = new URLSearchParams();
        if (auth) params.append('token', this.token);
        for (var key in data) params.append(key, data[key]);
        
        return new Promise((resolve, reject) => {
            this._http.get(this.baseURL + endpoint, { search: params }).subscribe(response => {
                console.log('GET: ');
                console.log(response);
                resolve(response.json());
            }, error => reject(error));
        });
    }

    public post(endpoint, data, auth = true) {
     
        let encode: URLSearchParams = new URLSearchParams();
        if (auth) encode.append('token', this.token);
        for (var key in data) encode.append(key, data[key]);

        var headers = new Headers();
        headers.append('Content-Type', 'application/x-www-form-urlencoded');

        return new Promise((resolve, reject) => {
            this._http.post(this.baseURL + endpoint, encode.toString(), { headers: headers }).subscribe(response => {
                console.log('POST: ');
                console.log(response);
                resolve(response.json());
            }, error => reject(error));
        });
    }

    public getUser() {
        return this.user;
    }

    public login(user) {
        return new Promise((resolve, reject) => {
            this.post('/login', user, false).then(response => {
                if (response['status'] == 1) {
                    window.localStorage.setItem('token', this.token = response['data']['token']);
                    this.user = new JwtHelper().decodeToken(this.token);
                    resolve(true);
                } else reject(response['code']);
            }).catch(code => reject('no-server'));
        });
    }

    public logout() {
        window.localStorage.clear();
        this.token = null;
        this.user = null;
        this.router.navigateByUrl('/login');
    }
}