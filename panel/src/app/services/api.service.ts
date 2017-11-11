import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

import { HttpClient, HttpParams, HttpHeaders } from "@angular/common/http";
import { environment } from '../../environments/environment';

@Injectable()
export class ApiService {

    private baseURL:string = environment.server + '/api';
    private token:string;
    private user:any;
    
    constructor(private http: HttpClient, private router: Router) { this.reload() }

    private reload() {
        if (this.token = window.localStorage.getItem(ApiService.key)) {
            this.get('/whoami').then(response => {
                if (response['status'] == 0) this.router.navigateByUrl('/login');
                else this.user = response['data'];
            });
        } else this.router.navigateByUrl('/login');
    }
 
    public get(endpoint, data = null, auth = true) {

        let params: HttpParams = new HttpParams();
        if (auth) params = params.append('token', this.token);
        for (var key in data) params = params.set(key, data[key] || '');
        
        return new Promise((resolve, reject) => {
            if (this.baseURL) this.http.get(this.endpoint(endpoint), { params: params }).subscribe(response => {
                if (!environment.production) {
                    console.log('GET: ');
                    console.log(response);
                } resolve(response);
            }, error => reject(error));
        });
    }

    public post(endpoint, data, auth = true) {
     
        let params = new HttpParams();
        if (auth) params = params.append('token', this.token);
        for (var key in data) params = params.append(key, data[key] || '');

        var headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return new Promise((resolve, reject) => {
            this.http.post(this.endpoint(endpoint), params.toString(), { headers: headers }).subscribe(response => {
                if (!environment.production) {
                    console.log('GET: ');
                    console.log(response);
                } resolve(response);
            }, error => reject(error));
        });
    }

    public login(user) {
        return new Promise((resolve, reject) => {
            this.post('/login', user, false).then(response => {
                if (response['status'] == 1) {
                    window.localStorage.setItem(ApiService.key, response['data']['token']);
                    this.token = response['data']['token'];
                    resolve(true);
                } else reject(response['code']);
            }).catch(code => reject('no-server'));
        });
    }

    public logout() {
        localStorage.removeItem(ApiService.key);
        this.token = null;
        this.user = null;
        this.router.navigateByUrl('/login');
    }

    public endpoint(endpoint:string) { return this.baseURL + endpoint }

    public getUser(token:string = this.token) { return this.user ? this.user : this.user = JSON.parse(window.atob(token.split('.')[1].replace('-', '+').replace('_', '/'))) }

    public setToken(token:string) { this.getUser(this.token = token) }
}