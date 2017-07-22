import { Component } from '@angular/core';
import * as moment from "moment";

moment.locale('es');

@Component({
    selector: 'body',
    template: '<router-outlet></router-outlet>'
})
export class AppComponent { 
	
}
