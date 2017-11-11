import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { LocationStrategy, HashLocationStrategy, CommonModule } from '@angular/common';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app.routing';

//------------------------------------------------------------------------------
//  Modules
//------------------------------------------------------------------------------

import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { BsDropdownModule } from 'ngx-bootstrap/dropdown';
import { ProgressbarModule } from 'ngx-bootstrap/progressbar';
import { ModalDirective, ModalModule } from 'ngx-bootstrap/modal';

import { SelectModule } from 'ng-select';
import { TextMaskModule } from 'angular2-text-mask';
import { ToasterModule, ToasterService} from 'angular2-toaster/angular2-toaster';
import { ClipboardModule } from 'ngx-clipboard';
import { NouisliderModule } from 'ng2-nouislider';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';

import { Observable } from 'rxjs/Observable';

//------------------------------------------------------------------------------
//  Panel Components
//------------------------------------------------------------------------------

import { NAV_DROPDOWN_DIRECTIVES } from './shared/nav-dropdown.directive';
import { SIDEBAR_TOGGLE_DIRECTIVES } from './shared/sidebar.directive';
import { AsideToggleDirective } from './shared/aside.directive';
import { BreadcrumbsComponent } from './shared/breadcrumb.component';

//------------------------------------------------------------------------------
//  Services
//------------------------------------------------------------------------------

import { ApiService } from './services/api.service';
import { NotificationService } from './services/notification.service';

//------------------------------------------------------------------------------
//  Layouts
//------------------------------------------------------------------------------

import { LoginComponent } from './layouts/login.component';
import { PanelComponent } from './layouts/panel.component';

//------------------------------------------------------------------------------
//  Pages
//------------------------------------------------------------------------------

import { HomePageComponent } from './pages/home/home.component';
import { DebtsPageComponent } from './pages/debts/debts.component';
import { UsersPageComponent } from './pages/users/users.component';

//------------------------------------------------------------------------------
//  Modals
//------------------------------------------------------------------------------

import { PaymentModalComponent } from './pages/home/modals/payment.modal.component';
import { DebtModalComponent } from './pages/debts/modals/debt.modal.component';
import { UserModalComponent } from './pages/users/modals/user.modal.component';

import { environment } from '../environments/environment';

export function jwtOptionsFactory() {
  return {
    tokenGetter: ApiService.getToken
  }
}

@NgModule({
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    ModalModule,
    BsDropdownModule.forRoot(),
    ProgressbarModule.forRoot(),
    HttpClientModule,
    SelectModule,
    ToasterModule,
    BrowserAnimationsModule,
    CommonModule,
    NgxDatatableModule,
    ClipboardModule,
    NouisliderModule
  ],
  declarations: [
    AppComponent,

    // Layouts

    LoginComponent,
    PanelComponent,

    // Pages

    HomePageComponent,
    DebtsPageComponent,
    UsersPageComponent,

    // Modals

    DebtModalComponent,
    UserModalComponent,
    PaymentModalComponent,

    NAV_DROPDOWN_DIRECTIVES,
    BreadcrumbsComponent,
    SIDEBAR_TOGGLE_DIRECTIVES,
    AsideToggleDirective
  ],
  providers: [ApiService, NotificationService, {
    provide: LocationStrategy,
    useClass: HashLocationStrategy
  }],
  bootstrap: [ AppComponent ],
  schemas: [ CUSTOM_ELEMENTS_SCHEMA ],
  entryComponents: [ DebtModalComponent, UserModalComponent, PaymentModalComponent ]
})
export class AppModule { }
