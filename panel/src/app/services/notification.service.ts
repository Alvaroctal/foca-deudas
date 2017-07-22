import { Observable } from 'rxjs/Observable';
import { Injectable } from '@angular/core';
import { Subject } from 'rxjs/Subject';

@Injectable()
export class NotificationService {

    private listener = new Subject<any>();

    listen = this.listener.asObservable();

    emit(toast: any) {
        this.listener.next(toast);
    }
}